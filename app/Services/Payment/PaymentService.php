<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Payment;
use App\Services\CheckoutService;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PaymentService
{
    public function __construct(
        private PaymentGatewayInterface $gateway,
        private CheckoutService $checkoutService,
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Start Payment
    |--------------------------------------------------------------------------
    */

    public function start(Order $order): Payment
    {
        return DB::transaction(function () use ($order) {

            $order = Order::query()
                ->lockForUpdate()
                ->findOrFail($order->id);

            /*
             * Already paid
             */
            if ($order->payment_status === 'paid') {
                throw new RuntimeException(
                    'این سفارش قبلاً پرداخت شده است.'
                );
            }

            /*
             * Only pending orders are payable.
             */
            if ($order->status !== 'pending') {
                throw new RuntimeException(
                    'این سفارش در وضعیت قابل پرداخت نیست.'
                );
            }

            /*
             * Prevent multiple active payment attempts.
             */
            $existingPayment = $order->payments()
                ->where('status', 'pending')
                ->latest()
                ->first();

            if ($existingPayment) {
                return $existingPayment->fresh();
            }

            /*
             * Create local payment record first.
             */
            $payment = $order->payments()->create([
                'gateway' => config(
                    'services.payment.default',
                    'gateway'
                ),

                'amount' => $order->total,
                'status' => 'pending',
            ]);

            try {

                $result = $this->gateway->purchase(
                    $order
                );

                $authority = $result['authority'] ?? null;

                if (! $authority) {
                    throw new RuntimeException(
                        $result['message']
                        ?? 'درگاه پرداخت شناسه تراکنش معتبری برنگرداند.'
                    );
                }

                $payment->update([
                    'transaction_id' => $authority,

                    'status' => 'pending',

                    'gateway_message' =>
                        $result['message'] ?? null,

                    'gateway_response' => $result,
                ]);

                return $payment->fresh();

            } catch (\Throwable $exception) {

                $payment->update([
                    'status' => 'failed',

                    'gateway_message' =>
                        $exception->getMessage(),

                    'gateway_response' => [
                        'exception' =>
                            get_class($exception),

                        'message' =>
                            $exception->getMessage(),
                    ],
                ]);

                /*
                 * Payment failed before user paid.
                 * Release the stock reservation.
                 */
                $this->checkoutService
                    ->releaseReservedStock($order);

                $order->update([
                    'payment_status' => 'failed',
                    'status' => 'cancelled',
                ]);

                $order->statusHistories()->create([
                    'from_status' => 'pending',
                    'to_status' => 'cancelled',
                    'changed_by' => null,
                    'note' => 'ایجاد تراکنش پرداخت ناموفق بود و موجودی آزاد شد.',
                ]);

                throw $exception;
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Verify Payment
    |--------------------------------------------------------------------------
    */

    public function verify(
        Order $order,
        string $authority
    ): Payment {
        return DB::transaction(function () use (
            $order,
            $authority
        ) {

            $order = Order::query()
                ->lockForUpdate()
                ->findOrFail($order->id);

            /*
             * Already paid.
             */
            if ($order->payment_status === 'paid') {

                $payment = $order->payments()
                    ->where('transaction_id', $authority)
                    ->latest()
                    ->first();

                if ($payment) {
                    return $payment;
                }

                return $order->payments()
                    ->where('status', 'paid')
                    ->latest()
                    ->firstOrFail();
            }

            /*
             * Find the exact payment attempt.
             */
            $payment = $order->payments()
                ->where('transaction_id', $authority)
                ->latest()
                ->first();

            if (! $payment) {
                throw new RuntimeException(
                    'تراکنش پرداخت پیدا نشد.'
                );
            }

            /*
             * Already verified.
             */
            if ($payment->status === 'paid') {
                return $payment;
            }

            /*
             * Payment amount must match the current order.
             */
            if ((int) $payment->amount !== (int) $order->total) {
                throw new RuntimeException(
                    'مبلغ تراکنش با مبلغ سفارش مطابقت ندارد.'
                );
            }

            try {

                $result = $this->gateway->verify(
                    $order,
                    $authority
                );

                if (! ($result['success'] ?? false)) {

                    $message =
                        $result['message']
                        ?? 'پرداخت تایید نشد.';

                    $payment->update([
                        'status' => 'failed',

                        'gateway_message' =>
                            $message,

                        'gateway_response' =>
                            $result,
                    ]);

                    $order->update([
                        'payment_status' => 'failed',
                        'status' => 'cancelled',
                    ]);

                    /*
                     * Release reserved inventory.
                     */
                    $this->checkoutService
                        ->releaseReservedStock($order);

                    $order->statusHistories()->create([
                        'from_status' => $order->status,
                        'to_status' => 'cancelled',
                        'changed_by' => null,
                        'note' => 'پرداخت ناموفق بود و موجودی آزاد شد.',
                    ]);

                    throw new RuntimeException(
                        $message
                    );
                }

                /*
                 * Successful payment.
                 */
                $payment->update([
                    'status' => 'paid',

                    'tracking_code' =>
                        $result['tracking_code'] ?? null,

                    'gateway_message' =>
                        $result['message'] ?? null,

                    'gateway_response' =>
                        $result,

                    'paid_at' => now(),
                ]);

                $oldStatus = $order->status;

                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                ]);

                /*
                 * Order status transition.
                 */
                if ($oldStatus !== 'processing') {
                    $order->statusHistories()->create([
                        'from_status' => $oldStatus,
                        'to_status' => 'processing',
                        'changed_by' => null,
                        'note' => 'پرداخت با موفقیت تایید شد.',
                    ]);
                }

                return $payment->fresh();

            } catch (\Throwable $exception) {

                /*
                 * Do not overwrite a successful payment.
                 */
                if ($payment->status !== 'paid') {

                    $payment->update([
                        'status' => 'failed',

                        'gateway_message' =>
                            $exception->getMessage(),
                    ]);
                }

                throw $exception;
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Refund
    |--------------------------------------------------------------------------
    */

    public function refund(
        Order $order,
        int $amount
    ): Payment {
        return DB::transaction(function () use (
            $order,
            $amount
        ) {

            $order = Order::query()
                ->lockForUpdate()
                ->findOrFail($order->id);

            if ($order->payment_status !== 'paid') {
                throw new RuntimeException(
                    'این سفارش پرداخت موفق ندارد.'
                );
            }

            if ($amount <= 0) {
                throw new RuntimeException(
                    'مبلغ بازگشت وجه باید بیشتر از صفر باشد.'
                );
            }

            /*
             * Total refund amount already made.
             */
            $refundedAmount = (int) abs(
                $order->payments()
                    ->where('status', 'refunded')
                    ->sum('amount')
            );

            $remainingRefundable = max(
                0,
                (int) $order->total - $refundedAmount
            );

            if ($amount > $remainingRefundable) {
                throw new RuntimeException(
                    'مبلغ بازگشت وجه از مبلغ قابل بازگشت بیشتر است.'
                );
            }

            /*
             * Call gateway.
             */
            $result = $this->gateway->refund(
                $order,
                $amount
            );

            if (! ($result['success'] ?? false)) {
                throw new RuntimeException(
                    $result['message']
                    ?? 'بازگشت وجه ناموفق بود.'
                );
            }

            /*
             * Create refund payment record.
             */
            $payment = $order->payments()->create([
                'gateway' => config(
                    'services.payment.default',
                    'gateway'
                ),

                'amount' => -$amount,

                'status' => 'refunded',

                'tracking_code' =>
                    $result['tracking_code'] ?? null,

                'gateway_message' =>
                    $result['message'] ?? null,

                'gateway_response' =>
                    $result,

                'paid_at' => now(),
            ]);

            /*
             * Full refund vs partial refund.
             */
            $newRefundedAmount =
                $refundedAmount + $amount;

            $paymentStatus =
                $newRefundedAmount >= (int) $order->total
                    ? 'refunded'
                    : 'paid';

            $order->update([
                'payment_status' => $paymentStatus,
            ]);

            return $payment->fresh();
        });
    }
}
