@extends('layouts.app')

@section('title', 'حساب من | فرزین')
@section('meta_description', 'مدیریت سفارش‌ها، علاقه‌مندی‌ها و آدرس‌های شما در فرزین')

@section('content')
<div class="store-transaction">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="store-account-shell">
            <div class="lg:order-2">
                <header class="store-account-hero">
                    <span class="product-v2__eyebrow text-white/55">FARZIN / MY ACCOUNT</span>
                    <h1>سلام، {{ auth()->user()->name }} 👋</h1>
                    <p>همه مسیرهای خریدت یکجا: سفارش‌ها، علاقه‌مندی‌ها، آدرس‌ها و تنظیمات حساب.</p>
                    <div class="store-account-hero__actions">
                        <a href="{{ route('shop.index') }}">ادامه خرید</a>
                        <a href="{{ route('customer.orders.index') }}">مشاهده سفارش‌ها</a>
                    </div>
                </header>

                <div class="mt-3 grid gap-2 sm:grid-cols-3">
                    <div class="store-panel p-4">
                        <span class="text-[10px] text-[var(--color-text-muted)]">سفارش‌ها</span>
                        <strong class="mt-1 block text-2xl font-black text-[var(--color-brand-950)]">{{ number_format($orderCount) }}</strong>
                    </div>
                    <div class="store-panel p-4">
                        <span class="text-[10px] text-[var(--color-text-muted)]">علاقه‌مندی‌ها</span>
                        <strong class="mt-1 block text-2xl font-black text-[var(--color-brand-950)]">{{ number_format($wishlistCount) }}</strong>
                    </div>
                    <div class="store-panel p-4">
                        <span class="text-[10px] text-[var(--color-text-muted)]">آدرس‌ها</span>
                        <strong class="mt-1 block text-2xl font-black text-[var(--color-brand-950)]">{{ number_format($addressCount) }}</strong>
                    </div>
                </div>

                <section class="store-panel mt-3">
                    <div class="store-panel__head">
                        <strong>سفارش‌های اخیر</strong>
                        <a class="text-[10px] font-black text-[var(--color-accent-600)]" href="{{ route('customer.orders.index') }}">همه سفارش‌ها ←</a>
                    </div>

                    @if($recentOrders->isNotEmpty())
                        <div class="store-panel__body !p-0">
                            @foreach($recentOrders as $order)
                                <a href="{{ route('customer.orders.show', $order) }}" class="flex items-center gap-3 border-b border-[var(--color-border)] px-4 py-4 last:border-0 transition hover:bg-[var(--color-brand-50)]">
                                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-[var(--color-brand-50)] text-xs font-black text-[var(--color-brand-900)]">#</span>
                                    <div class="min-w-0 flex-1">
                                        <strong class="block truncate text-xs font-black text-[var(--color-text-primary)]">{{ $order->order_number }}</strong>
                                        <span class="mt-1 block text-[9px] text-[var(--color-text-muted)]">{{ $order->items_count }} آیتم · {{ $order->created_at?->format('Y/m/d H:i') }}</span>
                                    </div>
                                    <div class="text-left">
                                        <strong class="block text-xs font-black text-[var(--color-brand-950)]">{{ number_format($order->total) }}</strong>
                                        <span class="mt-1 block text-[9px] font-bold {{ $order->status === 'delivered' ? 'text-[var(--color-success-ink)]' : ($order->status === 'cancelled' ? 'text-[var(--color-danger-ink)]' : 'text-[var(--color-accent-600)]') }}">
                                            {{ match($order->status) {
                                                'pending' => 'در انتظار پرداخت',
                                                'processing' => 'در حال پردازش',
                                                'shipped' => 'ارسال شده',
                                                'delivered' => 'تحویل شده',
                                                'cancelled' => 'لغو شده',
                                                default => $order->status,
                                            } }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="store-empty !border-0 !rounded-none">
                            <div class="store-empty__icon">🛍</div>
                            <h2>هنوز سفارشی ثبت نکردی</h2>
                            <p>از فروشگاه شروع کن و اولین خریدت را انجام بده.</p>
                            <a href="{{ route('shop.index') }}">رفتن به فروشگاه ←</a>
                        </div>
                    @endif
                </section>
            </div>

            <aside class="lg:order-1">
                @include('partials.account-nav')
            </aside>
        </div>
    </div>
</div>
@endsection
