<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $digital = Category::where('slug', 'digital')->firstOrFail();
        $home = Category::where('slug', 'home-appliances')->firstOrFail();
        $fashion = Category::where('slug', 'fashion')->firstOrFail();
        $beauty = Category::where('slug', 'beauty-health')->firstOrFail();
        $accessories = Category::where('slug', 'accessories')->firstOrFail();

        $products = [
            [
                'category_id' => $digital->id,
                'name' => 'هدفون بی‌سیم پرو',
                'slug' => 'wireless-headphone-pro',
                'sku' => 'DIG-1001',
                'brand' => 'Farzin',
                'short_description' => 'هدفون بی‌سیم با کیفیت صدای بالا',
                'description' => 'هدفون بی‌سیم مناسب استفاده روزمره، ورزش و تماس.',
                'price' => 2890000,
                'old_price' => 3290000,
                'discount' => 12,
                'stock' => 8,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'category_id' => $digital->id,
                'name' => 'ماوس بی‌سیم ارگونومیک',
                'slug' => 'ergonomic-wireless-mouse',
                'sku' => 'DIG-1002',
                'brand' => 'Logitech',
                'short_description' => 'ماوس راحت برای استفاده طولانی',
                'description' => 'ماوس بی‌سیم ارگونومیک با دقت بالا.',
                'price' => 1450000,
                'old_price' => 1650000,
                'discount' => 12,
                'stock' => 3,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'category_id' => $digital->id,
                'name' => 'کیبورد مکانیکی RGB',
                'slug' => 'mechanical-rgb-keyboard',
                'sku' => 'DIG-1003',
                'brand' => 'Redragon',
                'short_description' => 'کیبورد مکانیکی مخصوص گیمینگ',
                'description' => 'کیبورد مکانیکی RGB با کلیدهای نرم و سریع.',
                'price' => 3190000,
                'old_price' => null,
                'discount' => null,
                'stock' => 0,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'category_id' => $home->id,
                'name' => 'جارو شارژی مدل X1',
                'slug' => 'cordless-vacuum-x1',
                'sku' => 'HOM-2001',
                'brand' => 'Farzin',
                'short_description' => 'جارو شارژی سبک و قدرتمند',
                'description' => 'جارو شارژی با باتری قدرتمند و طراحی سبک.',
                'price' => 6490000,
                'old_price' => 7190000,
                'discount' => 10,
                'stock' => 12,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'category_id' => $home->id,
                'name' => 'کتری برقی استیل',
                'slug' => 'steel-electric-kettle',
                'sku' => 'HOM-2002',
                'brand' => 'Philips',
                'short_description' => 'کتری برقی سریع با بدنه استیل',
                'description' => 'کتری برقی مناسب استفاده روزمره با خاموشی خودکار.',
                'price' => 2390000,
                'old_price' => null,
                'discount' => null,
                'stock' => 16,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'category_id' => $fashion->id,
                'name' => 'هودی مردانه مشکی',
                'slug' => 'black-mens-hoodie',
                'sku' => 'FAS-3001',
                'brand' => 'Farzin',
                'short_description' => 'هودی روزمره با طراحی مینیمال',
                'description' => 'هودی راحت و مناسب استفاده روزمره.',
                'price' => 1890000,
                'old_price' => 2190000,
                'discount' => 14,
                'stock' => 22,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'category_id' => $fashion->id,
                'name' => 'کتانی اسپرت سفید',
                'slug' => 'white-sport-sneakers',
                'sku' => 'FAS-3002',
                'brand' => 'Nike',
                'short_description' => 'کتانی اسپرت مناسب استفاده روزانه',
                'description' => 'کتانی سبک و راحت برای استفاده شهری.',
                'price' => 4790000,
                'old_price' => null,
                'discount' => null,
                'stock' => 5,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'category_id' => $beauty->id,
                'name' => 'کرم مرطوب‌کننده روزانه',
                'slug' => 'daily-moisturizer',
                'sku' => 'BEA-4001',
                'brand' => 'Nivea',
                'short_description' => 'کرم مرطوب‌کننده مناسب استفاده روزانه',
                'description' => 'مرطوب‌کننده مناسب پوست خشک و معمولی.',
                'price' => 790000,
                'old_price' => 890000,
                'discount' => 11,
                'stock' => 14,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'category_id' => $beauty->id,
                'name' => 'عطر مردانه Classic',
                'slug' => 'classic-mens-perfume',
                'sku' => 'BEA-4002',
                'brand' => 'Farzin',
                'short_description' => 'عطر مردانه با رایحه کلاسیک',
                'description' => 'رایحه ماندگار مناسب استفاده روزانه و رسمی.',
                'price' => 3590000,
                'old_price' => 3990000,
                'discount' => 10,
                'stock' => 7,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'category_id' => $accessories->id,
                'name' => 'کیف دوشی چرمی',
                'slug' => 'leather-crossbody-bag',
                'sku' => 'ACC-5001',
                'brand' => 'Farzin',
                'short_description' => 'کیف دوشی چرمی با طراحی ساده',
                'description' => 'کیف دوشی مناسب استفاده روزمره و رسمی.',
                'price' => 2690000,
                'old_price' => null,
                'discount' => null,
                'stock' => 9,
                'is_active' => true,
                'is_featured' => false,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['sku' => $product['sku']],
                $product
            );
        }
    }
}
