@extends('layouts.app')

@section('title', 'سفارش‌های من | فرزین')
@section('meta_description', 'تاریخچه سفارش‌های مشتری در فرزین')

@section('content')
<div class="store-transaction">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="store-account-shell">
            <main class="store-account-content lg:order-2">
                <div class="store-transaction__head">
                    <div>
                        <span class="product-v2__eyebrow">FARZIN / ORDERS</span>
                        <h1>سفارش‌های من</h1>
                        <p>وضعیت، مبلغ و جزئیات سفارش‌ها را از همین‌جا دنبال کن.</p>
                    </div>
                    @include('partials.back-link', ['href' => route('customer.dashboard'), 'label' => 'بازگشت به حساب'])
                </div>

                <section class="store-panel mt-3">
                    @if($orders->isNotEmpty())
                        <div class="store-panel__body !p-0">
                            @foreach($orders as $order)
                                @php
                                    $status = match($order->status) {
                                        'pending' => ['در انتظار پرداخت', 'text-[var(--color-warning-ink)]', 'bg-[var(--color-warning-surface)]'],
                                        'processing' => ['در حال پردازش', 'text-[var(--color-accent-700)]', 'bg-[var(--color-accent-50)]'],
                                        'shipped' => ['ارسال شده', 'text-[var(--color-brand-900)]', 'bg-[var(--color-brand-50)]'],
                                        'delivered' => ['تحویل شده', 'text-[var(--color-success-ink)]', 'bg-[var(--color-success-surface)]'],
                                        'cancelled' => ['لغو شده', 'text-[var(--color-danger-ink)]', 'bg-[var(--color-danger-surface)]'],
                                        default => [$order->status, 'text-[var(--color-text-muted)]', 'bg-[var(--color-neutral-100)]'],
                                    };
                                    $payment = match($order->payment_status) {
                                        'paid' => 'پرداخت شده',
                                        'failed' => 'پرداخت ناموفق',
                                        'refunded' => 'مرجوع شده',
                                        default => 'پرداخت نشده',
                                    };
                                @endphp

                                <a href="{{ route('customer.orders.show', $order) }}" class="flex flex-col gap-3 border-b border-[var(--color-border)] px-4 py-4 last:border-0 transition hover:bg-[var(--color-brand-50)] sm:flex-row sm:items-center">
                                    <div class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-[var(--color-brand-50)] text-xs font-black text-[var(--color-brand-900)]">#</div>
                                    <div class="min-w-0 flex-1">
                                        <strong class="block truncate text-xs font-black text-[var(--color-text-primary)]">{{ $order->order_number }}</strong>
                                        <span class="mt-1 block text-[9px] text-[var(--color-text-muted)]">{{ $order->items_count }} آیتم · {{ $order->created_at?->format('Y/m/d H:i') }} · {{ $payment }}</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3 sm:flex-col sm:items-end">
                                        <strong class="text-sm font-black text-[var(--color-brand-950)]">{{ number_format($order->total) }} <small class="text-[9px]">تومان</small></strong>
                                        <span class="rounded-full px-2.5 py-1 text-[9px] font-black {{ $status[1] }} {{ $status[2] }}">{{ $status[0] }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        @if($orders->hasPages())
                            <div class="border-t border-[var(--color-border)] p-4">
                                {{ $orders->onEachSide(1)->links() }}
                            </div>
                        @endif
                    @else
                        <div class="store-empty !border-0 !rounded-none">
                            <div class="store-empty__icon">#</div>
                            <h2>هنوز سفارشی نداری</h2>
                            <p>اولین خریدت را از فروشگاه شروع کن.</p>
                            <a href="{{ route('shop.index') }}">رفتن به فروشگاه ←</a>
                        </div>
                    @endif
                </section>
            </main>

            <aside class="lg:order-1">@include('partials.account-nav')</aside>
        </div>
    </div>
</div>
@endsection
