@extends('layouts.app')
@section('title', 'سفارش‌های من | فرزین')
@section('content')
<section class="farzin-catalog">
    <div class="farzin-container">
        <header class="farzin-page-head">
            <p class="farzin-kicker">FARZIN / ORDERS</p>
            <h1>سفارش‌های <em>من</em></h1>
            <p>وضعیت، مبالغ و جزئیات سفارش‌های ثبت‌شده را پیگیری کن.</p>
        </header>
        @include('partials.account-nav')
        <section class="mt-8 overflow-hidden rounded-2xl border border-[var(--color-border)] bg-white">
            @forelse($orders as $order)
                <a href="{{ route('customer.orders.show', $order) }}" class="flex flex-wrap items-center justify-between gap-4 border-b border-[var(--color-border)] px-5 py-5 last:border-b-0 hover:bg-[var(--color-surface-soft)] sm:px-6">
                    <div>
                        <span class="block text-sm font-black">{{ $order->order_number }}</span>
                        <span class="mt-1 block text-xs text-[var(--color-text-muted)]">ثبت‌شده در {{ $order->created_at?->format('Y/m/d H:i') }} · {{ number_format($order->items_count) }} قلم</span>
                    </div>
                    <div class="text-left">
                        <strong class="block">{{ number_format($order->total) }} تومان</strong>
                        <span class="mt-1 block text-xs text-[var(--color-text-muted)]">وضعیت: {{ $order->status }} · پرداخت: {{ $order->payment_status }}</span>
                    </div>
                </a>
            @empty
                <div class="px-6 py-12 text-center">
                    <span class="text-4xl font-black text-[var(--color-text-soft)]">F.</span>
                    <h2 class="mt-3 font-black">هنوز سفارشی ثبت نشده است</h2>
                    <p class="mt-2 text-sm text-[var(--color-text-muted)]">وقتی خریدی انجام دهی، جزئیات سفارش اینجا نمایش داده می‌شود.</p>
                    <a class="farzin-button mt-5" href="{{ route('shop.index') }}">رفتن به فروشگاه <span aria-hidden="true">↗</span></a>
                </div>
            @endforelse
        </section>
        @if($orders->hasPages())
            <nav class="farzin-pagination" aria-label="صفحه‌بندی سفارش‌ها">{{ $orders->links() }}</nav>
        @endif
    </div>
</section>
@endsection
