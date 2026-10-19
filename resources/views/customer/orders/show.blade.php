@extends('layouts.app')
@section('title', 'جزئیات سفارش ' . $order->order_number . ' | فرزین')
@section('content')
<section class="farzin-catalog">
    <div class="farzin-container">
        <nav class="farzin-breadcrumb" aria-label="مسیر صفحه">
            <a href="{{ route('home') }}">خانه</a><span>/</span>
            <a href="{{ route('customer.orders.index') }}">سفارش‌های من</a><span>/</span>
            <span aria-current="page">{{ $order->order_number }}</span>
        </nav>
        <header class="farzin-page-head">
            <p class="farzin-kicker">FARZIN / ORDER DETAILS</p>
            <h1>جزئیات <em>سفارش</em></h1>
            <p>شماره سفارش: <strong>{{ $order->order_number }}</strong></p>
        </header>
        @include('partials.account-nav')
        <div class="mt-8 grid items-start gap-6 lg:grid-cols-[minmax(0,1fr)_320px]">
            <section class="overflow-hidden rounded-2xl border border-[var(--color-border)] bg-white">
                <header class="border-b border-[var(--color-border)] px-5 py-4 sm:px-6">
                    <h2 class="font-black">اقلام سفارش</h2>
                </header>
                @foreach($order->items as $item)
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[var(--color-border)] px-5 py-4 last:border-b-0 sm:px-6">
                        <div>
                            @if($item->product)
                                <a href="{{ route('products.show', $item->product) }}" class="font-bold hover:underline">{{ $item->product->name }}</a>
                            @else
                                <span class="font-bold">محصول حذف‌شده</span>
                            @endif
                            <p class="mt-1 text-xs text-[var(--color-text-muted)]">تعداد: {{ \\App\\Support\\JalaliCalendar::number($item->quantity) }} · هر واحد {{ \\App\\Support\\JalaliCalendar::number($item->unit_price) }} تومان</p>
                        </div>
                        <strong>{{ \\App\\Support\\JalaliCalendar::number($item->total ?? ($item->quantity * $item->unit_price)) }} تومان</strong>
                    </div>
                @endforeach
            </section>
            <aside class="rounded-2xl border border-[var(--color-border)] bg-white p-5 sm:p-6">
                <p class="farzin-kicker !mb-1">ORDER SUMMARY</p>
                <h2 class="text-lg font-black">خلاصه سفارش</h2>
                <dl class="mt-4 space-y-3 text-sm">
                    <div class="flex justify-between gap-3"><dt class="text-[var(--color-text-muted)]">وضعیت</dt><dd class="font-bold">{{ $order->status }}</dd></div>
                    <div class="flex justify-between gap-3"><dt class="text-[var(--color-text-muted)]">وضعیت پرداخت</dt><dd class="font-bold">{{ $order->payment_status }}</dd></div>
                    <div class="flex justify-between gap-3"><dt class="text-[var(--color-text-muted)]">جمع کالاها</dt><dd class="font-bold">{{ \\App\\Support\\JalaliCalendar::number($order->subtotal) }} تومان</dd></div>
                    <div class="flex justify-between gap-3"><dt class="text-[var(--color-text-muted)]">تخفیف</dt><dd class="font-bold">{{ \\App\\Support\\JalaliCalendar::number($order->discount) }} تومان</dd></div>
                    <div class="flex justify-between gap-3"><dt class="text-[var(--color-text-muted)]">ارسال</dt><dd class="font-bold">{{ \\App\\Support\\JalaliCalendar::number($order->shipping_cost) }} تومان</dd></div>
                    <div class="flex justify-between gap-3 border-t border-[var(--color-border)] pt-3"><dt class="font-black">مبلغ نهایی</dt><dd class="font-black">{{ \\App\\Support\\JalaliCalendar::number($order->total) }} تومان</dd></div>
                </dl>
                @if($order->payment_status !== 'paid' && ! $order->isCancelled())
                    <a href="{{ route('customer.payment.start', $order) }}" class="farzin-button mt-5 flex w-full justify-center">ادامه پرداخت <span aria-hidden="true">↗</span></a>
                @endif
                @if($order->shipping_address)
                    <div class="mt-5 border-t border-[var(--color-border)] pt-4">
                        <h3 class="text-sm font-black">آدرس ارسال</h3>
                        <p class="mt-2 text-xs leading-7 text-[var(--color-text-secondary)]">{{ $order->shipping_full_name }} · {{ $order->shipping_phone }}<br>{{ $order->shipping_province }}، {{ $order->shipping_city }}<br>{{ $order->shipping_address }}</p>
                    </div>
                @endif
            </aside>
        </div>
        <div class="mt-6"><a href="{{ route('customer.orders.index') }}" class="farzin-text-link">بازگشت به سفارش‌ها <span aria-hidden="true">↗</span></a></div>
    </div>
</section>
@endsection
