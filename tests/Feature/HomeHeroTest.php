<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeHeroTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_hero_only_renders_the_ten_latest_active_products_with_primary_images(): void
    {
        $category = Category::query()->create([
            'name' => 'هود',
            'slug' => 'hood',
            'is_active' => true,
        ]);

        foreach (range(1, 12) as $number) {
            $product = Product::query()->create([
                'category_id' => $category->id,
                'name' => 'Hero Product '.$number,
                'slug' => 'hero-product-'.$number,
                'sku' => 'HERO-'.$number,
                'price' => 100000 + $number,
                'stock' => 10,
                'is_active' => true,
                'is_featured' => false,
            ]);

            ProductImage::query()->create([
                'product_id' => $product->id,
                'image' => 'products/hero-'.$number.'.jpg',
                'alt' => $product->name,
                'sort_order' => 0,
                'is_primary' => true,
            ]);
        }

        $response = $this->get(route('home'));

        $response->assertOk();

        for ($number = 3; $number <= 12; $number++) {
            $response->assertSee('Hero Product '.$number);
        }

        $response->assertDontSee('Hero Product 1');
        $response->assertDontSee('Hero Product 2');
    }

    public function test_inactive_or_missing_primary_image_products_do_not_enter_the_home_hero(): void
    {
        $category = Category::query()->create([
            'name' => 'سینک',
            'slug' => 'sink',
            'is_active' => true,
        ]);

        $inactive = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Inactive Hero Product',
            'slug' => 'inactive-hero-product',
            'sku' => 'INACTIVE-HERO',
            'price' => 100000,
            'stock' => 10,
            'is_active' => false,
        ]);

        Product::query()->create([
            'category_id' => $category->id,
            'name' => 'No Image Hero Product',
            'slug' => 'no-image-hero-product',
            'sku' => 'NO-IMAGE-HERO',
            'price' => 100000,
            'stock' => 10,
            'is_active' => true,
        ]);

        $valid = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Valid Hero Product',
            'slug' => 'valid-hero-product',
            'sku' => 'VALID-HERO',
            'price' => 100000,
            'stock' => 10,
            'is_active' => true,
        ]);

        ProductImage::query()->create([
            'product_id' => $valid->id,
            'image' => 'products/valid.jpg',
            'alt' => $valid->name,
            'sort_order' => 0,
            'is_primary' => true,
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();

        $html = $response->getContent();
        $heroStart = strpos($html, 'data-home-hero');
        $trustStart = strpos($html, 'TRUST STRIP', $heroStart);

        $this->assertNotFalse($heroStart);
        $this->assertNotFalse($trustStart);

        $heroHtml = substr($html, $heroStart, $trustStart - $heroStart);

        $this->assertStringContainsString('Valid Hero Product', $heroHtml);
        $this->assertStringNotContainsString('Inactive Hero Product', $heroHtml);
        $this->assertStringNotContainsString('No Image Hero Product', $heroHtml);
    }
}
