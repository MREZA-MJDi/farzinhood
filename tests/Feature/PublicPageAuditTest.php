<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPageAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_category_page_renders_its_public_view(): void
    {
        $category = Category::query()->create([
            'name' => 'هود و سینک',
            'slug' => 'hood-sink',
            'is_active' => true,
        ]);

        $this->get(route('categories.show', $category))
            ->assertOk()
            ->assertSee('هود و سینک');
    }

    public function test_product_page_is_hidden_when_its_category_is_inactive(): void
    {
        $category = Category::query()->create([
            'name' => 'غیرفعال',
            'slug' => 'inactive-product-category',
            'is_active' => false,
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Hidden Product',
            'slug' => 'hidden-product-page',
            'sku' => 'HIDDEN-PAGE-1',
            'price' => 100000,
            'stock' => 2,
            'is_active' => true,
        ]);

        $this->get(route('products.show', $product))
            ->assertNotFound();
    }

    public function test_product_page_renders_for_active_product_and_category(): void
    {
        $category = Category::query()->create([
            'name' => 'سینک',
            'slug' => 'sink-page',
            'is_active' => true,
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'سینک تست',
            'slug' => 'sink-page-product',
            'sku' => 'SINK-PAGE-1',
            'price' => 100000,
            'stock' => 2,
            'is_active' => true,
        ]);

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('سینک تست');
    }
}
