<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationAuthorizationAuditTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role = 'customer', bool $active = true, ?string $email = null): User
    {
        static $counter = 0;
        $counter++;

        return User::query()->create([
            'name' => ucfirst($role) . ' Test User',
            'email' => $email ?? $role . '-' . $counter . '@example.test',
            'password' => Hash::make('Correct-Horse-123!'),
            'role' => $role,
            'is_active' => $active,
        ]);
    }

    private function category(array $overrides = []): Category
    {
        static $counter = 0;
        $counter++;

        return Category::query()->create(array_merge([
            'name' => 'Category ' . $counter,
            'slug' => 'auth-category-' . $counter,
            'description' => 'Category for authorization tests',
            'sort_order' => $counter,
            'is_active' => true,
        ], $overrides));
    }

    private function address(User $user, array $overrides = []): Address
    {
        static $counter = 0;
        $counter++;

        return Address::query()->create(array_merge([
            'user_id' => $user->id,
            'title' => 'Home ' . $counter,
            'full_name' => 'Test Customer',
            'phone' => '09120000000',
            'country' => 'Iran',
            'province' => 'Tehran',
            'city' => 'Tehran',
            'postal_code' => '1234567890',
            'address' => 'Test address ' . $counter,
            'is_default' => false,
        ], $overrides));
    }

    private function order(User $user, array $overrides = []): Order
    {
        static $counter = 0;
        $counter++;

        return Order::query()->create(array_merge([
            'user_id' => $user->id,
            'order_number' => 'AUTH-ORDER-' . $counter,
            'status' => 'pending',
            'payment_status' => 'paid',
            'subtotal' => 100000,
            'discount' => 0,
            'shipping_cost' => 0,
            'total' => 100000,
            'shipping_full_name' => 'Test Customer',
            'shipping_phone' => '09120000000',
            'shipping_country' => 'Iran',
            'shipping_province' => 'Tehran',
            'shipping_city' => 'Tehran',
            'shipping_postal_code' => '1234567890',
            'shipping_address' => 'Test shipping address',
            'notes' => null,
        ], $overrides));
    }

    private function addressPayload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'خانه',
            'full_name' => 'محمد رضایی',
            'phone' => '09121112233',
            'country' => 'Iran',
            'province' => 'Tehran',
            'city' => 'Tehran',
            'postal_code' => '1234567890',
            'address' => 'Tehran, Example Street, No. 10',
            'is_default' => 1,
        ], $overrides);
    }

    public function test_guest_is_redirected_from_customer_and_admin_workspaces(): void
    {
        $this->get(route('customer.dashboard'))->assertRedirect(route('login'));
        $this->get(route('customer.addresses.index'))->assertRedirect(route('login'));
        $this->get(route('customer.settings.index'))->assertRedirect(route('login'));
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('admin.settings.index'))->assertRedirect(route('login'));
        $this->get(route('admin.seo.index'))->assertRedirect(route('login'));

        $this->put(route('admin.settings.update'), [
            'site_name' => 'Must not save',
        ])->assertRedirect(route('login'));

        $this->post(route('customer.addresses.store'), $this->addressPayload())
            ->assertRedirect(route('login'));

        $this->assertDatabaseMissing('site_settings', ['key' => 'site_name', 'value' => 'Must not save']);
        $this->assertDatabaseCount('addresses', 0);
    }

    public function test_admin_routes_are_blocked_for_customers_including_mutations(): void
    {
        $customer = $this->user('customer', true, 'ordinary-customer@example.test');
        $this->actingAs($customer);

        $adminRoutes = [
            'admin.dashboard',
            'admin.dashboard.chart',
            'admin.products.index',
            'admin.products.create',
            'admin.categories.index',
            'admin.categories.create',
            'admin.inventory.index',
            'admin.orders.index',
            'admin.reviews.index',
            'admin.blog-categories.index',
            'admin.posts.index',
            'admin.customers.index',
            'admin.contact-messages.index',
            'admin.newsletter.index',
            'admin.settings.index',
            'admin.seo.index',
        ];

        foreach ($adminRoutes as $routeName) {
            $this->get(route($routeName))->assertForbidden();
        }

        $this->put(route('admin.settings.update'), [
            'site_name' => 'Attempted privilege escalation',
        ])->assertForbidden();

        $this->post(route('admin.products.store'), [
            'name' => 'Unauthorized product',
            'slug' => 'unauthorized-product',
            'price' => 10000,
        ])->assertForbidden();

        $this->assertDatabaseMissing('products', ['slug' => 'unauthorized-product']);
        $this->assertDatabaseMissing('site_settings', ['key' => 'site_name', 'value' => 'Attempted privilege escalation']);
    }

    public function test_customer_routes_are_blocked_for_admin_users(): void
    {
        $admin = $this->user('admin', true, 'active-admin@example.test');
        $this->actingAs($admin);

        foreach ([
            'customer.dashboard',
            'customer.cart.index',
            'customer.wishlist.index',
            'customer.checkout.index',
            'customer.orders.index',
            'customer.addresses.index',
            'customer.settings.index',
        ] as $routeName) {
            $this->get(route($routeName))->assertForbidden();
        }

        $this->post(route('customer.addresses.store'), $this->addressPayload())
            ->assertForbidden();

        $this->assertDatabaseCount('addresses', 0);
    }

    public function test_inactive_users_cannot_continue_using_either_workspace_after_deactivation(): void
    {
        $customer = $this->user('customer', false, 'inactive-customer@example.test');
        $admin = $this->user('admin', false, 'inactive-admin@example.test');

        $this->actingAs($customer)
            ->get(route('customer.dashboard'))
            ->assertForbidden();

        auth()->logout();

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertForbidden();

        auth()->logout();

        $this->post(route('login.store'), [
            'email' => $customer->email,
            'password' => 'Correct-Horse-123!',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_admin_and_customer_login_are_redirected_to_the_correct_workspace(): void
    {
        $customer = $this->user('customer', true, 'login-customer@example.test');
        $admin = $this->user('admin', true, 'login-admin@example.test');

        $this->post(route('login.store'), [
            'email' => $customer->email,
            'password' => 'Correct-Horse-123!',
        ])->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($customer);

        auth()->logout();

        $this->post(route('login.store'), [
            'email' => $admin->email,
            'password' => 'Correct-Horse-123!',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_registration_cannot_choose_an_admin_role_or_inactive_state(): void
    {
        $this->post(route('register.store'), [
            'name' => 'New Customer',
            'email' => 'role-escalation@example.test',
            'password' => 'Correct-Horse-123!',
            'password_confirmation' => 'Correct-Horse-123!',
            'role' => 'admin',
            'is_active' => 0,
        ])->assertRedirect(route('home'));

        $registered = User::query()->where('email', 'role-escalation@example.test')->firstOrFail();

        $this->assertSame('customer', $registered->role);
        $this->assertTrue($registered->is_active);
        $this->assertAuthenticatedAs($registered);
    }

    public function test_profile_update_cannot_change_role_or_active_state_and_reloads_values(): void
    {
        $customer = $this->user('customer', true, 'profile-before@example.test');
        $customer->forceFill(['email_verified_at' => now()])->save();

        $this->actingAs($customer)
            ->put(route('customer.settings.update'), [
                'name' => 'Updated Customer',
                'email' => 'profile-after@example.test',
                'role' => 'admin',
                'is_active' => false,
            ])
            ->assertRedirect(route('customer.settings.index'));

        $customer->refresh();

        $this->assertSame('Updated Customer', $customer->name);
        $this->assertSame('profile-after@example.test', $customer->email);
        $this->assertSame('customer', $customer->role);
        $this->assertTrue($customer->is_active);
        $this->assertNull($customer->email_verified_at);

        $this->get(route('customer.settings.index'))
            ->assertOk()
            ->assertSee('Updated Customer')
            ->assertSee('profile-after@example.test');
    }

    public function test_profile_update_rejects_duplicate_email_without_changing_the_account(): void
    {
        $customer = $this->user('customer', true, 'profile-owner@example.test');
        $this->user('customer', true, 'email-already-used@example.test');

        $this->actingAs($customer)
            ->from(route('customer.settings.index'))
            ->put(route('customer.settings.update'), [
                'name' => 'Attempted Update',
                'email' => 'email-already-used@example.test',
            ])
            ->assertRedirect(route('customer.settings.index'))
            ->assertSessionHasErrors('email');

        $this->assertSame('profile-owner@example.test', $customer->fresh()->email);
    }

    public function test_customer_can_manage_only_their_own_addresses_and_default_is_kept_consistent(): void
    {
        $customer = $this->user('customer', true, 'address-owner@example.test');
        $otherCustomer = $this->user('customer', true, 'other-address-owner@example.test');

        $first = $this->address($customer, ['title' => 'First address', 'is_default' => true]);
        $second = $this->address($customer, ['title' => 'Second address', 'is_default' => false]);
        $foreign = $this->address($otherCustomer, ['title' => 'Private address', 'is_default' => true]);

        $this->actingAs($customer)
            ->get(route('customer.addresses.index'))
            ->assertOk()
            ->assertSee('First address')
            ->assertSee(route('customer.addresses.edit', $first), false);

        $this->get(route('customer.addresses.edit', $first))
            ->assertOk()
            ->assertSee('ویرایش آدرس')
            ->assertSee('name="full_name"', false);

        $this->get(route('customer.addresses.edit', $foreign))->assertNotFound();

        $this->put(route('customer.addresses.update', $foreign), $this->addressPayload([
            'title' => 'Stolen and changed',
            'user_id' => $customer->id,
        ]))->assertNotFound();

        $this->delete(route('customer.addresses.destroy', $foreign))->assertNotFound();
        $this->patch(route('customer.addresses.default', $foreign))->assertNotFound();

        $this->assertSame('Private address', $foreign->fresh()->title);
        $this->assertSame($otherCustomer->id, $foreign->fresh()->user_id);

        $this->patch(route('customer.addresses.default', $second))
            ->assertRedirect(route('customer.addresses.index'));

        $this->assertDatabaseHas('addresses', ['id' => $first->id, 'is_default' => false]);
        $this->assertDatabaseHas('addresses', ['id' => $second->id, 'is_default' => true]);

        $this->delete(route('customer.addresses.destroy', $second))
            ->assertRedirect(route('customer.addresses.index'));

        $this->assertDatabaseMissing('addresses', ['id' => $second->id]);
        $this->assertDatabaseHas('addresses', ['id' => $first->id, 'is_default' => true]);
    }

    public function test_customer_can_create_and_update_an_address_without_mass_assigning_user_id(): void
    {
        $customer = $this->user('customer', true, 'address-crud@example.test');
        $otherCustomer = $this->user('customer', true, 'address-other@example.test');

        $this->actingAs($customer)
            ->post(route('customer.addresses.store'), $this->addressPayload([
                'title' => 'Created address',
                'user_id' => $otherCustomer->id,
            ]))
            ->assertRedirect(route('customer.addresses.index'));

        $created = Address::query()->where('title', 'Created address')->firstOrFail();
        $this->assertSame($customer->id, $created->user_id);
        $this->assertTrue($created->is_default);

        $this->put(route('customer.addresses.update', $created), $this->addressPayload([
            'title' => 'Updated address',
            'is_default' => 0,
            'user_id' => $otherCustomer->id,
        ]))->assertRedirect(route('customer.addresses.index'));

        $created->refresh();
        $this->assertSame('Updated address', $created->title);
        $this->assertSame($customer->id, $created->user_id);
        $this->assertTrue($created->is_default, 'The only saved address must remain a default address.');
    }

    public function test_checkout_cannot_use_an_address_owned_by_another_customer(): void
    {
        $customer = $this->user('customer', true, 'checkout-customer@example.test');
        $otherCustomer = $this->user('customer', true, 'checkout-owner@example.test');
        $foreignAddress = $this->address($otherCustomer, ['is_default' => true]);

        $this->actingAs($customer)
            ->post(route('customer.checkout.store'), [
                'address_id' => $foreignAddress->id,
                'payment_method' => 'gateway',
                'notes' => 'Try another customer address',
            ])
            ->assertNotFound();

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_customer_cannot_view_another_customers_order(): void
    {
        $customer = $this->user('customer', true, 'order-viewer@example.test');
        $otherCustomer = $this->user('customer', true, 'order-owner@example.test');
        $order = $this->order($otherCustomer);

        $this->actingAs($customer)
            ->get(route('customer.orders.show', $order))
            ->assertForbidden();

        $this->actingAs($otherCustomer)
            ->get(route('customer.orders.show', $order))
            ->assertOk()
            ->assertSee($order->order_number);
    }
}
