<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Contracts\View\View;

class ProductController extends Controller
{
    public function show(Product $product): View
    {
        $product = Product::query()
            ->where('is_active', true)
            ->whereHas(
                'category',
                fn ($query) => $query->where('is_active', true)
            )
            ->with([
            'category',
            'images',
            'primaryImage',
            'reviews' => fn ($query) => $query
                ->approved()
                ->with('user:id,name')
                ->latest()
                ->limit(6),
        ]);

        $relatedProducts = Product::query()
            ->with(['primaryImage', 'category'])
            ->where('is_active', true)
            ->whereHas(
                'category',
                fn ($query) => $query->where('is_active', true)
            )
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->latest('created_at')
            ->latest('id')
            ->take(4)
            ->get();

        $approvedReviews = Review::query()
            ->approved()
            ->where('product_id', $product->id);

        $reviewCount = (int) (clone $approvedReviews)->count('id');
        $reviewAverage = round((float) (clone $approvedReviews)->avg('rating'), 1);

        $isWishlisted = false;
        $canReview = false;

        if (auth()->check() && auth()->user()->isCustomer()) {
            $userId = auth()->id();

            $isWishlisted = auth()->user()
                ->wishlists()
                ->where('product_id', $product->id)
                ->exists();

            $hasPurchased = Order::query()
                ->where('user_id', $userId)
                ->where('payment_status', 'paid')
                ->whereHas(
                    'items',
                    fn ($query) => $query->where('product_id', $product->id)
                )
                ->exists();

            $alreadyReviewed = Review::query()
                ->where('user_id', $userId)
                ->where('product_id', $product->id)
                ->exists();

            $canReview = $hasPurchased && ! $alreadyReviewed;
        }

        return view('products.show', compact(
            'product',
            'relatedProducts',
            'reviewCount',
            'reviewAverage',
            'isWishlisted',
            'canReview',
        ));
    }
}
