<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\CheckoutService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use Tests\TestCase;

class CheckoutSafetyTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_rechecks_stock_inside_a_transaction_and_does_not_create_an_order_when_stock_is_insufficient(): void
    {
        $user = User::query()->create([
            'name' => 'Test Customer',
            'email' => 'checkout-safety@example.com',
            'password' => Hash::make('password'),
        ]);

        $category = Category::query()->create([
            'name' => 'هود',
            'slug' => 'checkout-hood',
            'is_active' => true,
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Race Safe Product',
            'slug' => 'race-safe-product',
            'sku' => 'RACE-SAFE',
            'price' => 1_000_000,
            'stock' => 1,
            'is_active' => true,
        ]);

        $address = Address::query()->create([
            'user_id' => $user->id,
            'title' => 'خانه',
            'full_name' => 'Test Customer',
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

        $this->expectException(RuntimeException::class);

        try {
            app(CheckoutService::class)->createOrder($user, $address);
        } finally {
            $this->assertDatabaseMissing('orders', [
                'user_id' => $user->id,
            ]);

            $this->assertDatabaseHas('products', [
                'id' => $product->id,
                'stock' => 1,
            ]);
        }
    }



    public function test_repeated_checkout_submission_cannot_create_a_second_order_from_a_cleared_cart(): void
    {
        $user = User::query()->create([
            'name' => 'Checkout Retry Customer',
            'email' => 'checkout-retry@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $category = Category::query()->create([
            'name' => 'Retry Category',
            'slug' => 'checkout-retry-category',
            'is_active' => true,
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Retry Product',
            'slug' => 'checkout-retry-product',
            'sku' => 'CHECKOUT-RETRY-1',
            'price' => 1_000_000,
            'stock' => 5,
            'is_active' => true,
        ]);

        $address = Address::query()->create([
            'user_id' => $user->id,
            'title' => 'Home',
            'full_name' => $user->name,
            'phone' => '09120000000',
            'country' => 'Iran',
            'province' => 'Tehran',
            'city' => 'Tehran',
            'address' => 'Retry test address',
            'is_default' => true,
        ]);

        $cart = Cart::query()->create([
            'user_id' => $user->id,
            'session_id' => null,
        ]);

        $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 1,
        ]);

        $service = app(CheckoutService::class);
        $firstOrder = $service->createOrder($user, $address);

        try {
            $service->createOrder($user, $address);
            $this->fail('A second checkout must not create an order after the cart was cleared.');
        } catch (RuntimeException $exception) {
            $this->assertSame('سبد خرید شما خالی است.', $exception->getMessage());
        }

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseHas('orders', [
            'id' => $firstOrder->id,
            'user_id' => $user->id,
        ]);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 3,
        ]);
        $this->assertDatabaseCount('order_items', 1);
        $this->assertSame(0, $cart->items()->count());
    }
}
