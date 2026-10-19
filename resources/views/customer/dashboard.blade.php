@extends('layouts.app')
@section('title', 'MY ACCOUNT | فرزین')
@section('content')
<section class="farzin-catalog">
    <div class="farzin-container">
        <header class="farzin-page-head">
            <p class="farzin-kicker">FARZIN / CUSTOMER ACCOUNT</p>
            <h1>MY ACCOUNT</h1>
            <p>سلام {{ auth()->user()->name }}؛ سفارش‌ها و اطلاعات حسابت را از اینجا مدیریت کن.</p>
        </header>

        @include('partials.account-nav')

        <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <a href="{{ route('customer.orders.index') }}" class="rounded-2xl border border-[var(--color-border)] bg-white p-5 transition hover:-translate-y-0.5 hover:shadow-lg">
                <span class="text-xs text-[var(--color-text-muted)]">سفارش‌ها</span>
                <strong class="mt-3 block text-3xl font-black">{{ number_format($orderCount) }}</strong>
                <span class="mt-3 block text-sm font-bold">مشاهده سفارش‌ها ←</span>
            </a>
            <a href="{{ route('customer.wishlist.index') }}" class="rounded-2xl border border-[var(--color-border)] bg-white p-5 transition hover:-translate-y-0.5 hover:shadow-lg">
                <span class="text-xs text-[var(--color-text-muted)]">علاقه‌مندی‌ها</span>
                <strong class="mt-3 block text-3xl font-black">{{ number_format($wishlistCount) }}</strong>
                <span class="mt-3 block text-sm font-bold">محصولات ذخیره‌شده ←</span>
            </a>
            <a href="{{ route('customer.addresses.index') }}" class="rounded-2xl border border-[var(--color-border)] bg-white p-5 transition hover:-translate-y-0.5 hover:shadow-lg">
                <span class="text-xs text-[var(--color-text-muted)]">آدرس‌های ارسال</span>
                <strong class="mt-3 block text-3xl font-black">{{ number_format($addressCount) }}</strong>
                <span class="mt-3 block text-sm font-bold">مدیریت آدرس‌ها ←</span>
            </a>
        </div>

        <section class="mt-10 overflow-hidden rounded-2xl border border-[var(--color-border)] bg-white">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[var(--color-border)] px-5 py-4 sm:px-6">
                <div>
                    <p class="farzin-kicker !mb-1">YOUR ACTIVITY</p>
                    <h2 class="text-lg font-black">آخرین سفارش‌ها</h2>
                </div>
                <a class="farzin-text-link" href="{{ route('customer.orders.index') }}">همه سفارش‌ها <span aria-hidden="true">↗</span></a>
            </div>
            @forelse($recentOrders as $order)
                <a href="{{ route('customer.orders.show', $order) }}" class="flex flex-wrap items-center justify-between gap-3 border-b border-[var(--color-border)] px-5 py-4 last:border-b-0 hover:bg-[var(--color-surface-soft)] sm:px-6">
                    <div class="min-w-0">
                        <span class="block text-sm font-bold">{{ $order->order_number }}</span>
                        <span class="mt-1 block text-xs text-[var(--color-text-muted)]">{{ $order->created_at?->format('Y/m/d') }} · {{ number_format($order->items_count) }} قلم</span>
                    </div>
                    <div class="text-left">
                        <strong class="block text-sm">{{ number_format($order->total) }} تومان</strong>
                        <span class="mt-1 block text-xs text-[var(--color-text-muted)]">{{ $order->status }}</span>
                    </div>
                </a>
            @empty
                <div class="px-6 py-12 text-center">
                    <span class="text-4xl font-black text-[var(--color-text-soft)]">F.</span>
                    <h3 class="mt-3 font-black">هنوز سفارشی ثبت نکرده‌ای</h3>
                    <p class="mt-2 text-sm text-[var(--color-text-muted)]">محصول موردنظرت را پیدا کن و اولین سفارش را ثبت کن.</p>
                    <a class="farzin-button mt-5" href="{{ route('shop.index') }}">رفتن به فروشگاه <span aria-hidden="true">↗</span></a>
                </div>
            @endforelse
        </section>
    </div>
</section>
@endsection
