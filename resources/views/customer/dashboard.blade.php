@extends('layouts.app')

@section('title', 'داشبورد من | Farzin')
@section('meta_description', 'مدیریت سفارش‌ها و حساب کاربری در Farzin')

@section('content')
    <div class="farzin-container py-8 sm:py-10 lg:py-12">

        <section class="relative overflow-hidden rounded-[2rem] border border-[var(--color-brand-800)] bg-[var(--color-brand-950)] text-white shadow-[var(--shadow-lg)]">
            <div class="absolute -left-20 -top-20 size-64 rounded-full bg-[var(--color-accent-600)]/10 blur-3xl"></div>
            <div class="absolute -bottom-28 right-1/3 size-72 rounded-full bg-[var(--color-earth-400)]/10 blur-3xl"></div>

            <div class="relative grid gap-8 p-7 sm:p-9 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-end">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-[0.25em] text-[var(--color-earth-200)]">
                        FARZIN / MY ACCOUNT
                    </span>

                    <h1 class="mt-3 text-3xl font-black tracking-tight sm:text-4xl">
                        سلام، {{ $user->name }} 👋
                    </h1>

                    <p class="mt-3 max-w-2xl text-sm leading-8 text-white/65">
                        سفارش‌ها، علاقه‌مندی‌ها، آدرس‌ها و اطلاعات حساب را از یک مسیر مدیریت کن.
                    </p>
                </div>

                <div class="flex flex-col gap-2 sm:flex-row lg:flex-col">
                    <a
                        href="{{ route('shop.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[var(--color-earth-100)] px-5 py-3.5 text-sm font-black text-[var(--color-earth-900)] transition hover:-translate-y-0.5 hover:bg-white"
                    >
                        ادامه خرید
                        <span aria-hidden="true">←</span>
                    </a>

                    <a
                        href="{{ route('customer.orders.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/15 bg-white/5 px-5 py-3.5 text-sm font-black text-white transition hover:bg-white/10"
                    >
                        سفارش‌های من
                        <span aria-hidden="true">↗</span>
                    </a>
                </div>
            </div>
        </section>

        <section class="mt-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-4" aria-label="خلاصه حساب">
            @php
                $accountStats = [
                    ['label' => 'سفارش‌ها', 'value' => $stats['orders'], 'href' => route('customer.orders.index'), 'accent' => false],
                    ['label' => 'علاقه‌مندی‌ها', 'value' => $stats['wishlists'], 'href' => route('customer.wishlist.index'), 'accent' => true],
                    ['label' => 'آدرس‌ها', 'value' => $stats['addresses'], 'href' => route('customer.addresses.index'), 'accent' => false],
                ];
            @endphp

            @foreach($accountStats as $stat)
                <a href="{{ $stat['href'] }}" class="group rounded-[1.5rem] border border-[var(--color-border)] bg-white p-5 shadow-[var(--shadow-xs)] transition hover:-translate-y-0.5 hover:border-[var(--color-brand-300)] hover:shadow-[var(--shadow-sm)]">
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-[10px] font-black uppercase tracking-[0.18em] text-[var(--color-text-muted)]">
                            {{ $stat['label'] }}
                        </span>
                        <span class="flex size-9 items-center justify-center rounded-xl {{ $stat['accent'] ? 'bg-[var(--color-accent-50)] text-[var(--color-accent-700)]' : 'bg-[var(--color-earth-100)] text-[var(--color-earth-800)]' }}">
                            ↗
                        </span>
                    </div>
                    <strong class="mt-3 block text-3xl font-black text-[var(--color-brand-950)]">
                        {{ number_format($stat['value']) }}
                    </strong>
                </a>
            @endforeach

            <div class="rounded-[1.5rem] border border-[var(--color-border)] bg-[var(--color-earth-50)] p-5 shadow-[var(--shadow-xs)]">
                <span class="text-[10px] font-black uppercase tracking-[0.18em] text-[var(--color-text-muted)]">ACCOUNT</span>
                <strong class="mt-3 block truncate text-sm font-black text-[var(--color-text-primary)]">
                    {{ $user->email }}
                </strong>
                <span class="mt-1 block text-[10px] text-[var(--color-text-muted)]">
                    حساب فعال
                </span>
            </div>
        </section>

        <section class="mt-6 grid gap-6 lg:grid-cols-[minmax(0,1fr)_320px]">

            <article class="overflow-hidden rounded-[1.75rem] border border-[var(--color-border)] bg-white shadow-[var(--shadow-xs)]">
                <header class="flex items-end justify-between gap-4 border-b border-[var(--color-border)] px-6 py-6">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-[var(--color-accent-600)]">
                            RECENT ORDERS
                        </span>
                        <h2 class="mt-2 text-xl font-black">سفارش‌های اخیر</h2>
                        <p class="mt-1 text-xs text-[var(--color-text-muted)]">آخرین وضعیت سفارش‌ها از اطلاعات واقعی حساب.</p>
                    </div>

                    <a href="{{ route('customer.orders.index') }}" class="text-xs font-black text-[var(--color-brand-900)] hover:text-[var(--color-accent-600)]">
                        همه سفارش‌ها ↗
                    </a>
                </header>

                @if($recentOrders->isNotEmpty())
                    <div class="divide-y divide-[var(--color-border)]">
                        @foreach($recentOrders as $order)
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
                                    'cancelled' => 'bg-[var(--color-accent-50)] text-[var(--color-accent-700)]',
                                    default => 'bg-[var(--color-brand-50)] text-[var(--color-brand-800)]',
                                };
                            @endphp

                            <a href="{{ route('customer.orders.show', $order) }}" class="group flex flex-col gap-4 px-6 py-5 transition hover:bg-[var(--color-earth-50)] sm:flex-row sm:items-center">
                                <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-[var(--color-earth-100)] text-xs font-black text-[var(--color-earth-800)]">
                                    #
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <strong class="text-xs font-black">{{ $order->order_number }}</strong>
                                        <span class="rounded-full px-2.5 py-1 text-[9px] font-black {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </div>

                                    <span class="mt-1 block text-[9px] text-[var(--color-text-muted)]">
                                        {{ $order->created_at?->format('Y/m/d H:i') }} · {{ number_format($order->items_count) }} قلم
                                    </span>
                                </div>

                                <div class="text-left">
                                    <strong class="text-xs font-black">
                                        {{ number_format($order->total) }}
                                        <span class="text-[9px] text-[var(--color-text-soft)]">تومان</span>
                                    </strong>
                                    <span class="mt-1 block text-[9px] font-bold text-[var(--color-brand-700)] group-hover:text-[var(--color-accent-600)]">
                                        مشاهده جزئیات ←
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="px-6 py-16 text-center">
                        <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-[var(--color-earth-100)] text-[var(--color-earth-800)]">🛍</div>
                        <h3 class="mt-4 text-sm font-black">هنوز سفارشی نداری</h3>
                        <p class="mt-1 text-xs leading-6 text-[var(--color-text-muted)]">از فروشگاه شروع کن و اولین انتخابت را بساز.</p>
                        <a href="{{ route('shop.index') }}" class="mt-5 inline-flex rounded-xl bg-[var(--color-accent-600)] px-5 py-3 text-xs font-black text-white hover:bg-[var(--color-accent-700)]">
                            رفتن به فروشگاه
                        </a>
                    </div>
                @endif
            </article>

            <aside class="space-y-6">
                <section class="overflow-hidden rounded-[1.75rem] border border-[var(--color-border)] bg-[var(--color-earth-50)]">
                    <header class="border-b border-[var(--color-border)] px-5 py-5">
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-[var(--color-accent-600)]">QUICK ACTIONS</span>
                        <h2 class="mt-2 text-lg font-black">دسترسی سریع</h2>
                    </header>

                    <div class="space-y-2 p-4">
                        @foreach([
                            ['title' => 'علاقه‌مندی‌ها', 'href' => route('customer.wishlist.index')],
                            ['title' => 'آدرس‌های من', 'href' => route('customer.addresses.index')],
                            ['title' => 'تنظیمات حساب', 'href' => route('customer.settings.index')],
                            ['title' => 'فروشگاه', 'href' => route('shop.index')],
                        ] as $action)
                            <a href="{{ $action['href'] }}" class="flex items-center justify-between rounded-xl bg-white px-4 py-3.5 text-xs font-black text-[var(--color-text-secondary)] transition hover:bg-[var(--color-brand-900)] hover:text-white">
                                <span>{{ $action['title'] }}</span>
                                <span>←</span>
                            </a>
                        @endforeach
                    </div>
                </section>

                <section class="rounded-[1.75rem] border border-[var(--color-border)] bg-white p-5">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-[var(--color-accent-600)]">ACCOUNT</span>
                    <h2 class="mt-2 text-lg font-black">خروج امن</h2>
                    <p class="mt-2 text-[10px] leading-6 text-[var(--color-text-muted)]">
                        پایان جلسه فقط از مسیر رسمی خروج انجام می‌شود.
                    </p>

                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                        @csrf
                        <button type="submit" class="w-full rounded-xl border border-[var(--color-accent-100)] bg-[var(--color-accent-50)] px-4 py-3 text-xs font-black text-[var(--color-accent-700)] transition hover:bg-[var(--color-accent-100)]">
                            خروج از حساب
                        </button>
                    </form>
                </section>
            </aside>
        </section>
    </div>
@endsection
