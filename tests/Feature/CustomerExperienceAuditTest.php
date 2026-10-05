<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
