<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductImageSeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CustomerExperienceAuditTest extends TestCase
{
    use RefreshDatabase;

    private function customer(): User
    {
        return User::factory()->create([
            'role' => 'customer',
            'is_active' => true,
        ]);
    }

    public function test_customer_account_pages_render_from_real_routes(): void
    {
        $customer = $this->customer();

        $this->actingAs($customer);

        $routes = [
            'customer.dashboard',
            'customer.cart.index',
            'customer.wishlist.index',
            'customer.orders.index',
            'customer.addresses.index',
            'customer.settings.index',
        ];

        foreach ($routes as $route) {
            $this->get(route($route))
                ->assertOk();
        }
    }

    public function test_customer_account_pages_are_not_open_to_admins(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->actingAs($admin);

        $this->get(route('customer.dashboard'))->assertForbidden();
        $this->get(route('customer.cart.index'))->assertForbidden();
        $this->get(route('customer.wishlist.index'))->assertForbidden();
        $this->get(route('customer.orders.index'))->assertForbidden();
        $this->get(route('customer.addresses.index'))->assertForbidden();
        $this->get(route('customer.settings.index'))->assertForbidden();
    }

    public function test_customer_can_create_update_and_remove_an_address(): void
    {
        $customer = $this->customer();

        $this->actingAs($customer);

        $this->post(route('customer.addresses.store'), [
            'title' => 'خانه',
            'full_name' => 'مشتری تست',
            'phone' => '09120000000',
            'country' => 'ایران',
            'province' => 'تهران',
            'city' => 'تهران',
            'postal_code' => '1234567890',
            'address' => 'خیابان تست، کوچه تست، پلاک ۱۰',
            'is_default' => true,
        ])->assertSessionHasNoErrors();

        $address = $customer->addresses()->firstOrFail();

        $this->assertTrue($address->is_default);

        $this->put(route('customer.addresses.update', $address), [
            'title' => 'محل کار',
            'full_name' => 'مشتری تست',
            'phone' => '09121111111',
            'country' => 'ایران',
            'province' => 'البرز',
            'city' => 'کرج',
            'postal_code' => '2234567890',
            'address' => 'آدرس جدید تست',
            'is_default' => true,
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'title' => 'محل کار',
            'city' => 'کرج',
            'phone' => '09121111111',
            'is_default' => true,
        ]);

        $this->delete(route('customer.addresses.destroy', $address))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('addresses', [
            'id' => $address->id,
        ]);
    }
    public function test_customer_can_open_order_details_with_real_order_snapshot(): void
    {
        $customer = $this->customer();

        $order = Order::query()->create([
            'user_id' => $customer->id,
            'order_number' => 'FARZIN-TEST-0001',
            'status' => 'processing',
            'payment_status' => 'paid',
            'subtotal' => 8900000,
            'discount' => 500000,
            'shipping_cost' => 0,
            'total' => 8400000,
            'shipping_full_name' => 'مشتری تست',
            'shipping_phone' => '09120000000',
            'shipping_country' => 'ایران',
            'shipping_province' => 'تهران',
            'shipping_city' => 'تهران',
            'shipping_postal_code' => '1234567890',
            'shipping_address' => 'خیابان تست، پلاک ۱۰',
        ]);

        $item = OrderItem::query()->create([
            'order_id' => $order->id,
            'product_id' => null,
            'product_name' => 'سینک تست',
            'product_sku' => 'SK-TEST-01',
            'unit_price' => 4200000,
            'quantity' => 2,
            'total' => 8400000,
        ]);

        OrderStatusHistory::query()->create([
            'order_id' => $order->id,
            'changed_by' => $customer->id,
            'from_status' => 'pending',
            'to_status' => 'processing',
            'note' => 'سفارش در حال پردازش است.',
        ]);

        $this->actingAs($customer)
            ->get(route('customer.orders.show', $order))
            ->assertOk()
            ->assertSee('FARZIN-TEST-0001')
            ->assertSee('سینک تست')
            ->assertSee('سفارش در حال پردازش است.');
    }

    public function test_farzin_seeders_create_a_consistent_image_ready_catalog(): void
    {
        Artisan::call('db:seed', [
            '--class' => CategorySeeder::class,
        ]);
        Artisan::call('db:seed', [
            '--class' => ProductSeeder::class,
        ]);
        Artisan::call('db:seed', [
            '--class' => ProductImageSeeder::class,
        ]);

        $this->assertDatabaseCount('categories', 2);
        $this->assertDatabaseCount('products', 4);
        $this->assertDatabaseCount('product_images', 8);

        $this->assertDatabaseHas('products', [
            'slug' => 'hood-arya',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('products', [
            'slug' => 'sink-nova',
            'is_active' => true,
        ]);
    }

}
