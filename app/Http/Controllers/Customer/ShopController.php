<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ShopIndexRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;

class ShopController extends Controller
{
    public function index(ShopIndexRequest $request): View
    {
        $filters = $request->validated();

        $products = Product::query()
            ->with(['primaryImage', 'category'])
            ->where('is_active', true)

            ->when(
                !empty($filters['search']),
                function ($query) use ($filters) {
                    $search = $filters['search'];

                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%")
                            ->orWhere('brand', 'like', "%{$search}%");
                    });
                }
            )

            ->when(
                !empty($filters['category']),
                function ($query) use ($filters) {
                    $query->whereHas(
                        'category',
                        fn ($q) => $q->where('slug', $filters['category'])
                    );
                }
            )

            ->when(
                isset($filters['min_price']),
                fn ($query) => $query->where('price', '>=', $filters['min_price'])
            )

            ->when(
                isset($filters['max_price']),
                fn ($query) => $query->where('price', '<=', $filters['max_price'])
            );

        $sort = $filters['sort'] ?? 'latest';

        match ($sort) {
            'price_asc' => $products->orderBy('price'),
            'price_desc' => $products->orderByDesc('price'),
            'popular' => $products->orderByDesc('review_count'),
            'rating' => $products->orderByDesc('rating'),
            default => $products->latest(),
        };

        $products = $products
            ->paginate($filters['per_page'] ?? 12)
            ->withQueryString();

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $priceMin = (int) Product::query()
            ->where('is_active', true)
            ->min('price');

        $priceMax = (int) Product::query()
            ->where('is_active', true)
            ->max('price');

        return view('shop.index', compact(
            'products',
            'categories',
            'priceMin',
            'priceMax',
            'filters'
        ));
    }
}
