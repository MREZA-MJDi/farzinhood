<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $hood = Category::query()
            ->where('slug', 'hood')
            ->firstOrFail();

        $sink = Category::query()
            ->where('slug', 'sink')
            ->firstOrFail();

        $products = [
            [
                'category_id' => $hood->id,
                'name' => 'هود مورب فرزین مدل آریا',
                'slug' => 'hood-arya',
                'sku' => 'HD-1001',
                'brand' => 'Farzin',
                'short_description' => 'هود مورب با طراحی مینیمال و مکش مناسب آشپزخانه.',
                'description' => 'هود مورب فرزین مدل آریا با ظاهر ساده و مدرن، مناسب آشپزخانه‌های امروزی.',
                'price' => 12800000,
                'old_price' => 14200000,
                'discount' => 10,
                'stock' => 8,
                'rating' => 4.7,
                'review_count' => 18,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'category_id' => $hood->id,
                'name' => 'هود مخفی فرزین مدل نیکا',
                'slug' => 'hood-nika',
                'sku' => 'HD-1002',
                'brand' => 'Farzin',
                'short_description' => 'هود مخفی برای طراحی یکپارچه و خلوت کابینت.',
                'description' => 'هود مخفی فرزین مدل نیکا با فرم یکپارچه و مناسب برای آشپزخانه‌های مینیمال.',
                'price' => 10900000,
                'old_price' => null,
                'discount' => null,
                'stock' => 4,
                'rating' => 4.5,
                'review_count' => 11,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'category_id' => $sink->id,
                'name' => 'سینک توکار فرزین مدل کلاسیک',
                'slug' => 'sink-classic',
                'sku' => 'SK-2001',
                'brand' => 'Farzin',
                'short_description' => 'سینک توکار با فرم تمیز و مناسب استفاده روزمره.',
                'description' => 'سینک توکار فرزین مدل کلاسیک با طراحی متعادل و مناسب طیف متنوعی از آشپزخانه‌ها.',
                'price' => 8900000,
                'old_price' => 9800000,
                'discount' => 9,
                'stock' => 7,
                'rating' => 4.8,
                'review_count' => 23,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'category_id' => $sink->id,
                'name' => 'سینک روکار فرزین مدل نوا',
                'slug' => 'sink-nova',
                'sku' => 'SK-2002',
                'brand' => 'Farzin',
                'short_description' => 'سینک روکار کاربردی با طراحی ساده و مقاوم.',
                'description' => 'سینک روکار فرزین مدل نوا برای استفاده روزمره با طراحی ساده و نصب آسان.',
                'price' => 7600000,
                'old_price' => null,
                'discount' => null,
                'stock' => 2,
                'rating' => 4.4,
                'review_count' => 9,
                'is_active' => true,
                'is_featured' => false,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['slug' => $product['slug']],
                $product
            );
        }
    }
}
