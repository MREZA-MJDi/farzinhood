<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Category;
use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use App\Services\CheckoutService;
use App\Services\Payment\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

class PaymentFailureSafetyTest extends TestCase
{
    use RefreshDatabase;

    public function test_unavailable_gateway_commits_failed_payment_cancels_order_and_releases_reserved_stock(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'is_active' => true,
        ]);

        $category = Category::query()->create([
            'name' => 'سینک',
            'slug' => 'payment-safety-sink',
            'is_active' => true,
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'سینک تست',
            'slug' => 'payment-safety-product',
            'sku' => 'PAYMENT-SAFE-001',
            'price' => 2_000_000,
            'stock' => 4,
            'is_active' => true,
        ]);

        $address = Address::query()->create([
            'user_id' => $user->id,
            'title' => 'خانه',
            'full_name' => 'Customer',
            'phone' => '09120000000',
            'province' => 'تهران',
            'city' => 'تهران',
            'address' => 'Test address',
            'is_default' => true,
        ]);

        $cart = Cart::query()->create([
            'user_id' => $user->id,
            'session_id' => null,
        ]);

        $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => $product->price,
        ]);

        $order = app(CheckoutService::class)->createOrder(
            $user,
            $address
        );

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 2,
        ]);

        try {
            app(PaymentService::class)->start($order);
            $this->fail('Payment start should fail while the gateway is unavailable.');
        } catch (RuntimeException) {
            // Expected: the gateway is explicitly unavailable.
        }

        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'status' => 'failed',
        ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'cancelled',
            'payment_status' => 'failed',
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 4,
        ]);

        $this->assertDatabaseHas('inventory_movements', [
            'reference_type' => Order::class,
            'reference_id' => $order->id,
            'type' => 'return',
            'quantity' => 2,
        ]);

        try {
            app(PaymentService::class)->start(
                $order->fresh()
            );
        } catch (RuntimeException) {
            // A cancelled order must remain non-payable.
        }

        $this->assertSame(
            1,
            InventoryMovement::query()
                ->where('reference_type', Order::class)
                ->where('reference_id', $order->id)
                ->where('type', 'return')
                ->count()
        );

        $this->assertSame(
            1,
            Payment::query()
                ->where('order_id', $order->id)
                ->where('status', 'failed')
                ->count()
        );
    }
}
