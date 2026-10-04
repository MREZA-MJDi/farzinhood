<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_is_authorized_and_loads_operational_data(): void
    {
        $admin = User::query()->create([
            'name' => 'Admin',
            'email' => 'dashboard-admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('داشبورد')
            ->assertSee('فروش امروز')
            ->assertSee('وضعیت سفارش‌ها');
    }

    public function test_customer_cannot_access_admin_dashboard(): void
    {
        $customer = User::query()->create([
            'name' => 'Customer',
            'email' => 'dashboard-customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $this->actingAs($customer)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_chart_endpoint_is_bounded_to_supported_ranges(): void
    {
        $admin = User::query()->create([
            'name' => 'Admin',
            'email' => 'dashboard-chart@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        foreach (['daily', 'monthly', 'yearly'] as $range) {
            $this->actingAs($admin)
                ->getJson(route('admin.dashboard.chart', ['range' => $range]))
                ->assertOk()
                ->assertJsonStructure(['labels', 'data']);
        }

        $this->actingAs($admin)
            ->getJson(route('admin.dashboard.chart', ['range' => 'unbounded']))
            ->assertStatus(422);
    }

    public function test_product_edit_keeps_its_current_inactive_category_selectable(): void
    {
        $admin = User::query()->create([
            'name' => 'Admin',
            'email' => 'product-edit-admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $category = Category::query()->create([
            'name' => 'دسته غیرفعال',
            'slug' => 'inactive-category',
            'is_active' => false,
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Product With Inactive Category',
            'slug' => 'product-with-inactive-category',
            'sku' => 'INACTIVE-CAT-1',
            'price' => 100000,
            'stock' => 5,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.products.edit', $product))
            ->assertOk()
            ->assertSee('دسته غیرفعال');
    }
}
