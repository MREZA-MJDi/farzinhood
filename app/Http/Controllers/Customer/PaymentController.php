<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Payment\PaymentService;
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
            $payment = $this->paymentService->start($order);
            $redirectUrl = data_get(
                $payment->gateway_response,
                'redirect_url'
            );

            if (is_string($redirectUrl) && $redirectUrl !== '') {
                return redirect()->away($redirectUrl);
            }

            return redirect()
                ->route('customer.orders.show', $order)
                ->with(
                    'success',
                    'درخواست پرداخت ایجاد شد. ادامه پرداخت از همین مسیر انجام می‌شود.'
                );

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
            $payment = $this->paymentService->verify(
                $order,
                $authority
            );

            if ($payment->isSuccessful()) {
                return redirect()
                    ->route('customer.orders.show', $order)
                    ->with('success', 'پرداخت با موفقیت تایید شد.');
            }

            return redirect()
                ->route('customer.orders.show', $order)
                ->with('error', 'تایید پرداخت ناموفق بود.');

        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('customer.orders.show', $order)
                ->with('error', 'Payment verification failed.');
        }
    }
}
