<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CartRequest;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use RuntimeException;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {
    }

    public function index(): View
    {
        $user = auth()->user();

        $items = $this->cartService->contents($user);
        $subtotal = $this->cartService->subtotal($user);
        $itemCount = $this->cartService->itemCount($user);

        return view('customer.cart.index', compact(
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
            ->findOrFail($validated['product_id']);

        try {
            $this->cartService->add(
                $product,
                $validated['quantity'],
                auth()->user()
            );
        } catch (RuntimeException $exception) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $exception->getMessage(),
                ], 422);
            }

            return back()
                ->withInput()
                ->with('error', $exception->getMessage());
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

    public function update(
        CartRequest $request,
        Product $product
    ): RedirectResponse|JsonResponse {
        abort_unless($product->is_active, 404);

        $validated = $request->validated();

        try {
            $this->cartService->update(
                $product,
                $validated['quantity'],
                auth()->user()
            );
        } catch (RuntimeException $exception) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $exception->getMessage(),
                ], 422);
            }

            return back()
                ->withInput()
                ->with('error', $exception->getMessage());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'سبد خرید بروزرسانی شد.',
                'item_count' => $this->cartService->itemCount(auth()->user()),
                'subtotal' => $this->cartService->subtotal(auth()->user()),
            ]);
        }

        return back()->with('success', 'سبد خرید بروزرسانی شد.');
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

        return back()->with('success', 'محصول از سبد خرید حذف شد.');
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

        return back()->with('success', 'سبد خرید خالی شد.');
    }
}
