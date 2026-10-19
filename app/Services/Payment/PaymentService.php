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

    public function start(Order $order): Payment
    {
        $payment = DB::transaction(function () use ($order): Payment {
            $lockedOrder = Order::query()
                ->lockForUpdate()
                ->findOrFail($order->id);

            if ($lockedOrder->payment_status === 'paid') {
                throw new RuntimeException('این سفارش قبلاً پرداخت شده است.');
            }

            if ($lockedOrder->status !== 'pending') {
                throw new RuntimeException('این سفارش در وضعیت قابل پرداخت نیست.');
            }

            $existingPayment = $lockedOrder->payments()
                ->where('status', 'pending')
                ->latest()
                ->lockForUpdate()
                ->first();

            if ($existingPayment) {
                // A persisted gateway authority makes the start operation idempotent:
                // return the same payment instead of creating another external attempt.
                if ($existingPayment->transaction_id) {
                    return $existingPayment;
                }

                // A concurrent request may already be initializing this payment.
                // The short lease prevents a second purchase call while allowing
                // recovery if the process died before persisting the gateway result.
                $startedAt = data_get(
                    $existingPayment->gateway_response,
                    'purchase_started_at'
                );

                if (is_string($startedAt) && $startedAt !== '') {
                    try {
                        $leaseIsActive = \\Illuminate\\Support\\Carbon::parse($startedAt)
                            ->greaterThan(now()->subSeconds(90));
                    } catch (\\Throwable) {
                        $leaseIsActive = false;
                    }

                    if ($leaseIsActive) {
                        throw new RuntimeException(
                            'درخواست پرداخت در حال آماده‌سازی است؛ چند لحظه دیگر دوباره تلاش کن.'
                        );
                    }
                }

                $existingPayment->update([
                    'gateway_response' => [
                        'purchase_started_at' => now()->toIso8601String(),
                    ],
                    'gateway_message' => null,
                ]);

                return $existingPayment->fresh();
            }

            return $lockedOrder->payments()->create([
                'gateway' => config('services.payment.default', 'gateway'),
                'amount' => $lockedOrder->total,
                'status' => 'pending',
                'gateway_response' => [
                    'purchase_started_at' => now()->toIso8601String(),
                ],
            ]);
        });

        if ($payment->transaction_id) {
            return $payment;
        }

        try {
            $result = $this->gateway->purchase($order);

            $authority = $result['authority'] ?? null;

            if (! $authority) {
                throw new RuntimeException(
                    $result['message'] ?? 'درگاه پرداخت شناسه تراکنش معتبری برنگرداند.'
                );
            }

            return DB::transaction(function () use ($payment, $result, $authority): Payment {
                $lockedPayment = Payment::query()
                    ->lockForUpdate()
                    ->findOrFail($payment->id);

                if ($lockedPayment->status !== 'pending') {
                    return $lockedPayment;
                }

                $lockedPayment->update([
                    'transaction_id' => $authority,
                    'status' => 'pending',
                    'gateway_message' => $result['message'] ?? null,
                    'gateway_response' => $result,
                ]);

                return $lockedPayment->fresh();
            });
        } catch (\Throwable $exception) {
            $this->markPaymentFailure(
                $payment->id,
                $exception->getMessage()
            );

            throw $exception;
        }
    }

    public function verify(Order $order, string $authority): Payment
    {
        $payment = $order->payments()
            ->where('transaction_id', $authority)
            ->latest()
            ->first();

        if (! $payment) {
            throw new RuntimeException('تراکنش پرداخت پیدا نشد.');
        }

        if ($payment->status === 'successful') {
            return $payment;
        }

        if ((int) $payment->amount !== (int) $order->total) {
            throw new RuntimeException('مبلغ تراکنش با مبلغ سفارش مطابقت ندارد.');
        }

        try {
            $result = $this->gateway->verify($order, $authority);

            if (! ($result['success'] ?? false)) {
                $message = $result['message'] ?? 'پرداخت تایید نشد.';
                $this->markPaymentFailure($payment->id, $message);

                throw new RuntimeException($message);
            }

            return DB::transaction(function () use ($order, $payment, $result): Payment {
                $lockedOrder = Order::query()
                    ->lockForUpdate()
                    ->findOrFail($order->id);

                $lockedPayment = Payment::query()
                    ->lockForUpdate()
                    ->findOrFail($payment->id);

                if ($lockedPayment->status === 'successful') {
                    return $lockedPayment->fresh();
                }

                if ($lockedOrder->payment_status === 'paid') {
                    throw new RuntimeException('این سفارش قبلاً پرداخت شده است.');
                }

                $lockedPayment->update([
                    'status' => 'successful',
                    'tracking_code' => $result['tracking_code'] ?? null,
                    'gateway_message' => $result['message'] ?? null,
                    'gateway_response' => $result,
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
        } catch (\Throwable $exception) {
            if ($payment->fresh()?->status === 'successful') {
                return $payment->fresh();
            }

            throw $exception;
        }
    }

    public function refund(Order $order, int $amount): Payment
    {
        return DB::transaction(function () use ($order, $amount): Payment {
            $lockedOrder = Order::query()
                ->lockForUpdate()
                ->findOrFail($order->id);

            if ($lockedOrder->payment_status !== 'paid') {
                throw new RuntimeException('این سفارش پرداخت موفق ندارد.');
            }

            if ($amount <= 0) {
                throw new RuntimeException('مبلغ بازگشت وجه باید بیشتر از صفر باشد.');
            }

            $refundedAmount = (int) abs(
                $lockedOrder->payments()
                    ->where('status', 'refunded')
                    ->sum('amount')
            );

            $remainingRefundable = max(
                0,
                (int) $lockedOrder->total - $refundedAmount
            );

            if ($amount > $remainingRefundable) {
                throw new RuntimeException('مبلغ بازگشت وجه از مبلغ قابل بازگشت بیشتر است.');
            }

            $result = $this->gateway->refund($lockedOrder, $amount);

            if (! ($result['success'] ?? false)) {
                throw new RuntimeException(
                    $result['message'] ?? 'بازگشت وجه ناموفق بود.'
                );
            }

            $payment = $lockedOrder->payments()->create([
                'gateway' => config('services.payment.default', 'gateway'),
                'amount' => $amount,
                'status' => 'refunded',
                'tracking_code' => $result['tracking_code'] ?? null,
                'gateway_message' => $result['message'] ?? null,
                'gateway_response' => $result,
                'paid_at' => now(),
            ]);

            $newRefundedAmount = $refundedAmount + $amount;

            $lockedOrder->update([
                'payment_status' => $newRefundedAmount >= (int) $lockedOrder->total
                    ? 'refunded'
                    : 'paid',
            ]);

            return $payment->fresh();
        });
    }

    private function markPaymentFailure(
        int $paymentId,
        string $message
    ): void {
        DB::transaction(function () use ($paymentId, $message): void {
            $orderId = Payment::query()
                ->whereKey($paymentId)
                ->value('order_id');

            if (! $orderId) {
                return;
            }

            // Always lock order before payment. verify() follows this order too,
            // avoiding the old payment->order / order->payment deadlock pattern.
            $order = Order::query()
                ->whereKey($orderId)
                ->lockForUpdate()
                ->first();

            if (! $order) {
                return;
            }

            $payment = Payment::query()
                ->whereKey($paymentId)
                ->lockForUpdate()
                ->first();

            if (! $payment || in_array($payment->status, ['successful', 'refunded'], true)) {
                return;
            }

            if ($order->payment_status === 'paid') {
                // A stale failure callback for another attempt must never release
                // stock belonging to an order that has already been paid.
                $payment->update([
                    'status' => 'failed',
                    'gateway_message' => $message,
                    'gateway_response' => ['error' => $message],
                ]);

                return;
            }

            $alreadyFinalized = $order->status === 'cancelled'
                && $order->payment_status === 'failed';

            $payment->update([
                'status' => 'failed',
                'gateway_message' => $message,
                'gateway_response' => ['error' => $message],
            ]);

            if ($alreadyFinalized) {
                return;
            }

            $oldStatus = $order->status;

            $this->checkoutService->releaseReservedStock($order);

            $order->update([
                'payment_status' => 'failed',
                'status' => 'cancelled',
            ]);

            if ($oldStatus !== 'cancelled') {
                $order->statusHistories()->create([
                    'from_status' => $oldStatus,
                    'to_status' => 'cancelled',
                    'changed_by' => null,
                    'note' => 'پرداخت ناموفق بود و موجودی رزروشده آزاد شد.',
                ]);
            }
        });
    }
}
