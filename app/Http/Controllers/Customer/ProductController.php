<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Contracts\View\View;

class ProductController extends Controller
{
    public function show(Product $product): View
    {
        $product->load([
            'category',
            'images',
            'primaryImage',
            'reviews' => fn ($query) => $query
                ->with('user:id,name')
                ->where('status', 'approved')
                ->latest()
                ->limit(6),
        ]);

        abort_unless(
            $product->is_active && $product->category?->is_active,
            404
        );

        $relatedProducts = Product::query()

        $relatedProducts = Product::query()
            ->with(['primaryImage', 'category'])
            ->where('is_active', true)
            ->whereHas('category', fn ($query) => $query->where('is_active', true))
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->latest()
            ->take(4)
            ->get();

        $reviewCount = (int) $product->review_count;

        $canReview = false;
        if (auth()->check()) {
            $userId = auth()->id();
            $canReview = ! \App\Models\Review::query()
                ->where('user_id', $userId)
                ->where('product_id', $product->id)
                ->exists()
                && \App\Models\Order::query()
                    ->where('user_id', $userId)
                    ->where('payment_status', 'paid')
                    ->whereHas('items', fn ($query) => $query->where('product_id', $product->id))
                    ->exists();
        }

        return view('products.show', compact(
            'product',
            'relatedProducts',
            'reviewCount',
            'canReview',
        ));
    }
}
