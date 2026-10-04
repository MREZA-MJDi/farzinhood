<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $images = [
            'hood-arya' => [
                [
                    'image' => 'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?auto=format&fit=crop&w=1200&q=85',
                    'alt' => 'هود مورب فرزین مدل آریا',
                    'sort_order' => 1,
                    'is_primary' => true,
                ],
                [
                    'image' => 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?auto=format&fit=crop&w=1200&q=85',
                    'alt' => 'نمای دوم هود مورب فرزین مدل آریا',
                    'sort_order' => 2,
                    'is_primary' => false,
                ],
            ],

            'hood-nika' => [
                [
                    'image' => 'https://images.unsplash.com/photo-1556911220-bff31c812dba?auto=format&fit=crop&w=1200&q=85',
                    'alt' => 'هود مخفی فرزین مدل نیکا',
                    'sort_order' => 1,
                    'is_primary' => true,
                ],
                [
                    'image' => 'https://images.unsplash.com/photo-1556909212-d5b604d0c90d?auto=format&fit=crop&w=1200&q=85',
                    'alt' => 'نمای دوم هود مخفی فرزین مدل نیکا',
                    'sort_order' => 2,
                    'is_primary' => false,
                ],
            ],

            'sink-classic' => [
                [
                    'image' => 'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?auto=format&fit=crop&w=1200&q=85',
                    'alt' => 'سینک توکار فرزین مدل کلاسیک',
                    'sort_order' => 1,
                    'is_primary' => true,
                ],
                [
                    'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=85',
                    'alt' => 'نمای دوم سینک توکار فرزین مدل کلاسیک',
                    'sort_order' => 2,
                    'is_primary' => false,
                ],
            ],

            'sink-nova' => [
                [
                    'image' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=85',
                    'alt' => 'سینک روکار فرزین مدل نوا',
                    'sort_order' => 1,
                    'is_primary' => true,
                ],
                [
                    'image' => 'https://images.unsplash.com/photo-1600566753051-f0b89df2dd90?auto=format&fit=crop&w=1200&q=85',
                    'alt' => 'نمای دوم سینک روکار فرزین مدل نوا',
                    'sort_order' => 2,
                    'is_primary' => false,
                ],
            ],
        ];

        foreach ($images as $slug => $productImages) {

            $product = Product::query()
                ->where('slug', $slug)
                ->first();

            if (! $product) {
                continue;
            }

            $product->images()->delete();

            $product->images()->createMany(
                $productImages
            );
        }
    }
}
