@extends('layouts.app')

@section('title', 'سفارش‌های من | فرزین')

@section('content')
<section class="fh-page">
    <header class="fh-page__hero"><p class="fh-eyebrow fh-eyebrow--dark">FARZIN / ORDERS</p><h1>سفارش‌های من</h1><p>وضعیت، مبلغ و جزئیات سفارش‌های ثبت‌شده را پیگیری کنید.</p></header>
    <div class="fh-page__inner">
        @forelse ($orders as $order)
            <a class="fh-order-row" href="{{ route('customer.orders.show', ['order' => $order->id]) }}">
                <span>{{ $order->order_number }}</span>
                <span>{{ $order->created_at->format('Y/m/d') }}</span>
                <span>{{ $order->items_count }} قلم</span>
                <strong>{{ number_format((float) $order->total) }} تومان</strong>
                <span>{{ $order->status }}</span>
            </a>
        @empty
            <div class="fh-empty"><strong>هنوز سفارشی ثبت نشده است.</strong><p>محصول موردنظر خود را انتخاب کنید و خرید را شروع کنید.</p><a href="{{ route('shop.index') }}">مشاهده فروشگاه ↗</a></div>
        @endforelse
        <div class="fh-pagination">{{ $orders->links() }}</div>
    </div>
</section>
@endsection
