<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReviewStatusRequest;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(ReviewIndexRequest $request)
    {
        $reviews = Review::query()
            ->with(['product', 'user'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($q) use ($search) {
                    $q->where('body', 'like', "%{$search}%")
                        ->orWhere('title', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($user) use ($search) {
                            $user->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('product', function ($product) use ($search) {
                            $product->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->filled('status'), fn ($query) =>
            $query->where('status', $request->input('status'))
            )
            ->when($request->filled('rating'), fn ($query) =>
            $query->where('rating', $request->integer('rating'))
            )
            ->latest()
            ->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }
    public function updateStatus(
        ReviewStatusRequest $request,
        Review $review
    ): RedirectResponse {
        $review->update([
            'status' => $request->validated('status'),
        ]);

        return back()->with(
            'success',
            'Review status updated successfully.'
        );
    }

    public function destroy(
        Review $review
    ): RedirectResponse {
        $review->delete();

        return back()->with(
            'success',
            'Review deleted successfully.'
        );
    }
}
