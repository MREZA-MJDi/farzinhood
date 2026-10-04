@extends('layouts.app')

@section('title', 'سفارش ' . $order->order_number . ' | فرزین')

@section('content')
<div class="store-transaction">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="store-account-shell">
            <main class="store-account-content lg:order-2">
                <div class="store-transaction__head">
                    <div>
                        <span class="product-v2__eyebrow">FARZIN / ORDER</span>
                        <h1>{{ $order->order_number }}</h1>
                        <p>{{ $order->created_at?->format('Y/m/d H:i') }}</p>
                    </div>
                    @include('partials.back-link', ['href' => route('customer.orders.index'), 'label' => 'بازگشت به سفارش‌ها'])
                </div>

                <section class="store-panel mt-3">
                    <div class="store-panel__head">
                        <strong>وضعیت سفارش</strong>
                        <span class="rounded-full bg-[var(--color-brand-50)] px-2.5 py-1 text-[9px] font-black text-[var(--color-brand-900)]">{{ match($order->status) {
                            'pending' => 'در انتظار پرداخت',
                            'processing' => 'در حال پردازش',
                            'shipped' => 'ارسال شده',
                            'delivered' => 'تحویل شده',
                            'cancelled' => 'لغو شده',
                            default => $order->status,
                        } }}</span>
                    </div>

                    <div class="store-panel__body">
                        @if($order->payment_status !== 'paid' && $order->status === 'pending')
                            <a href="{{ route('customer.payment.start', $order) }}" class="inline-flex min-h-11 items-center justify-center rounded-xl bg-[var(--color-accent-600)] px-5 text-xs font-black text-white transition hover:bg-[var(--color-accent-700)]">ادامه پرداخت</a>
                        @endif

                        <div class="mt-3">
                            @foreach($order->statusHistories->sortBy('created_at') as $history)
                                <div class="flex gap-3 border-b border-[var(--color-border)] py-3 last:border-0">
                                    <span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-[var(--color-accent-600)]"></span>
                                    <div>
                                        <strong class="text-xs font-black text-[var(--color-text-primary)]">{{ $history->to_status }}</strong>
                                        <p class="mt-1 text-[10px] leading-6 text-[var(--color-text-muted)]">{{ $history->note ?: 'بروزرسانی وضعیت سفارش' }} · {{ $history->created_at?->format('Y/m/d H:i') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <section class="store-panel mt-3">
                    <div class="store-panel__head"><strong>اقلام سفارش</strong><span>{{ $order->items->count() }} آیتم</span></div>
                    <div class="store-panel__body">
                        @foreach($order->items as $item)
                            <div class="store-item">
                                <div class="store-item__media grid place-items-center bg-[var(--color-neutral-100)] text-[10px] text-[var(--color-text-soft)]">#</div>
                                <div class="min-w-0">
                                    @if($item->product)
                                        <a class="block text-xs font-black text-[var(--color-text-primary)] hover:text-[var(--color-accent-600)]" href="{{ route('products.show', $item->product) }}">{{ $item->product_name }}</a>
                                    @else
                                        <strong class="text-xs font-black text-[var(--color-text-primary)]">{{ $item->product_name }}</strong>
                                    @endif
                                    <p>{{ $item->quantity }} عدد · {{ $item->product_sku ? 'SKU / ' . $item->product_sku : 'بدون SKU' }}</p>
                                </div>
                                <div class="store-item__price">{{ number_format($item->total) }}<small>تومان</small></div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="store-panel mt-3">
                    <div class="store-panel__head"><strong>آدرس ارسال</strong><span>اطلاعات ثبت‌شده سفارش</span></div>
                    <div class="store-panel__body text-[11px] leading-7 text-[var(--color-text-secondary)]">
                        <strong class="text-[var(--color-text-primary)]">{{ $order->shipping_full_name }}</strong>
                        <div>{{ $order->shipping_phone }}</div>
                        <div>{{ $order->shipping_province }}، {{ $order->shipping_city }}</div>
                        <div>{{ $order->shipping_address }}</div>
                        @if($order->shipping_postal_code)
                            <div>کد پستی: <span dir="ltr">{{ $order->shipping_postal_code }}</span></div>
                        @endif
                    </div>
                </section>
            </main>

            <aside class="lg:order-1">
                @include('partials.account-nav')

                <div class="store-summary mt-3">
                    <h2>جمع سفارش</h2>
                    <div class="store-summary__row"><span>جمع کالاها</span><strong>{{ number_format($order->subtotal) }}</strong></div>
                    <div class="store-summary__row"><span>تخفیف</span><strong>{{ $order->discount ? number_format($order->discount) : '—' }}</strong></div>
                    <div class="store-summary__row"><span>ارسال</span><strong>{{ $order->shipping_cost ? number_format($order->shipping_cost) : 'رایگان' }}</strong></div>
                    <div class="store-summary__total"><span>مبلغ نهایی</span><div class="text-left"><strong>{{ number_format($order->total) }}</strong><small>تومان</small></div></div>
                    <p class="mt-2 text-center text-[9px] text-[var(--color-text-muted)]">وضعیت پرداخت: {{ $order->payment_status === 'paid' ? 'پرداخت شده' : 'در انتظار پرداخت' }}</p>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection
