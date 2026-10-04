<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminOperationsAuditTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_open_dashboard_and_review_index_without_eager_loading_failure(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('admin.reviews.index'))
            ->assertOk();
    }

    public function test_admin_can_create_and_update_category_with_image(): void
    {
        Storage::fake('public');

        $admin = $this->admin();

        $this->actingAs($admin)
            ->post(route('admin.categories.store'), [
                'name' => 'هود و سینک',
                'slug' => 'hood-sink',
                'description' => 'دسته تجهیزات آشپزخانه',
                'sort_order' => 1,
                'is_active' => 1,
            ])
            ->assertRedirect(route('admin.categories.index'));

        $category = Category::query()->where('slug', 'hood-sink')->firstOrFail();

        $this->actingAs($admin)
            ->put(route('admin.categories.update', $category), [
                'name' => 'هود و سینک آشپزخانه',
                'slug' => 'hood-sink',
                'description' => 'دسته اصلی',
                'sort_order' => 2,
                'is_active' => 1,
            ])
            ->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'هود و سینک آشپزخانه',
            'sort_order' => 2,
        ]);
    }

    public function test_admin_can_create_product_and_it_is_available_to_shop(): void
    {
        $admin = $this->admin();

        $category = Category::query()->create([
            'name' => 'هود و سینک',
            'slug' => 'hood-sink',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.products.store'), [
                'category_id' => $category->id,
                'name' => 'هود توکار تست',
                'slug' => 'test-built-in-hood',
                'sku' => 'TEST-HOOD-001',
                'brand' => 'Farzin',
                'short_description' => 'محصول تستی',
                'description' => 'توضیحات محصول',
                'price' => 15000000,
                'stock' => 7,
                'is_active' => 1,
                'is_featured' => 1,
            ])
            ->assertRedirect(route('admin.products.index'));

        $product = Product::query()->where('sku', 'TEST-HOOD-001')->firstOrFail();

        $this->actingAs($admin)
            ->get(route('shop.index'))
            ->assertOk()
            ->assertSee('هود توکار تست');
    }

    public function test_inventory_increase_rejects_negative_quantity_but_adjustment_accepts_it(): void
    {
        $admin = $this->admin();

        $category = Category::query()->create([
            'name' => 'هود',
            'slug' => 'hood',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'هود تست',
            'slug' => 'inventory-test-hood',
            'sku' => 'INV-HOOD-001',
            'price' => 1000000,
            'stock' => 5,
            'is_active' => true,
            'is_featured' => false,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.inventory.adjust', $product), [
                'type' => 'restock',
                'quantity' => -2,
            ])
            ->assertSessionHasErrors('quantity');

        $this->actingAs($admin)
            ->post(route('admin.inventory.adjust', $product), [
                'type' => 'adjustment',
                'quantity' => -2,
                'note' => 'کسری موجودی',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 3,
        ]);
    }
}
