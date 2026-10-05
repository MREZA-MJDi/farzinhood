@extends('layouts.app')

@section('title', 'جزئیات سفارش ' . $order->order_number . ' | Farzin')
@section('meta_description', 'جزئیات و وضعیت سفارش ' . $order->order_number . ' در فرزین')

@section('content')
<section class="farzin-container py-8 sm:py-10 lg:py-12">
    <div class="flex flex-wrap items-center gap-2 text-[10px] text-[var(--color-text-muted)]">
        <a href="{{ route('customer.orders.index') }}" class="transition hover:text-[var(--color-accent-600)]">سفارش‌های من</a>
        <span>/</span>
        <strong class="text-[var(--color-text-secondary)]">{{ $order->order_number }}</strong>
    </div>

    @php
        $statusLabel = match($order->status) {
            'pending' => 'در انتظار پرداخت',
            'processing' => 'در حال پردازش',
            'shipped' => 'ارسال شده',
            'delivered' => 'تحویل شده',
            'cancelled' => 'لغو شده',
            default => $order->status,
        };

        $statusClass = match($order->status) {
            'delivered' => 'bg-[var(--color-success-50)] text-[var(--color-success-700)]',
            'cancelled' => 'bg-[var(--color-danger-50)] text-[var(--color-danger-700)]',
            'shipped' => 'bg-[var(--color-info-50)] text-[var(--color-info-700)]',
            default => 'bg-[var(--color-earth-100)] text-[var(--color-earth-800)]',
        };

        $paymentLabel = match($order->payment_status) {
            'paid' => 'پرداخت شده',
            'pending' => 'در انتظار پرداخت',
            'failed' => 'پرداخت ناموفق',
            'refunded' => 'بازگشت وجه',
            default => $order->payment_status,
        };
    @endphp

    <header class="mt-6 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <span class="farzin-eyebrow">FARZIN / ORDER DETAILS</span>
            <h1 class="farzin-page-title mt-3">{{ $order->order_number }}</h1>
            <p class="farzin-section-description mt-3">
                ثبت شده در {{ $order->created_at?->format('Y/m/d H:i') }}
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <span class="rounded-full px-4 py-2 text-xs font-black {{ $statusClass }}">{{ $statusLabel }}</span>
            <span class="rounded-full border border-[var(--color-border)] bg-white px-4 py-2 text-xs font-black text-[var(--color-text-secondary)]">{{ $paymentLabel }}</span>
        </div>
    </header>

    @if($order->payment_status !== 'paid' && $order->status === 'pending')
        <div class="mt-7 flex flex-col gap-4 rounded-[1.75rem] border border-[var(--color-earth-200)] bg-[var(--color-earth-50)] p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
            <div>
                <span class="text-[9px] font-black uppercase tracking-[0.2em] text-[var(--color-earth-700)]">ACTION REQUIRED</span>
                <h2 class="mt-2 text-base font-black">پرداخت این سفارش هنوز تکمیل نشده</h2>
                <p class="mt-1 text-xs leading-6 text-[var(--color-text-secondary)]">برای ادامه پردازش سفارش، پرداخت را کامل کن.</p>
            </div>

            <a href="{{ route('customer.payment.start', $order) }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-900)] px-5 py-3.5 text-xs font-black text-white transition hover:bg-[var(--color-brand-950)]">
                ادامه پرداخت
                <span aria-hidden="true">←</span>
            </a>
        </div>
    @endif

    <div class="mt-7 grid gap-6 xl:grid-cols-[minmax(0,1fr)_340px]">
        <div class="space-y-6">
            <section class="overflow-hidden rounded-[1.75rem] border border-[var(--color-border)] bg-white shadow-[var(--shadow-xs)]">
                <header class="border-b border-[var(--color-border)] bg-[var(--color-earth-50)] px-5 py-5 sm:px-6">
                    <span class="text-[9px] font-black uppercase tracking-[0.2em] text-[var(--color-earth-700)]">ORDER ITEMS</span>
                    <h2 class="mt-2 text-lg font-black">محصولات سفارش</h2>
                </header>

                <div class="divide-y divide-[var(--color-border)]">
                    @foreach($order->items as $item)
                        <div class="flex gap-4 px-5 py-5 sm:px-6">
                            <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-[var(--color-brand-50)] text-[10px] font-black text-[var(--color-brand-900)]">
                                {{ number_format($item->quantity) }}×
                            </div>

                            <div class="min-w-0 flex-1">
                                @if($item->product)
                                    <a href="{{ route('products.show', $item->product) }}" class="text-sm font-black text-[var(--color-text-primary)] hover:text-[var(--color-accent-600)]">
                                        {{ $item->product_name }}
                                    </a>
                                @else
                                    <span class="text-sm font-black">{{ $item->product_name }}</span>
                                @endif

                                @if($item->product_sku)
                                    <span class="mt-1 block font-mono text-[9px] text-[var(--color-text-soft)]" dir="ltr">{{ $item->product_sku }}</span>
                                @endif

                                <span class="mt-2 block text-[10px] text-[var(--color-text-muted)]">
                                    {{ number_format($item->unit_price) }} تومان × {{ number_format($item->quantity) }}
                                </span>
                            </div>

                            <strong class="shrink-0 text-sm font-black text-[var(--color-brand-950)]">
                                {{ number_format($item->total) }}
                                <small class="text-[9px] font-bold text-[var(--color-text-muted)]">تومان</small>
                            </strong>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="rounded-[1.75rem] border border-[var(--color-border)] bg-white p-5 shadow-[var(--shadow-xs)] sm:p-6">
                <span class="text-[9px] font-black uppercase tracking-[0.2em] text-[var(--color-earth-700)]">SHIPPING</span>
                <h2 class="mt-2 text-lg font-black">آدرس ارسال</h2>

                <div class="mt-5 grid gap-5 sm:grid-cols-2">
                    <div>
                        <span class="text-[9px] font-black text-[var(--color-text-muted)]">گیرنده</span>
                        <p class="mt-1 text-sm font-bold">{{ $order->shipping_full_name }}</p>
                    </div>
                    <div>
                        <span class="text-[9px] font-black text-[var(--color-text-muted)]">تلفن</span>
                        <p class="mt-1 text-sm font-bold" dir="ltr">{{ $order->shipping_phone }}</p>
                    </div>
                    <div>
                        <span class="text-[9px] font-black text-[var(--color-text-muted)]">موقعیت</span>
                        <p class="mt-1 text-sm font-bold">{{ $order->shipping_province }}، {{ $order->shipping_city }}</p>
                    </div>
                    @if($order->shipping_postal_code)
                        <div>
                            <span class="text-[9px] font-black text-[var(--color-text-muted)]">کد پستی</span>
                            <p class="mt-1 font-mono text-sm font-bold">{{ $order->shipping_postal_code }}</p>
                        </div>
                    @endif
                    <div class="sm:col-span-2">
                        <span class="text-[9px] font-black text-[var(--color-text-muted)]">آدرس کامل</span>
                        <p class="mt-1 text-sm leading-7 text-[var(--color-text-secondary)]">
                            {{ $order->shipping_address }}
                        </p>
                    </div>
                </div>
            </section>

            @if($order->statusHistories->isNotEmpty())
                <section class="rounded-[1.75rem] border border-[var(--color-border)] bg-white p-5 shadow-[var(--shadow-xs)] sm:p-6">
                    <span class="text-[9px] font-black uppercase tracking-[0.2em] text-[var(--color-earth-700)]">ORDER TIMELINE</span>
                    <h2 class="mt-2 text-lg font-black">روند سفارش</h2>

                    <div class="mt-6 space-y-5">
                        @foreach($order->statusHistories->sortBy('created_at') as $history)
                            @php
                                $historyLabel = match($history->to_status) {
                                    'pending' => 'در انتظار پرداخت',
                                    'processing' => 'در حال پردازش',
                                    'shipped' => 'ارسال شده',
                                    'delivered' => 'تحویل شده',
                                    'cancelled' => 'لغو شده',
                                    default => $history->to_status,
                                };
                            @endphp

                            <div class="relative flex gap-4">
                                <div class="flex shrink-0 flex-col items-center">
                                    <span class="flex size-8 items-center justify-center rounded-full bg-[var(--color-brand-900)] text-[10px] font-black text-white">✓</span>
                                    @unless($loop->last)
                                        <span class="mt-2 h-full w-px bg-[var(--color-border)]"></span>
                                    @endunless
                                </div>

                                <div class="pb-1">
                                    <strong class="text-sm font-black">{{ $historyLabel }}</strong>
                                    <span class="mt-1 block text-[9px] text-[var(--color-text-muted)]">{{ $history->created_at?->format('Y/m/d H:i') }}</span>
                                    @if($history->changedBy)
                                        <span class="mt-1 block text-[9px] text-[var(--color-text-soft)]">ثبت توسط {{ $history->changedBy->name }}</span>
                                    @endif
                                    @if($history->note)
                                        <p class="mt-2 text-xs leading-6 text-[var(--color-text-secondary)]">{{ $history->note }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        <aside class="xl:sticky xl:top-28 xl:self-start">
            <section class="overflow-hidden rounded-[1.75rem] border border-[var(--color-brand-900)] bg-[var(--color-brand-950)] text-white shadow-[var(--shadow-lg)]">
                <div class="relative p-6">
                    <div class="absolute -left-16 -top-16 size-40 rounded-full bg-[var(--color-earth-400)]/15 blur-3xl"></div>

                    <span class="relative text-[9px] font-black uppercase tracking-[0.2em] text-[var(--color-earth-200)]">ORDER SUMMARY</span>
                    <h2 class="relative mt-2 text-lg font-black">خلاصه مالی</h2>

                    <div class="relative mt-6 space-y-4 border-t border-white/10 pt-5">
                        <div class="flex justify-between gap-4 text-xs text-white/60">
                            <span>مبلغ کالاها</span>
                            <strong class="text-white">{{ number_format($order->subtotal) }} تومان</strong>
                        </div>

                        @if($order->discount > 0)
                            <div class="flex justify-between gap-4 text-xs text-white/60">
                                <span>تخفیف</span>
                                <strong class="text-[var(--color-earth-200)]">− {{ number_format($order->discount) }} تومان</strong>
                            </div>
                        @endif

                        <div class="flex justify-between gap-4 text-xs text-white/60">
                            <span>ارسال</span>
                            <strong class="text-white">
                                @if($order->shipping_cost > 0)
                                    {{ number_format($order->shipping_cost) }} تومان
                                @else
                                    رایگان
                                @endif
                            </strong>
                        </div>
                    </div>

                    <div class="relative mt-6 border-t border-white/10 pt-5">
                        <span class="text-[9px] text-white/50">مبلغ نهایی</span>
                        <div class="mt-1 flex items-end justify-between gap-3">
                            <strong class="text-2xl font-black">{{ number_format($order->total) }}</strong>
                            <span class="text-[10px] text-white/50">تومان</span>
                        </div>
                    </div>

                    <a href="{{ route('customer.orders.index') }}" class="relative mt-6 inline-flex w-full items-center justify-center rounded-xl border border-white/15 bg-white/5 px-4 py-3.5 text-xs font-black text-white transition hover:bg-white/10">
                        بازگشت به سفارش‌ها
                    </a>
                </div>
            </section>
        </aside>
    </div>
</section>
@endsection
