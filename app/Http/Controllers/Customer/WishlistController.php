<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class WishlistController extends Controller
{
    public function index(): View
    {
        $wishlist = auth()->user()
            ->wishlists()
            ->with(['product.primaryImage', 'product.category'])
            ->latest()
            ->get();

        return view('customer.wishlist.index', compact('wishlist'));
    }

    public function toggle(Product $product): RedirectResponse
    {
        abort_unless($product->is_active, 404);

        $wishlist = auth()->user()
            ->wishlists()
            ->where('product_id', $product->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();

            return back()->with('success', 'Product removed from wishlist.');
        }

        auth()->user()->wishlists()->create([
            'product_id' => $product->id,
        ]);

        return back()->with('success', 'Product added to wishlist.');
    }
}
