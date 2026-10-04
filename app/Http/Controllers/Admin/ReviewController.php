<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReviewIndexRequest;
use App\Http\Requests\Admin\ReviewStatusRequest;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(ReviewIndexRequest $request): View
    {
        $reviews = Review::query()
            ->with([
                'product:id,name,slug',
                'user:id,name,email',
            ])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($q) use ($search) {
                    $q->where('body', 'like', "%{$search}%")
                        ->orWhere('title', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($user) use ($search) {
                            $user
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('product', function ($product) use ($search) {
                            $product->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where('status', $request->input('status'))
            )
            ->when(
                $request->filled('rating'),
                fn ($query) => $query->where('rating', $request->integer('rating'))
            )
            ->latest()
            ->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function updateStatus(
        ReviewStatusRequest $request,
        Review $review
    ): RedirectResponse {
        $status = $request->validated('status');

        DB::transaction(function () use ($review, $status): void {
            $review->update(['status' => $status]);
            $this->syncProductRating($review->product_id);
        });

        return back()->with(
            'success',
            'وضعیت نظر با موفقیت بروزرسانی شد.'
        );
    }

    public function destroy(Review $review): RedirectResponse
    {
        DB::transaction(function () use ($review): void {
            $productId = $review->product_id;

            $review->delete();
            $this->syncProductRating($productId);
        });

        return back()->with(
            'success',
            'نظر حذف شد.'
        );
    }

    private function syncProductRating(int $productId): void
    {
        $aggregate = Review::query()
            ->approved()
            ->where('product_id', $productId)
            ->selectRaw('COUNT(*) as review_count, AVG(rating) as rating')
            ->first();

        Product::query()
            ->whereKey($productId)
            ->update([
                'review_count' => (int) ($aggregate?->review_count ?? 0),
                'rating' => round((float) ($aggregate?->rating ?? 0), 1),
            ]);
    }
}
