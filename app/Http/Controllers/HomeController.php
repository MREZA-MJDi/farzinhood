<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $latestProducts = Product::query()
            ->with(['primaryImage', 'category'])
            ->where('is_active', true)
            ->whereHas('category', fn ($query) => $query->where('is_active', true))
            ->whereHas('primaryImage')
            ->latest('created_at')
            ->latest('id')
            ->take(10)
            ->get();

        $heroProducts = $latestProducts->values();
        $latestProducts = $latestProducts
            ->take(8)
            ->values();

        $wishlistedProductIds = auth()->check() && auth()->user()->isCustomer()
            ? auth()->user()->wishlists()->pluck('product_id')
            : collect();

        $featuredProducts = Product::query()
            ->with(['primaryImage', 'category'])
            ->where('is_active', true)
            ->whereHas('category', fn ($query) => $query->where('is_active', true))
            ->where('is_featured', true)
            ->latest('created_at')
            ->latest('id')
            ->take(8)
            ->get();

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->take(5)
            ->get();

        return view('home', compact(
            'heroProducts',
            'featuredProducts',
            'latestProducts',
            'categories',
            'wishlistedProductIds',
        ));
    }
}
