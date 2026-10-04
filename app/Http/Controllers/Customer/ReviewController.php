<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ReviewRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function store(ReviewRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = $request->user();

        $product = Product::query()
            ->where('is_active', true)
            ->findOrFail($validated['product_id']);

        $orderQuery = Order::query()
            ->where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->whereHas('items', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            });

        if (!empty($validated['order_id'])) {
            $orderQuery->whereKey($validated['order_id']);
        }

        $order = $orderQuery->first();

        if (!$order) {
            return back()->with(
                'error',
                'You can only review a product you purchased.'
            );
        }

        $alreadyReviewed = Review::query()
            ->where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->where('order_id', $order->id)
            ->exists();

        if ($alreadyReviewed) {
            return back()->with(
                'error',
                'You have already reviewed this product for this order.'
            );
        }

        DB::transaction(function () use (
            $validated,
            $user,
            $product,
            $order
        ) {
            Review::create([
                'product_id' => $product->id,
                'user_id' => $user->id,
                'order_id' => $order->id,
                'rating' => $validated['rating'],
                'title' => $validated['title'] ?? null,
                'body' => $validated['body'],
                'status' => 'pending',
            ]);
        });

        return back()->with(
            'success',
            'Your review has been submitted and is waiting for approval.'
        );
    }
}
