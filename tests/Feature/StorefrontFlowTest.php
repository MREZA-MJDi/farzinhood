<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StorefrontFlowTest extends TestCase
{
    use RefreshDatabase;

    private function customer(): User
    {
        return User::query()->create([
            'name' => 'مشتری تست',
            'email' => 'storefront-test@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'is_active' => true,
        ]);
    }

    private function product(): Product
    {
        $category = Category::query()->create([
            'name' => 'کالای تست',
            'slug' => 'test-catalog',
            'is_active' => true,
        ]);

        return Product::query()->create([
            'category_id' => $category->id,
            'name' => 'محصول تست فروشگاه',
            'slug' => 'storefront-test-product',
            'sku' => 'STORE-TEST-1',
            'price' => 1_250_000,
            'stock' => 8,
            'is_active' => true,
            'is_featured' => false,
        ]);
    }

    public function test_guest_is_sent_to_login_and_login_returns_to_the_original_customer_route(): void
    {
        $customer = $this->customer();

        $this->get(route('customer.dashboard'))
            ->assertRedirect(route('login'));

        $this->post(route('login.store'), [
            'email' => $customer->email,
            'password' => 'password',
        ])->assertRedirect(route('customer.dashboard'));
    }

    public function test_product_page_renders_gallery_purchase_and_structured_data_for_active_catalog_items(): void
    {
        $product = $this->product();

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('محصول تست فروشگاه')
            ->assertSee('FARZIN / PRODUCT')
            ->assertSee('application/ld+json', false)
            ->assertSee('افزودن به سبد خرید');
    }

    public function test_customer_can_open_cart_and_checkout_using_the_same_route_family(): void
    {
        $customer = $this->customer();
        $product = $this->product();

        $cart = Cart::query()->create([
            'user_id' => $customer->id,
            'session_id' => null,
        ]);

        $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => $product->price,
        ]);

        Address::query()->create([
            'user_id' => $customer->id,
            'title' => 'خانه',
            'full_name' => $customer->name,
            'phone' => '09120000000',
            'country' => 'ایران',
            'province' => 'تهران',
            'city' => 'تهران',
            'postal_code' => '1234567890',
            'address' => 'آدرس تست',
            'is_default' => true,
        ]);

        $this->actingAs($customer)
            ->get(route('customer.cart.index'))
            ->assertOk()
            ->assertSee('سبد خرید شما');

        $this->actingAs($customer)
            ->get(route('customer.checkout.index'))
            ->assertOk()
            ->assertSee('تکمیل سفارش');
    }

    public function test_customer_can_use_account_pages_without_view_path_mismatches(): void
    {
        $customer = $this->customer();

        $this->actingAs($customer)
            ->get(route('customer.dashboard'))
            ->assertOk()
            ->assertSee('MY ACCOUNT');

        $this->actingAs($customer)
            ->get(route('customer.wishlist.index'))
            ->assertOk()
            ->assertSee('FARZIN / WISHLIST');

        $this->actingAs($customer)
            ->get(route('customer.addresses.index'))
            ->assertOk()
            ->assertSee('FARZIN / ADDRESSES');

        $this->actingAs($customer)
            ->get(route('customer.settings.index'))
            ->assertOk()
            ->assertSee('FARZIN / SETTINGS');
    }

    public function test_logout_invalidates_the_session_and_returns_to_home(): void
    {
        $customer = $this->customer();

        $this->actingAs($customer)
            ->post(route('logout'))
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }
}
