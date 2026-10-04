<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {
    }

    public function start(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        if ($order->payment_status === 'paid') {
            return redirect()
                ->route('customer.orders.show', $order)
                ->with('success', 'This order has already been paid.');
        }

        try {
            $result = $this->paymentService->start($order);

            if (!empty($result['redirect_url'])) {
                return redirect()->away($result['redirect_url']);
            }

            return redirect()
                ->route('customer.orders.show', $order)
                ->with('success', 'Payment request created successfully.');

        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('customer.orders.show', $order)
                ->with('error', 'Unable to start payment.');
        }
    }

    public function callback(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        $authority = $request->string('authority')->toString();

        if ($authority === '') {
            return redirect()
                ->route('customer.orders.show', $order)
                ->with('error', 'Payment authority is missing.');
        }

        try {
            $result = $this->paymentService->verify(
                $order,
                $authority
            );

            if (!empty($result['success'])) {
                return redirect()
                    ->route('customer.orders.show', $order)
                    ->with('success', 'Payment completed successfully.');
            }

            return redirect()
                ->route('customer.orders.show', $order)
                ->with('error', $result['message'] ?? 'Payment verification failed.');

        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('customer.orders.show', $order)
                ->with('error', 'Payment verification failed.');
        }
    }
}
