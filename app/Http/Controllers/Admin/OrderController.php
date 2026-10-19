<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderIndexRequest;
use App\Http\Requests\Admin\OrderRefundRequest;
use App\Http\Requests\Admin\OrderStatusRequest;
use App\Models\Order;
use App\Services\CheckoutService;
use App\Services\Payment\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RuntimeException;

class OrderController extends Controller
{
    public function __construct(
        private CheckoutService $checkoutService,
        private PaymentService $paymentService,
    ) {
    }

    public function index(OrderIndexRequest $request): View
    {
        $orders = Order::query()
            ->with(['user:id,name,email'])
            ->withCount('items')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                        ->orWhere('shipping_full_name', 'like', "%{$search}%")
                        ->orWhere('shipping_phone', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($user) use ($search) {
                            $user->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->validated('status'));
            })
            ->when($request->filled('payment_status'), function ($query) use ($request) {
                $query->where('payment_status', $request->validated('payment_status'));
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load([
            'user:id,name,email',
            'items.product:id,name,slug',
            'payments',
            'latestPayment',
            'statusHistories.changedBy:id,name',
        ]);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(
        OrderStatusRequest $request,
        Order $order
    ): RedirectResponse {
        $validated = $request->validated();

        $result = DB::transaction(function () use ($order, $validated, $request): string {
            $lockedOrder = Order::query()
                ->whereKey($order->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $oldStatus = $lockedOrder->status;
            $newStatus = $validated['status'];

            if ($oldStatus === $newStatus) {
                if ($newStatus === 'cancelled' && $lockedOrder->payment_status === 'paid') {
                    return 'refund_required';
                }

                // Repair a legacy cancelled order whose stock/payment state was
                // not finalized by older code. The inventory ledger makes this safe
                // when the reservation was already released.
                if (
                    $newStatus === 'cancelled'
                    && $lockedOrder->payment_status === 'pending'
                    && ! $lockedOrder->payments()->where('status', 'pending')->exists()
                ) {
                    $this->checkoutService->releaseReservedStock($lockedOrder);
                    $lockedOrder->update(['payment_status' => 'failed']);

                    return 'repaired';
                }

                return 'unchanged';
            }

            if ($newStatus === 'cancelled') {
                if ($lockedOrder->payment_status === 'paid') {
                    return 'refund_required';
                }

                // Never cancel while a gateway attempt is pending. Otherwise a
                // late, successful callback could revive a cancelled order.
                if ($lockedOrder->payments()->where('status', 'pending')->exists()) {
                    return 'payment_pending';
                }

                // Do not silently put goods back into stock after dispatch.
                if (in_array($oldStatus, ['shipped', 'delivered'], true)) {
                    return 'already_shipped';
                }

                $isRefunded = $lockedOrder->payment_status === 'refunded';

                $this->checkoutService->releaseReservedStock(
                    $lockedOrder,
                    $isRefunded
                );

                $lockedOrder->update([
                    'status' => 'cancelled',
                    'payment_status' => $isRefunded ? 'refunded' : 'failed',
                ]);
            } else {
                if (in_array($oldStatus, ['cancelled', 'delivered'], true)) {
                    return 'terminal';
                }

                if (
                    in_array($newStatus, ['processing', 'shipped', 'delivered'], true)
                    && $lockedOrder->payment_status !== 'paid'
                ) {
                    return 'payment_required';
                }

                $allowedNextStatuses = match ($oldStatus) {
                    'pending' => ['processing'],
                    'processing' => ['shipped'],
                    'shipped' => ['delivered'],
                    default => [],
                };

                if (! in_array($newStatus, $allowedNextStatuses, true)) {
                    return 'invalid_transition';
                }

                $lockedOrder->update(['status' => $newStatus]);
            }

            $note = $validated['note'] ?? null;

            if ($newStatus === 'cancelled' && ! filled($note)) {
                $note = $lockedOrder->payment_status === 'refunded'
                    ? 'سفارش پس از بازگشت کامل وجه لغو شد.'
                    : 'سفارش لغو شد و موجودی رزروشده آزاد شد.';
            }

            $lockedOrder->statusHistories()->create([
                'from_status' => $oldStatus,
                'to_status' => $newStatus,
                'changed_by' => $request->user()->id,
                'note' => $note,
            ]);

            return 'updated';
        }, 3);

        return match ($result) {
            'updated' => back()->with('success', 'وضعیت سفارش با موفقیت به‌روزرسانی شد.'),
            'repaired' => back()->with('success', 'وضعیت لغو و موجودی سفارش قدیمی بررسی و نهایی شد.'),
            'refund_required' => back()->with('error', 'سفارش پرداخت شده است؛ ابتدا بازگشت وجه را از بخش پرداخت ثبت کن.'),
            'payment_pending' => back()->with('error', 'برای این سفارش پرداخت در جریان است؛ تا تعیین تکلیف درگاه، لغو انجام نمی‌شود.'),
            'already_shipped' => back()->with('error', 'سفارش ارسال شده است؛ لغو باید از فرایند مرجوعی پیگیری شود.'),
            'payment_required' => back()->with('error', 'تا تأیید پرداخت، سفارش را نمی‌توان وارد مرحله ارسال کرد.'),
            'terminal' => back()->with('error', 'سفارش لغو یا تحویل نهایی شده و از این مسیر قابل بازگشایی نیست.'),
            'invalid_transition' => back()->with('error', 'این تغییر وضعیت با مسیر فعلی سفارش سازگار نیست.'),
            default => back()->with('info', 'وضعیت سفارش تغییری نکرد.'),
        };
    }

    public function refund(
        OrderRefundRequest $request,
        Order $order
    ): RedirectResponse {
        try {
            $payment = $this->paymentService->refund(
                $order,
                (int) $request->validated('amount')
            );
        } catch (RuntimeException $exception) {
            return back()
                ->withInput()
                ->with('error', $exception->getMessage());
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'ثبت بازگشت وجه با خطای غیرمنتظره مواجه شد؛ وضعیت پرداخت را بررسی کن.'
            );
        }

        return back()->with(
            'success',
            'درخواست بازگشت وجه ثبت شد. کد پیگیری: '.($payment->tracking_code ?: 'در انتظار به‌روزرسانی درگاه')
        );
    }
}
