<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ShopIndexRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;

class ShopController extends Controller
{

    public function suggestions(ShopIndexRequest $request): \Illuminate\Http\JsonResponse
    {
        $search = trim((string) $request->validated('search'));

        if ($search === '' || mb_strlen($search) < 2) {
            return response()->json([
                'items' => [],
            ]);
        }

        $products = Product::query()
            ->with(['primaryImage', 'category'])
            ->where('is_active', true)
            ->whereHas('category', fn ($query) => $query->where('is_active', true))
            ->where(function ($query) use ($search) {
                $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhereHas('category', fn ($category) =>
                        $category->where('name', 'like', "%{$search}%")
                    );
            })
            ->orderByDesc('is_featured')
            ->latest('created_at')
            ->latest('id')
            ->limit(6)
            ->get();

        return response()->json([
            'items' => $products->map(
                fn (Product $product): array => [
                    'name' => $product->name,
                    'url' => route('products.show', $product),
                    'price' => (int) $product->price,
                    'brand' => $product->brand,
                    'category' => $product->category?->name,
                    'image' => $product->primaryImage?->image
                        ? asset('storage/' . $product->primaryImage->image)
                        : null,
                ]
            )->values(),
        ]);
    }

    public function index(ShopIndexRequest $request): View
    {
        $filters = $request->validated();

        $shopHeroProduct = Product::query()
            ->with(['primaryImage', 'category'])
            ->where('is_active', true)
            ->whereHas('category', fn ($query) => $query->where('is_active', true))
            ->whereHas('primaryImage')
            ->orderByDesc('is_featured')
            ->latest('created_at')
            ->latest('id')
            ->first();

        $products = Product::query()
            ->with(['primaryImage', 'category'])
            ->where('is_active', true)
            ->whereHas('category', fn ($query) => $query->where('is_active', true))

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
                        fn ($q) => $q
                            ->where('slug', $filters['category'])
                            ->where('is_active', true)
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

        $priceBounds = Product::query()
            ->where('is_active', true)
            ->whereHas('category', fn ($query) => $query->where('is_active', true))
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();

        $priceMin = (int) ($priceBounds?->min_price ?? 0);
        $priceMax = (int) ($priceBounds?->max_price ?? 0);

        return view('shop.index', compact(
            'products',
            'categories',
            'priceMin',
            'priceMax',
            'filters',
            'shopHeroProduct'
        ));
    }
}
