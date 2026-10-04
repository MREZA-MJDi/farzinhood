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
}
