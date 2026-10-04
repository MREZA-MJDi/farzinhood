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
}
