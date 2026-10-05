<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationFlowAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_login_merges_the_guest_cart_before_rotating_the_session(): void
    {
        $category = Category::query()->create([
            'name' => 'هود',
            'slug' => 'auth-cart-hood',
            'is_active' => true,
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'هود تست',
            'slug' => 'auth-cart-product',
            'sku' => 'AUTH-CART-001',
            'price' => 1_000_000,
            'stock' => 10,
            'is_active' => true,
        ]);

        $customer = User::query()->create([
            'name' => 'Customer',
            'email' => 'auth-cart@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $this->get(route('shop.index'));

        $guestSessionId = session()->getId();

        $guestCart = Cart::query()->create([
            'user_id' => null,
            'session_id' => $guestSessionId,
        ]);

        $guestCart->items()->create([
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 900_000,
        ]);

        $this->post(route('login.store'), [
            'email' => $customer->email,
            'password' => 'password',
        ])->assertRedirect(route('home'));

        $userCart = Cart::query()
            ->where('user_id', $customer->id)
            ->firstOrFail();

        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $userCart->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 1_000_000,
        ]);

        $this->assertDatabaseMissing('carts', [
            'id' => $guestCart->id,
        ]);
    }

    public function test_admin_login_does_not_receive_customer_cart_merge_behavior(): void
    {
        $admin = User::query()->create([
            'name' => 'Admin',
            'email' => 'auth-admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->post(route('login.store'), [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertDatabaseMissing('carts', [
            'user_id' => $admin->id,
        ]);
    }
}
