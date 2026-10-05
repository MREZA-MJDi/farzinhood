<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_shop_only_filters_products_through_active_categories(): void
    {
        $active = Category::query()->create([
            'name' => 'فعال',
            'slug' => 'active-category',
            'is_active' => true,
        ]);

        $inactive = Category::query()->create([
            'name' => 'غیرفعال',
            'slug' => 'inactive-category',
            'is_active' => false,
        ]);

        Product::query()->create([
            'category_id' => $active->id,
            'name' => 'Visible Product',
            'slug' => 'visible-product',
            'sku' => 'VISIBLE-1',
            'price' => 100000,
            'stock' => 5,
            'is_active' => true,
        ]);

        Product::query()->create([
            'category_id' => $inactive->id,
            'name' => 'Hidden Product',
            'slug' => 'hidden-product',
            'sku' => 'HIDDEN-1',
            'price' => 100000,
            'stock' => 5,
            'is_active' => true,
        ]);

        $this->get(route('shop.index', [
            'category' => 'inactive-category',
        ]))
            ->assertOk()
            ->assertSee('محصولی پیدا نشد')
            ->assertDontSee('Hidden Product');
    }

    public function test_shop_per_page_is_bounded_by_request_validation(): void
    {
        $this->get(route('shop.index', ['per_page' => 999]))
            ->assertStatus(302);
    }

    public function test_live_search_returns_only_active_products_from_active_categories(): void
    {
        $activeCategory = Category::query()->create([
            'name' => 'هود',
            'slug' => 'live-search-hood',
            'is_active' => true,
        ]);

        $inactiveCategory = Category::query()->create([
            'name' => 'سینک غیرفعال',
            'slug' => 'live-search-inactive',
            'is_active' => false,
        ]);

        Product::query()->create([
            'category_id' => $activeCategory->id,
            'name' => 'هود مشکی مدرن',
            'slug' => 'live-search-black-hood',
            'sku' => 'LIVE-001',
            'brand' => 'فرزین',
            'price' => 12500000,
            'stock' => 4,
            'is_active' => true,
        ]);

        Product::query()->create([
            'category_id' => $inactiveCategory->id,
            'name' => 'هودی که نباید نمایش داده شود',
            'slug' => 'live-search-hidden',
            'sku' => 'LIVE-002',
            'brand' => 'فرزین',
            'price' => 9000000,
            'stock' => 4,
            'is_active' => true,
        ]);

        Product::query()->create([
            'category_id' => $activeCategory->id,
            'name' => 'کالای غیرفعال',
            'slug' => 'live-search-inactive-product',
            'sku' => 'LIVE-003',
            'brand' => 'فرزین',
            'price' => 8000000,
            'stock' => 4,
            'is_active' => false,
        ]);

        $this->getJson(route('shop.suggestions', [
            'search' => 'هود',
        ]))
            ->assertOk()
            ->assertJsonCount(1, 'items')
            ->assertJsonPath('items.0.name', 'هود مشکی مدرن')
            ->assertJsonPath('items.0.price', 12500000);
    }

}

