<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Category;
use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\CheckoutService;
use App\Services\Payment\PaymentGatewayInterface;
use App\Services\Payment\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OrderWorkflowSafetyTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::query()->create([
            'name' => 'Workflow Admin',
            'email' => 'workflow-admin@example.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    private function customer(): User
    {
        return User::query()->create([
            'name' => 'Workflow Customer',
            'email' => 'workflow-customer@example.test',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'is_active' => true,
        ]);
    }

    private function buildOrder(): array
    {
        $customer = $this->customer();

        $category = Category::query()->create([
            'name' => 'Workflow Category',
            'slug' => 'workflow-category',
            'is_active' => true,
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Workflow Product',
            'slug' => 'workflow-product',
            'sku' => 'WORKFLOW-PRODUCT',
            'price' => 2_000_000,
            'stock' => 5,
            'is_active' => true,
        ]);

        $cart = Cart::query()->create([
            'user_id' => $customer->id,
            'session_id' => null,
        ]);

        $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 1,
        ]);

        $address = Address::query()->create([
            'user_id' => $customer->id,
            'title' => 'Home',
            'full_name' => $customer->name,
            'phone' => '09120000000',
            'country' => 'Iran',
            'province' => 'Tehran',
            'city' => 'Tehran',
            'postal_code' => '1234567890',
            'address' => 'Workflow test address',
            'is_default' => true,
        ]);

        $order = app(CheckoutService::class)->createOrder($customer, $address);

        return [$customer, $product, $order];
    }

    private function successfulGateway(): PaymentGatewayInterface
    {
        return new class implements PaymentGatewayInterface {
            public function purchase(Order $order): array
            {
                return [
                    'authority' => 'AUTH-WORKFLOW-1',
                    'redirect_url' => 'https://gateway.test/pay/AUTH-WORKFLOW-1',
                ];
            }

            public function verify(Order $order, string $authority): array
            {
                return [
                    'success' => true,
                    'tracking_code' => 'TRACK-WORKFLOW-1',
                    'message' => 'paid',
                ];
            }

            public function refund(Order $order, int $amount): array
            {
                return [
                    'success' => true,
                    'tracking_code' => 'REFUND-WORKFLOW-1',
                    'message' => 'refunded',
                ];
            }
        };
    }

    public function test_admin_cancelling_an_unpaid_order_releases_reserved_stock_once(): void
    {
        $admin = $this->admin();
        [, $product, $order] = $this->buildOrder();

        $this->actingAs($admin)
            ->from(route('admin.orders.show', $order))
            ->patch(route('admin.orders.status', $order), [
                'status' => 'cancelled',
                'note' => 'Customer requested cancellation',
            ])
            ->assertRedirect(route('admin.orders.show', $order));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'cancelled',
            'payment_status' => 'failed',
        ]);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 5,
        ]);

        $this->assertSame(
            1,
            InventoryMovement::query()
                ->where('reference_type', Order::class)
                ->where('reference_id', $order->id)
                ->where('type', 'return')
                ->count()
        );

        $this->actingAs($admin)
            ->from(route('admin.orders.show', $order))
            ->patch(route('admin.orders.status', $order), [
                'status' => 'cancelled',
            ])
            ->assertRedirect(route('admin.orders.show', $order));

        $this->assertSame(
            1,
            InventoryMovement::query()
                ->where('reference_type', Order::class)
                ->where('reference_id', $order->id)
                ->where('type', 'return')
                ->count()
        );
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 5,
        ]);
    }

    public function test_admin_cannot_cancel_an_order_while_a_gateway_payment_is_pending(): void
    {
        [, $product, $order] = $this->buildOrder();
        $this->app->instance(PaymentGatewayInterface::class, $this->successfulGateway());
        app(PaymentService::class)->start($order);

        $this->actingAs($this->admin())
            ->from(route('admin.orders.show', $order))
            ->patch(route('admin.orders.status', $order), [
                'status' => 'cancelled',
            ])
            ->assertRedirect(route('admin.orders.show', $order))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 3,
        ]);
        $this->assertSame(
            0,
            InventoryMovement::query()
                ->where('reference_type', Order::class)
                ->where('reference_id', $order->id)
                ->where('type', 'return')
                ->count()
        );
    }

    public function test_paid_order_must_be_fully_refunded_before_pre_shipment_cancellation(): void
    {
        $admin = $this->admin();
        [, $product, $order] = $this->buildOrder();
        $this->app->instance(PaymentGatewayInterface::class, $this->successfulGateway());

        $payments = app(PaymentService::class);
        $payments->start($order);
        $payments->verify($order->fresh(), 'AUTH-WORKFLOW-1');

        $this->actingAs($admin)
            ->from(route('admin.orders.show', $order))
            ->patch(route('admin.orders.status', $order), [
                'status' => 'cancelled',
            ])
            ->assertRedirect(route('admin.orders.show', $order))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'processing',
            'payment_status' => 'paid',
        ]);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 3,
        ]);

        $this->actingAs($admin)
            ->from(route('admin.orders.show', $order))
            ->post(route('admin.orders.refund', $order), [
                'amount' => $order->total,
            ])
            ->assertRedirect(route('admin.orders.show', $order))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'refunded',
            'status' => 'processing',
        ]);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 3,
        ]);

        $this->actingAs($admin)
            ->from(route('admin.orders.show', $order))
            ->patch(route('admin.orders.status', $order), [
                'status' => 'cancelled',
            ])
            ->assertRedirect(route('admin.orders.show', $order))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'cancelled',
            'payment_status' => 'refunded',
        ]);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 5,
        ]);
    }
}
