@extends('layouts.app')

@section('title', 'حساب من | فرزین')

@section('content')
<section class="fh-page">
    <header class="fh-page__hero">
        <p class="fh-eyebrow fh-eyebrow--dark">FARZIN / MY ACCOUNT</p>
        <h1>MY ACCOUNT</h1>
        <p>سلام {{ auth()->user()->name }}؛ سفارش‌ها و اطلاعات حساب خود را از اینجا مدیریت کنید.</p>
    </header>
    <div class="fh-page__inner">
        <div class="fh-account-stats">
            <a href="{{ route('customer.orders.index') }}"><span>سفارش‌ها</span><strong>{{ $orderCount }}</strong><small>مشاهده وضعیت سفارش‌ها ↗</small></a>
            <a href="{{ route('customer.wishlist.index') }}"><span>علاقه‌مندی‌ها</span><strong>{{ $wishlistCount }}</strong><small>محصولات ذخیره‌شده ↗</small></a>
            <a href="{{ route('customer.addresses.index') }}"><span>آدرس‌ها</span><strong>{{ $addressCount }}</strong><small>مدیریت نشانی‌ها ↗</small></a>
        </div>
        <div class="fh-account-links">
            <a href="{{ route('customer.settings.index') }}">اطلاعات حساب <span>↗</span></a>
            <a href="{{ route('customer.addresses.index') }}">آدرس‌های ارسال <span>↗</span></a>
            <a href="{{ route('customer.cart.index') }}">سبد خرید <span>↗</span></a>
        </div>
        <div class="fh-section__heading"><div><p class="fh-eyebrow fh-eyebrow--dark">RECENT ACTIVITY</p><h2>آخرین سفارش‌ها</h2></div><a class="fh-text-link" href="{{ route('customer.orders.index') }}">همه سفارش‌ها ↗</a></div>
        @forelse ($recentOrders as $order)
            <a class="fh-order-row" href="{{ route('customer.orders.show', ['order' => $order->id]) }}"><span>{{ $order->order_number }}</span><span>{{ $order->created_at->format('Y/m/d') }}</span><span>{{ $order->items_count }} قلم</span><strong>{{ number_format((float) $order->total) }} تومان</strong><span>{{ $order->status }}</span></a>
        @empty
            <div class="fh-empty">هنوز سفارشی ثبت نشده است. <a href="{{ route('shop.index') }}">شروع خرید ↗</a></div>
        @endforelse
    </div>
</section>
@endsection
