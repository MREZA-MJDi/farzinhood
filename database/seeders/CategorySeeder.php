<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'هود',
                'slug' => 'hood',
                'description' => 'انواع هود آشپزخانه با طراحی مدرن و کاربردی.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'سینک',
                'slug' => 'sink',
                'description' => 'سینک‌های توکار و روکار برای آشپزخانه‌های امروزی.',
                'sort_order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
