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

    public function test_home_hero_uses_the_four_latest_active_products_with_primary_images(): void
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

        $response = $this->get(route('home'))->assertOk();
        $html = $response->getContent();
        $heroStart = strpos($html, 'data-farzin-hero');
        $sectionStart = strpos($html, 'id="home-categories"', $heroStart);

        $this->assertNotFalse($heroStart);
        $this->assertNotFalse($sectionStart);
        $heroHtml = substr($html, $heroStart, $sectionStart - $heroStart);

        foreach (range(9, 12) as $number) {
            $this->assertStringContainsString('Hero Product '.$number, $heroHtml);
        }
        $this->assertStringNotContainsString('Hero Product 1</strong>', $heroHtml);
        $this->assertStringNotContainsString('Hero Product 8</strong>', $heroHtml);
    }

    public function test_inactive_or_missing_primary_image_products_do_not_enter_the_home_hero(): void
    {
        $category = Category::query()->create([
            'name' => 'سینک',
            'slug' => 'sink',
            'is_active' => true,
        ]);

        Product::query()->create([
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

        $response = $this->get(route('home'))->assertOk();
        $html = $response->getContent();
        $heroStart = strpos($html, 'data-farzin-hero');
        $sectionStart = strpos($html, 'id="home-categories"', $heroStart);

        $this->assertNotFalse($heroStart);
        $this->assertNotFalse($sectionStart);
        $heroHtml = substr($html, $heroStart, $sectionStart - $heroStart);

        $this->assertStringContainsString('Valid Hero Product', $heroHtml);
        $this->assertStringNotContainsString('Inactive Hero Product', $heroHtml);
        $this->assertStringNotContainsString('No Image Hero Product', $heroHtml);
    }
}
