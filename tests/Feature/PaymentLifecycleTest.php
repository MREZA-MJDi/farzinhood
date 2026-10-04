<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\CheckoutService;
use App\Services\Payment\PaymentGatewayInterface;
use App\Services\Payment\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use Tests\TestCase;

class PaymentLifecycleTest extends TestCase
{
    use RefreshDatabase;

    private function buildOrder(): array
    {
        $user = User::query()->create([
            'name' => 'Payment Customer',
            'email' => 'payment-test@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $category = Category::query()->create([
            'name' => 'Payment Category',
            'slug' => 'payment-category',
            'is_active' => true,
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Payment Product',
            'slug' => 'payment-product',
            'sku' => 'PAYMENT-1',
            'price' => 1_000_000,
            'stock' => 2,
            'is_active' => true,
        ]);

        $cart = Cart::query()->create([
            'user_id' => $user->id,
            'session_id' => null,
        ]);

        $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => $product->price,
        ]);

        $address = Address::query()->create([
            'user_id' => $user->id,
            'title' => 'خانه',
            'full_name' => $user->name,
            'phone' => '09120000000',
            'country' => 'ایران',
            'province' => 'تهران',
            'city' => 'تهران',
            'postal_code' => '1234567890',
            'address' => 'آدرس تست',
            'is_default' => true,
        ]);

        $order = app(CheckoutService::class)->createOrder(
            $user,
            $address
        );

        return [$user, $product, $order];
    }

    public function test_failed_payment_commits_failure_state_and_releases_reserved_stock(): void
    {
        [$user, $product, $order] = $this->buildOrder();

        $gateway = new class implements PaymentGatewayInterface {
            public function purchase(\App\Models\Order $order): array
            {
                throw new RuntimeException('gateway unavailable');
            }

            public function verify(\App\Models\Order $order, string $authority): array
            {
                throw new RuntimeException('gateway unavailable');
            }

            public function refund(\App\Models\Order $order, int $amount): array
            {
                throw new RuntimeException('gateway unavailable');
            }
        };

        $this->app->instance(PaymentGatewayInterface::class, $gateway);

        $this->expectException(RuntimeException::class);

        try {
            app(PaymentService::class)->start($order);
        } finally {
            $this->assertDatabaseHas('orders', [
                'id' => $order->id,
                'status' => 'cancelled',
                'payment_status' => 'failed',
            ]);

            $this->assertDatabaseHas('payments', [
                'order_id' => $order->id,
                'status' => 'failed',
            ]);

            $this->assertDatabaseHas('products', [
                'id' => $product->id,
                'stock' => 2,
            ]);
        }
    }

    public function test_successful_payment_uses_supported_payment_status_and_keeps_reserved_stock_consumed(): void
    {
        [$user, $product, $order] = $this->buildOrder();

        $gateway = new class implements PaymentGatewayInterface {
            public function purchase(\App\Models\Order $order): array
            {
                return [
                    'authority' => 'AUTH-TEST-1',
                    'redirect_url' => 'https://gateway.test/pay/AUTH-TEST-1',
                ];
            }

            public function verify(\App\Models\Order $order, string $authority): array
            {
                return [
                    'success' => true,
                    'tracking_code' => 'TRACK-TEST-1',
                    'message' => 'ok',
                ];
            }

            public function refund(\App\Models\Order $order, int $amount): array
            {
                return [
                    'success' => true,
                    'tracking_code' => 'REFUND-TEST-1',
                ];
            }
        };

        $this->app->instance(PaymentGatewayInterface::class, $gateway);

        $payment = app(PaymentService::class)->start($order);

        $this->assertSame('pending', $payment->status);
        $this->assertSame(
            'https://gateway.test/pay/AUTH-TEST-1',
            data_get($payment->gateway_response, 'redirect_url')
        );

        $verified = app(PaymentService::class)->verify(
            $order->fresh(),
            'AUTH-TEST-1'
        );

        $this->assertSame('successful', $verified->status);
        $this->assertTrue($verified->isSuccessful());

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'processing',
            'payment_status' => 'paid',
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 1,
        ]);
    }
}
