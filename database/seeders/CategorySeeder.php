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
                'name' => 'لوازم دیجیتال',
                'slug' => 'digital',
                'description' => 'انواع محصولات و لوازم دیجیتال',
                'sort_order' => 1,
            ],
            [
                'name' => 'لوازم خانگی',
                'slug' => 'home-appliances',
                'description' => 'محصولات کاربردی برای خانه',
                'sort_order' => 2,
            ],
            [
                'name' => 'پوشاک',
                'slug' => 'fashion',
                'description' => 'انواع لباس و پوشاک',
                'sort_order' => 3,
            ],
            [
                'name' => 'زیبایی و سلامت',
                'slug' => 'beauty-health',
                'description' => 'محصولات زیبایی و مراقبت شخصی',
                'sort_order' => 4,
            ],
            [
                'name' => 'اکسسوری',
                'slug' => 'accessories',
                'description' => 'اکسسوری و لوازم جانبی',
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'sort_order' => $category['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
