<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Payment;
use App\Services\CheckoutService;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

class PaymentService
{
    public function __construct(
        private PaymentGatewayInterface $gateway,
        private CheckoutService $checkoutService,
    ) {
    }

    public function start(Order $order): Payment
    {
        $result = DB::transaction(function () use ($order) {
            $lockedOrder = Order::query()
                ->lockForUpdate()
                ->findOrFail($order->id);

            if ($lockedOrder->payment_status === 'paid') {
                throw new RuntimeException(
                    'این سفارش قبلاً پرداخت شده است.'
                );
            }

            if ($lockedOrder->status !== 'pending') {
                throw new RuntimeException(
                    'این سفارش در وضعیت قابل پرداخت نیست.'
                );
            }

            $existingPayment = $lockedOrder->payments()
                ->where('status', 'pending')
                ->latest()
                ->first();

            if ($existingPayment) {
                return [
                    'payment' => $existingPayment->fresh(),
                    'should_start' => false,
                ];
            }

            return [
                'payment' => $lockedOrder->payments()->create([
                    'gateway' => config(
                        'services.payment.default',
                        'unavailable'
                    ),
                    'amount' => $lockedOrder->total,
                    'status' => 'pending',
                ]),
                'should_start' => true,
            ];
        });

        /** @var Payment $payment */
        $payment = $result['payment'];

        if (! $result['should_start']) {
            return $payment;
        }

        try {
            $gatewayResult = $this->gateway->purchase($order);
            $authority = $gatewayResult['authority'] ?? null;

            if (! $authority) {
                throw new RuntimeException(
                    $gatewayResult['message']
                    ?? 'درگاه پرداخت شناسه تراکنش معتبری برنگرداند.'
                );
            }

            return DB::transaction(function () use (
                $payment,
                $authority,
                $gatewayResult
            ) {
                $payment = Payment::query()
                    ->lockForUpdate()
                    ->findOrFail($payment->id);

                if ($payment->status === 'paid') {
                    return $payment;
                }

                $payment->update([
                    'transaction_id' => $authority,
                    'status' => 'pending',
                    'gateway_message' => $gatewayResult['message'] ?? null,
                    'gateway_response' => $gatewayResult,
                ]);

                return $payment->fresh();
            });

        } catch (Throwable $exception) {
            $this->failPayment(
                $payment->id,
                $exception->getMessage(),
                [
                    'exception' => get_class($exception),
                    'message' => $exception->getMessage(),
                ]
            );

            throw $exception;
        }
    }

    public function verify(
        Order $order,
        string $authority
    ): Payment {
        $payment = DB::transaction(function () use (
            $order,
            $authority
        ) {
            $lockedOrder = Order::query()
                ->lockForUpdate()
                ->findOrFail($order->id);

            if ($lockedOrder->payment_status === 'paid') {
                return $lockedOrder->payments()
                    ->where('transaction_id', $authority)
                    ->latest()
                    ->first()
                    ?? $lockedOrder->payments()
                        ->where('status', 'paid')
                        ->latest()
                        ->firstOrFail();
            }

            $payment = $lockedOrder->payments()
                ->where('transaction_id', $authority)
                ->latest()
                ->first();

            if (! $payment) {
                throw new RuntimeException(
                    'تراکنش پرداخت پیدا نشد.'
                );
            }

            if ($payment->status === 'paid') {
                return $payment;
            }

            if ((int) $payment->amount !== (int) $lockedOrder->total) {
                throw new RuntimeException(
                    'مبلغ تراکنش با مبلغ سفارش مطابقت ندارد.'
                );
            }

            return $payment;
        });

        if ($payment->status === 'paid') {
            return $payment;
        }

        try {
            $gatewayResult = $this->gateway->verify(
                $order,
                $authority
            );

            if (! ($gatewayResult['success'] ?? false)) {
                $message = $gatewayResult['message']
                    ?? 'پرداخت تایید نشد.';

                $this->failPayment(
                    $payment->id,
                    $message,
                    $gatewayResult
                );

                throw new RuntimeException($message);
            }

            return DB::transaction(function () use (
                $payment,
                $authority,
                $gatewayResult
            ) {
                $lockedPayment = Payment::query()
                    ->lockForUpdate()
                    ->findOrFail($payment->id);

                $lockedOrder = Order::query()
                    ->lockForUpdate()
                    ->findOrFail($lockedPayment->order_id);

                if ($lockedOrder->payment_status === 'paid') {
                    return $lockedPayment->fresh();
                }

                if ($lockedPayment->status === 'paid') {
                    return $lockedPayment->fresh();
                }

                if ((int) $lockedPayment->amount !== (int) $lockedOrder->total) {
                    throw new RuntimeException(
                        'مبلغ تراکنش با مبلغ سفارش مطابقت ندارد.'
                    );
                }

                $lockedPayment->update([
                    'status' => 'paid',
                    'transaction_id' => $authority,
                    'tracking_code' => $gatewayResult['tracking_code'] ?? null,
                    'gateway_message' => $gatewayResult['message'] ?? null,
                    'gateway_response' => $gatewayResult,
                    'paid_at' => now(),
                ]);

                $oldStatus = $lockedOrder->status;

                $lockedOrder->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                ]);

                if ($oldStatus !== 'processing') {
                    $lockedOrder->statusHistories()->create([
                        'from_status' => $oldStatus,
                        'to_status' => 'processing',
                        'changed_by' => null,
                        'note' => 'پرداخت با موفقیت تایید شد.',
                    ]);
                }

                return $lockedPayment->fresh();
            });

        } catch (Throwable $exception) {
            $payment->refresh();

            if ($payment->status !== 'paid') {
                $this->failPayment(
                    $payment->id,
                    $exception->getMessage()
                );
            }

            throw $exception;
        }
    }

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

            $payment = $order->payments()->create([
                'gateway' => config(
                    'services.payment.default',
                    'unavailable'
                ),
                'amount' => -$amount,
                'status' => 'refunded',
                'tracking_code' => $result['tracking_code'] ?? null,
                'gateway_message' => $result['message'] ?? null,
                'gateway_response' => $result,
                'paid_at' => now(),
            ]);

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

    private function failPayment(
        int $paymentId,
        string $message,
        ?array $gatewayResponse = null
    ): Payment {
        return DB::transaction(function () use (
            $paymentId,
            $message,
            $gatewayResponse
        ) {
            $payment = Payment::query()
                ->lockForUpdate()
                ->findOrFail($paymentId);

            $order = Order::query()
                ->lockForUpdate()
                ->findOrFail($payment->order_id);

            if (
                $payment->status !== 'pending'
                || $order->payment_status === 'paid'
            ) {
                return $payment->fresh();
            }

            $payment->update([
                'status' => 'failed',
                'gateway_message' => $message,
                'gateway_response' => $gatewayResponse
                    ?? ['message' => $message],
            ]);

            if (
                $order->status === 'pending'
                && $order->payment_status !== 'paid'
            ) {
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
                    'note' => 'پرداخت ناموفق بود و موجودی آزاد شد.',
                ]);
            }

            return $payment->fresh();
        });
    }
}
