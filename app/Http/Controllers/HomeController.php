<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $heroProducts = Product::query()
            ->with(['primaryImage', 'category'])
            ->where('is_active', true)
            ->whereHas('category', fn ($query) => $query->where('is_active', true))
            ->whereHas('primaryImage')
            ->latest('created_at')
            ->take(5)
            ->get();

        $featuredProducts = Product::query()
            ->with(['primaryImage', 'category'])
            ->where('is_active', true)
            ->whereHas('category', fn ($query) => $query->where('is_active', true))
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        $latestProducts = Product::query()
            ->with(['primaryImage', 'category'])
            ->where('is_active', true)
            ->whereHas('category', fn ($query) => $query->where('is_active', true))
            ->latest()
            ->take(8)
            ->get();

        $tourFrames = HomeTourFrame::query()
            ->where('is_active', true)
            ->orderBy('scene')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->groupBy('scene');

        $categories = Category::query()
            ->withCount(['products as active_products_count' => fn ($query) => $query->where('is_active', true)])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->take(6)
            ->get();

        return view('storefront.home', compact(
            'heroProducts',
            'featuredProducts',
            'latestProducts',
            'categories'
        ));
    }
}
