<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CheckoutRequest;
use App\Models\Address;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected CheckoutService $checkoutService,
    ) {
    }

    public function index(): View|RedirectResponse
    {
        $user = auth()->user();

        $items = $this->cartService->contents($user);
        $itemCount = $this->cartService->itemCount($user);

        if ($itemCount === 0) {
            return redirect()
                ->route('customer.cart.index')
                ->with('error', 'سبد خرید شما خالی است.');
        }

        $addresses = $user
            ->addresses()
            ->orderByDesc('is_default')
            ->latest()
            ->get();

        $summary = $this->checkoutService->summary($user);

        return view('customer.checkout.index', compact(
            'items',
            'itemCount',
            'addresses',
            'summary',
        ));
    }

    public function store(
        CheckoutRequest $request
    ): RedirectResponse {
        $validated = $request->validated();

        $address = Address::query()
            ->where('user_id', $request->user()->id)
            ->findOrFail($validated['address_id']);

        try {
            $order = $this->checkoutService->createOrder(
                $request->user(),
                $address,
                $validated['notes'] ?? null
            );
        } catch (\RuntimeException $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('customer.payment.start', $order)
            ->with(
                'success',
                'سفارش شما با موفقیت ثبت شد. در حال انتقال به پرداخت...'
            );
    }
}
