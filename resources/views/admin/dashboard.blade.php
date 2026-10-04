@extends('layouts.admin')

@section('title', 'داشبورد')

@section('content')
    @php
        $statusLabels = [
            'pending' => 'در انتظار',
            'processing' => 'در حال پردازش',
            'shipped' => 'ارسال شده',
            'delivered' => 'تحویل شده',
            'cancelled' => 'لغو شده',
        ];

        $statusClasses = [
            'pending' => 'bg-amber-50 text-amber-700',
            'processing' => 'bg-blue-50 text-blue-700',
            'shipped' => 'bg-indigo-50 text-indigo-700',
            'delivered' => 'bg-emerald-50 text-emerald-700',
            'cancelled' => 'bg-red-50 text-red-700',
        ];

        $movementLabels = [
            'sale' => 'فروش',
            'restock' => 'تأمین موجودی',
            'return' => 'مرجوعی',
            'adjustment' => 'اصلاح موجودی',
        ];

        $movementClasses = [
            'sale' => 'bg-red-50 text-red-700',
            'restock' => 'bg-emerald-50 text-emerald-700',
            'return' => 'bg-blue-50 text-blue-700',
            'adjustment' => 'bg-amber-50 text-amber-700',
        ];
    @endphp

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-black text-[var(--color-text-primary)]">
                    داشبورد
                </h1>

                <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                    نمای کلی عملکرد فروشگاه و وضعیت سیستم
                </p>
            </div>

            <div class="rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm font-bold text-[var(--color-text-secondary)] shadow-sm">
                {{ now()->locale('fa')->translatedFormat('l، j F Y') }}
            </div>
        </div>


        {{-- Sales KPIs --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Today Sales --}}
            <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

                <div class="flex items-start justify-between gap-3">

                    <div>
                        <p class="text-sm text-[var(--color-text-secondary)]">
                            فروش امروز
                        </p>

                        <p class="mt-2 text-2xl font-black text-[var(--color-text-primary)]">
                            {{ number_format($todaySales) }}
                        </p>

                        <p class="mt-1 text-xs text-[var(--color-text-muted)]">
                            تومان
                        </p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[var(--color-brand-50)] text-[var(--color-brand-700)]">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2-.448 2-1s-.89-1-2-1-2 .448-2 1 .89 1 2 1zm0 0V5m0 14v-2m8-5a8 8 0 11-16 0 8 8 0 0116 0z"/>
                        </svg>
                    </div>

                </div>

                <div class="mt-4 flex items-center justify-between text-xs">
                    <span class="text-[var(--color-text-muted)]">
                        {{ number_format($todayOrderCount) }} سفارش پرداخت‌شده
                    </span>

                    <span class="font-bold text-[var(--color-brand-700)]">
                        میانگین {{ number_format($todayAverageOrderValue) }} تومان
                    </span>
                </div>

            </div>


            {{-- Month Sales --}}
            <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

                <div class="flex items-start justify-between gap-3">

                    <div>
                        <p class="text-sm text-[var(--color-text-secondary)]">
                            فروش این ماه
                        </p>

                        <p class="mt-2 text-2xl font-black text-[var(--color-text-primary)]">
                            {{ number_format($monthSales) }}
                        </p>

                        <p class="mt-1 text-xs text-[var(--color-text-muted)]">
                            تومان
                        </p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M3 3v18h18M7 15l4-4 3 3 6-7"/>
                        </svg>
                    </div>

                </div>

                <div class="mt-4 flex items-center justify-between text-xs">

                    <span class="text-[var(--color-text-muted)]">
                        {{ number_format($monthOrderCount) }} سفارش پرداخت‌شده
                    </span>

                    <span class="{{ $monthGrowth >= 0 ? 'text-emerald-700' : 'text-red-700' }} font-bold">
                        {{ $monthGrowth >= 0 ? '+' : '' }}{{ number_format($monthGrowth, 1) }}٪
                    </span>

                </div>

            </div>


            {{-- Year Sales --}}
            <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

                <div class="flex items-start justify-between gap-3">

                    <div>
                        <p class="text-sm text-[var(--color-text-secondary)]">
                            فروش امسال
                        </p>

                        <p class="mt-2 text-2xl font-black text-[var(--color-text-primary)]">
                            {{ number_format($yearSales) }}
                        </p>

                        <p class="mt-1 text-xs text-[var(--color-text-muted)]">
                            تومان
                        </p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M4 19V5m0 14h16M8 16l3-4 3 2 4-6"/>
                        </svg>
                    </div>

                </div>

                <div class="mt-4 text-xs text-[var(--color-text-muted)]">
                    کل فروش ثبت‌شده سال جاری
                </div>

            </div>


            {{-- Customers --}}
            <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

                <div class="flex items-start justify-between gap-3">

                    <div>
                        <p class="text-sm text-[var(--color-text-secondary)]">
                            مشتریان
                        </p>

                        <p class="mt-2 text-2xl font-black text-[var(--color-text-primary)]">
                            {{ number_format($customersCount) }}
                        </p>

                        <p class="mt-1 text-xs text-[var(--color-text-muted)]">
                            مشتری ثبت‌نام‌شده
                        </p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-purple-50 text-purple-700">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM4 21a8 8 0 0116 0"/>
                        </svg>
                    </div>

                </div>

                <div class="mt-4 flex items-center justify-between text-xs">

                    <span class="text-[var(--color-text-muted)]">
                        فعال: {{ number_format($activeCustomersCount) }}
                    </span>

                    <span class="font-bold text-[var(--color-brand-700)]">
                        +{{ number_format($newCustomersThisMonth) }} این ماه
                    </span>

                </div>

            </div>

        </div>


        {{-- Sales Chart --}}
        <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                        نمودار فروش
                    </h2>

                    <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                        روند فروش پرداخت‌شده فروشگاه
                    </p>
                </div>

                <div class="flex rounded-xl bg-[var(--color-neutral-100)] p-1">

                    <button type="button"
                            data-range="daily"
                            class="dashboard-chart-tab rounded-lg px-4 py-2 text-xs font-bold transition">
                        روزانه
                    </button>

                    <button type="button"
                            data-range="monthly"
                            class="dashboard-chart-tab rounded-lg px-4 py-2 text-xs font-bold transition">
                        ماهانه
                    </button>

                    <button type="button"
                            data-range="yearly"
                            class="dashboard-chart-tab rounded-lg px-4 py-2 text-xs font-bold transition">
                        سالانه
                    </button>

                </div>

            </div>


            <div class="relative mt-6 h-[360px]">
                <canvas id="salesChart"></canvas>
            </div>

        </div>


        {{-- Orders + Inventory --}}
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

            {{-- Order Status --}}
            <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

                <div>
                    <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                        وضعیت سفارش‌ها
                    </h2>

                    <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                        توزیع سفارش‌ها بر اساس وضعیت
                    </p>
                </div>

                <div class="relative mx-auto mt-6 h-64 max-w-xs">
                    <canvas id="orderStatusChart"></canvas>
                </div>

                <div class="mt-6 space-y-3">

                    @foreach($orderStatusOverview as $status => $count)

                        <div class="flex items-center justify-between">

                            <div class="flex items-center gap-2">

                                <span class="h-2.5 w-2.5 rounded-full
                                    @switch($status)
                                @case('pending') bg-amber-500 @break
                                @case('processing') bg-blue-500 @break
                                @case('shipped') bg-indigo-500 @break
                                @case('delivered') bg-emerald-500 @break
                                @default bg-red-500
                                    @endswitch
                                    "></span>

                                <span class="text-sm text-[var(--color-text-secondary)]">
                                    {{ $statusLabels[$status] ?? $status }}
                                </span>

                            </div>

                            <span class="font-black text-[var(--color-text-primary)]">
                                {{ number_format($count) }}
                            </span>

                        </div>

                    @endforeach

                </div>

            </div>


            {{-- Inventory --}}
            <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm xl:col-span-2">

                <div class="flex items-center justify-between gap-3">

                    <div>
                        <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                            وضعیت موجودی
                        </h2>

                        <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                            محصولات نیازمند توجه
                        </p>
                    </div>

                    <a href="{{ route('admin.inventory.index') }}"
                       class="text-xs font-bold text-[var(--color-brand-700)] hover:underline">
                        مدیریت انبار
                    </a>

                </div>


                <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">

                    <div class="rounded-xl border border-red-100 bg-red-50 p-4">
                        <div class="text-xs text-red-600">
                            ناموجود
                        </div>

                        <div class="mt-2 text-2xl font-black text-red-700">
                            {{ number_format($outOfStockCount) }}
                        </div>
                    </div>

                    <div class="rounded-xl border border-amber-100 bg-amber-50 p-4">
                        <div class="text-xs text-amber-600">
                            موجودی کم
                        </div>

                        <div class="mt-2 text-2xl font-black text-amber-700">
                            {{ number_format($lowStockCount) }}
                        </div>
                    </div>

                    <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-4">
                        <div class="text-xs text-emerald-600">
                            محصولات فعال
                        </div>

                        <div class="mt-2 text-2xl font-black text-emerald-700">
                            {{ number_format($activeProductsCount) }}
                        </div>
                    </div>

                </div>


                <div class="mt-6 overflow-hidden rounded-xl border border-[var(--color-border)]">

                    <div class="border-b border-[var(--color-border)] bg-[var(--color-neutral-50)] px-4 py-3">
                        <div class="text-sm font-black text-[var(--color-text-primary)]">
                            کمبود موجودی
                        </div>
                    </div>

                    <div class="divide-y divide-[var(--color-border)]">

                        @forelse($lowStockProducts as $product)

                            <div class="flex items-center justify-between gap-4 px-4 py-3">

                                <div class="min-w-0">

                                    <div class="truncate text-sm font-bold text-[var(--color-text-primary)]">
                                        {{ $product->name }}
                                    </div>

                                    <div class="mt-1 text-xs text-[var(--color-text-muted)]">
                                        {{ $product->category?->name ?? 'بدون دسته‌بندی' }}
                                    </div>

                                </div>

                                <div class="flex-shrink-0">

                                    @if($product->stock <= 0)
                                        <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-black text-red-700">
                                            ناموجود
                                        </span>
                                    @else
                                        <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-black text-amber-700">
                                            {{ number_format($product->stock) }} عدد
                                        </span>
                                    @endif

                                </div>

                            </div>

                        @empty

                            <div class="px-4 py-8 text-center text-sm text-[var(--color-text-secondary)]">
                                محصولی با موجودی بحرانی وجود ندارد.
                            </div>

                        @endforelse

                    </div>

                </div>

            </div>

        </div>


        {{-- Recent Orders + Best Selling --}}
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">

            {{-- Recent Orders --}}
            <div class="overflow-hidden rounded-2xl border border-[var(--color-border)] bg-white shadow-sm">

                <div class="flex items-center justify-between border-b border-[var(--color-border)] p-5">

                    <div>
                        <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                            سفارش‌های اخیر
                        </h2>

                        <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                            آخرین سفارش‌های ثبت شده
                        </p>
                    </div>

                    <a href="{{ route('admin.orders.index') }}"
                       class="text-xs font-bold text-[var(--color-brand-700)] hover:underline">
                        مشاهده همه
                    </a>

                </div>


                <div class="divide-y divide-[var(--color-border)]">

                    @forelse($recentOrders as $order)

                        <a href="{{ route('admin.orders.show', $order) }}"
                           class="block p-4 transition hover:bg-[var(--color-neutral-50)]">

                            <div class="flex items-start justify-between gap-4">

                                <div class="min-w-0">

                                    <div class="font-black text-[var(--color-text-primary)]">
                                        #{{ $order->order_number }}
                                    </div>

                                    <div class="mt-1 text-xs text-[var(--color-text-secondary)]">
                                        {{ $order->user?->name ?? $order->shipping_full_name }}
                                    </div>

                                    <div class="mt-1 text-xs text-[var(--color-text-muted)]">
                                        {{ number_format($order->items_count ?? 0) }}
                                        آیتم
                                    </div>

                                </div>

                                <div class="text-left">

                                    <div class="font-black text-[var(--color-text-primary)]">
                                        {{ number_format($order->total) }}
                                    </div>

                                    <div class="mt-1 text-xs text-[var(--color-text-muted)]">
                                        تومان
                                    </div>

                                    <span class="mt-2 inline-flex rounded-full px-2.5 py-1 text-[11px] font-bold {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ $statusLabels[$order->status] ?? $order->status }}
                                    </span>

                                </div>

                            </div>

                        </a>

                    @empty

                        <div class="px-5 py-12 text-center text-sm text-[var(--color-text-secondary)]">
                            هنوز سفارشی ثبت نشده است.
                        </div>

                    @endforelse

                </div>

            </div>


            {{-- Best Selling Products --}}
            <div class="overflow-hidden rounded-2xl border border-[var(--color-border)] bg-white shadow-sm">

                <div class="flex items-center justify-between border-b border-[var(--color-border)] p-5">

                    <div>
                        <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                            پرفروش‌ترین محصولات
                        </h2>

                        <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                            بر اساس تعداد فروش پرداخت‌شده
                        </p>
                    </div>

                    <a href="{{ route('admin.products.index') }}"
                       class="text-xs font-bold text-[var(--color-brand-700)] hover:underline">
                        محصولات
                    </a>

                </div>


                <div class="divide-y divide-[var(--color-border)]">

                    @forelse($bestSellingProducts as $product)

                        <div class="flex items-center justify-between gap-4 p-4">

                            <div class="flex min-w-0 items-center gap-3">

                                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-[var(--color-brand-50)] text-sm font-black text-[var(--color-brand-700)]">
                                    {{ $loop->iteration }}
                                </div>

                                <div class="min-w-0">

                                    <div class="truncate text-sm font-bold text-[var(--color-text-primary)]">
                                        {{ $product->product_name }}
                                    </div>

                                    <div class="mt-1 text-xs text-[var(--color-text-muted)]">
                                        {{ number_format($product->total_quantity) }}
                                        عدد فروخته شده
                                    </div>

                                </div>

                            </div>

                            <div class="flex-shrink-0 text-left">

                                <div class="font-black text-[var(--color-text-primary)]">
                                    {{ number_format($product->total_sales) }}
                                </div>

                                <div class="mt-1 text-xs text-[var(--color-text-muted)]">
                                    تومان
                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="px-5 py-12 text-center text-sm text-[var(--color-text-secondary)]">
                            هنوز فروش موفقی ثبت نشده است.
                        </div>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- Inventory Movements + Reviews + Messages --}}
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

            {{-- Inventory Movements --}}
            <div class="overflow-hidden rounded-2xl border border-[var(--color-border)] bg-white shadow-sm">

                <div class="border-b border-[var(--color-border)] p-5">

                    <div>
                        <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                            آخرین تغییرات انبار
                        </h2>

                        <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                            آخرین ورود و خروج کالا
                        </p>
                    </div>

                </div>


                <div class="divide-y divide-[var(--color-border)]">

                    @forelse($recentInventoryMovements as $movement)

                        <div class="p-4">

                            <div class="flex items-start justify-between gap-3">

                                <div class="min-w-0">

                                    <div class="truncate text-sm font-bold text-[var(--color-text-primary)]">
                                        {{ $movement->product?->name ?? 'محصول حذف شده' }}
                                    </div>

                                    <div class="mt-1 text-xs text-[var(--color-text-muted)]">
                                        {{ $movement->user?->name ?? 'سیستم' }}
                                    </div>

                                </div>

                                <div class="flex-shrink-0 text-left">

                                    <div class="font-black {{ $movement->quantity >= 0 ? 'text-emerald-700' : 'text-red-700' }}">
                                        {{ $movement->quantity >= 0 ? '+' : '' }}{{ number_format($movement->quantity) }}
                                    </div>

                                    <div class="mt-1">
                                        <span class="rounded-full px-2.5 py-1 text-[10px] font-bold {{ $movementClasses[$movement->type] ?? 'bg-gray-100 text-gray-700' }}">
                                            {{ $movementLabels[$movement->type] ?? $movement->type }}
                                        </span>
                                    </div>

                                </div>

                            </div>

                            <div class="mt-2 text-xs text-[var(--color-text-muted)]">
                                موجودی:
                                {{ number_format($movement->stock_before) }}
                                →
                                {{ number_format($movement->stock_after) }}
                            </div>

                        </div>

                    @empty

                        <div class="px-5 py-12 text-center text-sm text-[var(--color-text-secondary)]">
                            هنوز تغییر موجودی ثبت نشده است.
                        </div>

                    @endforelse

                </div>

            </div>


            {{-- Reviews --}}
            <div class="overflow-hidden rounded-2xl border border-[var(--color-border)] bg-white shadow-sm">

                <div class="flex items-center justify-between border-b border-[var(--color-border)] p-5">

                    <div>
                        <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                            آخرین نظرات
                        </h2>

                        <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                            بررسی و مدیریت نظرات
                        </p>
                    </div>

                    <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">
                        {{ number_format($pendingReviews) }} در انتظار
                    </span>

                </div>


                <div class="divide-y divide-[var(--color-border)]">

                    @forelse($recentReviews as $review)

                        <div class="p-4">

                            <div class="flex items-start justify-between gap-3">

                                <div class="min-w-0">

                                    <div class="truncate text-sm font-bold text-[var(--color-text-primary)]">
                                        {{ $review->product?->name ?? 'محصول حذف شده' }}
                                    </div>

                                    <div class="mt-1 text-xs text-[var(--color-text-muted)]">
                                        {{ $review->user?->name ?? 'کاربر حذف شده' }}
                                    </div>

                                </div>

                                <span class="flex-shrink-0 rounded-full px-2.5 py-1 text-[10px] font-bold {{ match($review->status) {
                                    'approved' => 'bg-emerald-50 text-emerald-700',
                                    'rejected' => 'bg-red-50 text-red-700',
                                    default => 'bg-amber-50 text-amber-700',
                                } }}">
                                    {{ match($review->status) {
                                        'approved' => 'تأیید',
                                        'rejected' => 'رد',
                                        default => 'در انتظار',
                                    } }}
                                </span>

                            </div>

                            <div class="mt-2 flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="{{ $i <= $review->rating ? 'text-amber-500' : 'text-[var(--color-neutral-300)]' }}">
                                        ★
                                    </span>
                                @endfor
                            </div>

                            @if($review->body)
                                <p class="mt-2 line-clamp-2 text-xs leading-6 text-[var(--color-text-secondary)]">
                                    {{ $review->body }}
                                </p>
                            @endif

                        </div>

                    @empty

                        <div class="px-5 py-12 text-center text-sm text-[var(--color-text-secondary)]">
                            هنوز نظری ثبت نشده است.
                        </div>

                    @endforelse

                </div>

            </div>


            {{-- Messages --}}
            <div class="overflow-hidden rounded-2xl border border-[var(--color-border)] bg-white shadow-sm">

                <div class="flex items-center justify-between border-b border-[var(--color-border)] p-5">

                    <div>
                        <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                            آخرین پیام‌ها
                        </h2>

                        <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                            پیام‌های فرم تماس
                        </p>
                    </div>

                    <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-bold text-red-700">
                        {{ number_format($unreadMessages) }} جدید
                    </span>

                </div>


                <div class="divide-y divide-[var(--color-border)]">

                    @forelse($recentMessages as $message)

                        <a href="{{ route('admin.contact-messages.show', $message) }}"
                           class="block p-4 transition hover:bg-[var(--color-neutral-50)]">

                            <div class="flex items-start justify-between gap-3">

                                <div class="min-w-0">

                                    <div class="truncate text-sm font-bold text-[var(--color-text-primary)]">
                                        {{ $message->subject }}
                                    </div>

                                    <div class="mt-1 text-xs text-[var(--color-text-muted)]">
                                        {{ $message->name }}
                                    </div>

                                </div>

                                <span class="flex-shrink-0 rounded-full px-2.5 py-1 text-[10px] font-bold {{ match($message->status) {
                                    'new' => 'bg-red-50 text-red-700',
                                    'read' => 'bg-blue-50 text-blue-700',
                                    'replied' => 'bg-emerald-50 text-emerald-700',
                                    default => 'bg-gray-100 text-gray-700',
                                } }}">
                                    {{ match($message->status) {
                                        'new' => 'جدید',
                                        'read' => 'خوانده',
                                        'replied' => 'پاسخ داده',
                                        default => 'بسته',
                                    } }}
                                </span>

                            </div>

                            <p class="mt-2 line-clamp-2 text-xs leading-6 text-[var(--color-text-secondary)]">
                                {{ $message->message }}
                            </p>

                            <div class="mt-2 text-[11px] text-[var(--color-text-muted)]">
                                {{ $message->created_at->locale('fa')->translatedFormat('Y/m/d H:i') }}
                            </div>

                        </a>

                    @empty

                        <div class="px-5 py-12 text-center text-sm text-[var(--color-text-secondary)]">
                            هنوز پیامی دریافت نشده است.
                        </div>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- Quick Stats --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">

            <a href="{{ route('admin.orders.index') }}"
               class="rounded-2xl border border-[var(--color-border)] bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="text-xs text-[var(--color-text-muted)]">
                    کل سفارش‌ها
                </div>

                <div class="mt-2 text-xl font-black text-[var(--color-text-primary)]">
                    {{ number_format($ordersCount) }}
                </div>

            </a>


            <a href="{{ route('admin.products.index') }}"
               class="rounded-2xl border border-[var(--color-border)] bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="text-xs text-[var(--color-text-muted)]">
                    محصولات
                </div>

                <div class="mt-2 text-xl font-black text-[var(--color-text-primary)]">
                    {{ number_format($productsCount) }}
                </div>

            </a>


            <a href="{{ route('admin.reviews.index') }}"
               class="rounded-2xl border border-[var(--color-border)] bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="text-xs text-[var(--color-text-muted)]">
                    نظرات
                </div>

                <div class="mt-2 text-xl font-black text-[var(--color-text-primary)]">
                    {{ number_format($reviewsCount) }}
                </div>

            </a>


            <a href="{{ route('admin.contact-messages.index') }}"
               class="rounded-2xl border border-[var(--color-border)] bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="text-xs text-[var(--color-text-muted)]">
                    پیام‌ها
                </div>

                <div class="mt-2 text-xl font-black text-[var(--color-text-primary)]">
                    {{ number_format($totalMessages) }}
                </div>

            </a>


            <a href="{{ route('admin.newsletter.index') }}"
               class="rounded-2xl border border-[var(--color-border)] bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="text-xs text-[var(--color-text-muted)]">
                    خبرنامه
                </div>

                <div class="mt-2 text-xl font-black text-[var(--color-text-primary)]">
                    {{ number_format($newsletterSubscribers) }}
                </div>

            </a>


            <a href="{{ route('admin.inventory.index') }}"
               class="rounded-2xl border border-[var(--color-border)] bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="text-xs text-[var(--color-text-muted)]">
                    کمبود موجودی
                </div>

                <div class="mt-2 text-xl font-black text-[var(--color-text-primary)]">
                    {{ number_format($lowStockCount) }}
                </div>

            </a>

        </div>

    </div>


    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const chartUrl = @json(route('admin.dashboard.chart'));

            const chartTabs = document.querySelectorAll('.dashboard-chart-tab');

            const salesCanvas = document.getElementById('salesChart');

            const orderStatusCanvas = document.getElementById('orderStatusChart');

            let salesChart = null;


            /*
            |--------------------------------------------------------------------------
            | Sales Chart
            |--------------------------------------------------------------------------
            */

            async function loadSalesChart(range) {

                chartTabs.forEach(tab => {

                    const active = tab.dataset.range === range;

                    tab.classList.toggle(
                        'bg-white',
                        active
                    );

                    tab.classList.toggle(
                        'text-[var(--color-text-primary)]',
                        active
                    );

                    tab.classList.toggle(
                        'shadow-sm',
                        active
                    );

                    tab.classList.toggle(
                        'text-[var(--color-text-muted)]',
                        !active
                    );

                });


                try {

                    const response = await fetch(
                        `${chartUrl}?range=${encodeURIComponent(range)}`,
                        {
                            headers: {
                                'Accept': 'application/json'
                            }
                        }
                    );

                    if (!response.ok) {
                        throw new Error('Chart request failed.');
                    }

                    const result = await response.json();


                    if (salesChart) {
                        salesChart.destroy();
                    }


                    salesChart = new Chart(
                        salesCanvas,
                        {
                            type: 'line',

                            data: {
                                labels: result.labels,

                                datasets: [
                                    {
                                        label: 'فروش',

                                        data: result.data,

                                        borderWidth: 2,

                                        tension: 0.35,

                                        fill: true,

                                        pointRadius: 3,

                                        pointHoverRadius: 5
                                    }
                                ]
                            },

                            options: {

                                responsive: true,

                                maintainAspectRatio: false,

                                interaction: {
                                    intersect: false,
                                    mode: 'index'
                                },

                                plugins: {
                                    legend: {
                                        display: false
                                    },

                                    tooltip: {
                                        callbacks: {
                                            label: function (context) {
                                                return Number(
                                                    context.raw
                                                ).toLocaleString('fa-IR') + ' تومان';
                                            }
                                        }
                                    }
                                },

                                scales: {

                                    x: {
                                        grid: {
                                            display: false
                                        }
                                    },

                                    y: {
                                        beginAtZero: true,

                                        ticks: {
                                            callback: function (value) {
                                                return Number(value).toLocaleString('fa-IR');
                                            }
                                        }
                                    }

                                }

                            }

                        }
                    );

                } catch (error) {

                    console.error(error);

                }

            }


            chartTabs.forEach(tab => {

                tab.addEventListener('click', function () {
                    loadSalesChart(this.dataset.range);
                });

            });


            /*
            |--------------------------------------------------------------------------
            | Initial Sales Chart
            |--------------------------------------------------------------------------
            */

            loadSalesChart('daily');


            /*
            |--------------------------------------------------------------------------
            | Order Status Chart
            |--------------------------------------------------------------------------
            */

            const orderStatusData = @json(array_values($orderStatusOverview));

            new Chart(
                orderStatusCanvas,
                {
                    type: 'doughnut',

                    data: {
                        labels: @json(array_values($statusLabels)),

                        datasets: [
                            {
                                data: orderStatusData,

                                borderWidth: 0
                            }
                        ]
                    },

                    options: {

                        responsive: true,

                        maintainAspectRatio: false,

                        cutout: '68%',

                        plugins: {

                            legend: {
                                display: false
                            },

                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        return `${context.label}: ${Number(context.raw).toLocaleString('fa-IR')}`;
                                    }
                                }
                            }

                        }

                    }

                }
            );

        });
    </script>
@endsection
