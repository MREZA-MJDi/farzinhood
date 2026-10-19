<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CartRequest;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {
    }

    public function index(): View
    {
        $items = $this->cartService->contents(auth()->user());
        $subtotal = $this->cartService->subtotal(auth()->user());
        $itemCount = $this->cartService->itemCount(auth()->user());

        return view('cart.index', compact(
            'items',
            'subtotal',
            'itemCount',
        ));
    }

    public function add(CartRequest $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();

        $product = Product::query()
            ->where('is_active', true)
            ->whereHas(
                'category',
                fn ($query) => $query->where('is_active', true)
            )
            ->findOrFail($validated['product_id']);

        try {
            $this->cartService->add(
                $product,
                $validated['quantity'],
                auth()->user()
            );
        } catch (\RuntimeException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->withInput()->with('error', $e->getMessage());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'محصول به سبد خرید اضافه شد.',
                'item_count' => $this->cartService->itemCount(auth()->user()),
                'subtotal' => $this->cartService->subtotal(auth()->user()),
            ]);
        }

        return back()->with('success', 'محصول به سبد خرید اضافه شد.');
    }

    public function update(CartRequest $request, Product $product): RedirectResponse|JsonResponse
    {
        abort_unless(
            $product->is_active
            && $product->category?->is_active,
            404
        );

        $validated = $request->validated();

        try {
            $this->cartService->update(
                $product,
                $validated['quantity'],
                auth()->user()
            );
        } catch (\RuntimeException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->withInput()->with('error', $e->getMessage());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'سبد خرید به‌روزرسانی شد.',
                'item_count' => $this->cartService->itemCount(auth()->user()),
                'subtotal' => $this->cartService->subtotal(auth()->user()),
            ]);
        }

        return back()->with('success', 'سبد خرید به‌روزرسانی شد.');
    }

    public function remove(Product $product): RedirectResponse|JsonResponse
    {
        $this->cartService->remove(
            $product,
            auth()->user()
        );

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'محصول از سبد خرید حذف شد.',
                'item_count' => $this->cartService->itemCount(auth()->user()),
                'subtotal' => $this->cartService->subtotal(auth()->user()),
            ]);
        }

        return back()->with('success', 'Product removed from cart.');
    }

    public function clear(): RedirectResponse|JsonResponse
    {
        $this->cartService->clear(auth()->user());

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'سبد خرید خالی شد.',
                'item_count' => 0,
                'subtotal' => 0,
            ]);
        }

        return back()->with('success', 'Cart cleared successfully.');
    }
}
