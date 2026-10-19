@extends('layouts.app')

@section('title', 'جزئیات سفارش ' . $order->order_number . ' | فرزین')

@section('content')
<section class="fh-page">
    <header class="fh-page__hero"><p class="fh-eyebrow fh-eyebrow--dark">FARZIN / ORDER DETAILS</p><h1>سفارش {{ $order->order_number }}</h1><p>تاریخ ثبت: {{ $order->created_at->format('Y/m/d H:i') }}</p></header>
    <div class="fh-page__inner fh-order-detail">
        <div class="fh-order-detail__summary"><div><span>وضعیت سفارش</span><strong>{{ $order->status }}</strong></div><div><span>وضعیت پرداخت</span><strong>{{ $order->payment_status }}</strong></div><div><span>مبلغ نهایی</span><strong>{{ number_format((float) $order->total) }} تومان</strong></div></div>
        <section class="fh-form-panel"><h2>اقلام سفارش</h2>
            @forelse ($order->items as $item)
                <div class="fh-order-row"><span>{{ $item->product?->name ?: 'محصول حذف‌شده' }}</span><span>تعداد: {{ $item->quantity }}</span><strong>{{ number_format((float) $item->unit_price * $item->quantity) }} تومان</strong></div>
            @empty
                <p>برای این سفارش قلمی ثبت نشده است.</p>
            @endforelse
            <div class="fh-order-totals"><p><span>جمع جزء</span><strong>{{ number_format((float) $order->subtotal) }} تومان</strong></p><p><span>هزینه ارسال</span><strong>{{ number_format((float) $order->shipping_cost) }} تومان</strong></p><p><span>مبلغ نهایی</span><strong>{{ number_format((float) $order->total) }} تومان</strong></p></div>
        </section>
        <section class="fh-form-panel"><h2>آدرس ارسال</h2><p>{{ $order->shipping_full_name }} · {{ $order->shipping_phone }}</p><p>{{ $order->shipping_country }}، {{ $order->shipping_province }}، {{ $order->shipping_city }}</p><p>{{ $order->shipping_address }}</p></section>
        @if ($order->isPaymentPending() && ! $order->isCancelled())
            <a class="fh-button fh-button--dark" href="{{ route('customer.payment.start', ['order' => $order->id]) }}">ادامه پرداخت <span aria-hidden="true">↗</span></a>
        @endif
        <a class="fh-text-link" href="{{ route('customer.orders.index') }}">بازگشت به سفارش‌ها</a>
    </div>
</section>
@endsection
