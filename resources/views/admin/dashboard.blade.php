@extends('layouts.admin')

@section('title', 'داشبورد | فرزین')

@section('content')
    @php
        $statusLabels = [
            'pending' => 'در انتظار',
            'processing' => 'در حال پردازش',
            'shipped' => 'ارسال شده',
            'delivered' => 'تحویل شده',
            'cancelled' => 'لغو شده',
        ];

        $statusTone = [
            'pending' => 'warning',
            'processing' => 'info',
            'shipped' => 'brand',
            'delivered' => 'success',
            'cancelled' => 'danger',
        ];

        $movementLabels = [
            'sale' => 'فروش',
            'restock' => 'تأمین موجودی',
            'return' => 'مرجوعی',
            'adjustment' => 'اصلاح موجودی',
        ];

        $chartData = $salesChart['data'] ?? [];
        $chartLabels = $salesChart['labels'] ?? [];
        $chartMax = max(1, ...array_map('intval', $chartData));
        $processingOrders = (int) $pendingOrders + (int) $processingOrders;
    @endphp

    <div class="space-y-6 pb-4">

        {{-- Control center header --}}
        <section class="overflow-hidden rounded-[2rem] border border-[var(--color-brand-800)] bg-[var(--color-brand-950)] text-white shadow-[var(--shadow-lg)]">
            <div class="relative grid gap-8 p-7 sm:p-9 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-end">
                <div class="absolute -top-28 left-1/4 size-72 rounded-full bg-[var(--color-accent-600)]/10 blur-3xl"></div>
                <div class="absolute -bottom-32 right-0 size-80 rounded-full bg-[var(--color-earth-400)]/10 blur-3xl"></div>

                <div class="relative">
                    <span class="text-[10px] font-black uppercase tracking-[0.28em] text-[var(--color-earth-200)]">
                        FARZIN / CONTROL CENTER
                    </span>

                    <h1 class="mt-3 max-w-3xl text-3xl font-black tracking-tight sm:text-4xl lg:text-5xl">
                        فروشگاه را،
                        <span class="text-[var(--color-earth-200)]">در یک نگاه.</span>
                    </h1>

                    <p class="mt-4 max-w-2xl text-sm leading-8 text-white/65">
                        فروش، سفارش، مشتری، موجودی و کارهای نیازمند اقدام را از همین‌جا کنترل کن.
                        تمام شاخص‌های این صفحه از داده‌های واقعی فرزین می‌آیند.
                    </p>
                </div>

                <div class="relative flex flex-col gap-3 sm:flex-row lg:flex-col lg:items-stretch">
                    <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                        <div class="text-[10px] font-bold text-white/45">امروز</div>
                        <div class="mt-1 text-sm font-black">
                            {{ now()->locale('fa')->translatedFormat('l، j F Y') }}
                        </div>
                    </div>

                    <a
                        href="{{ route('shop.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[var(--color-earth-100)] px-5 py-3.5 text-sm font-black text-[var(--color-earth-900)] transition hover:-translate-y-0.5 hover:bg-white"
                    >
                        مشاهده فروشگاه
                        <span aria-hidden="true">↗</span>
                    </a>
                </div>
            </div>
        </section>

        {{-- Primary KPI grid --}}
        <section aria-label="شاخص‌های اصلی" class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            @php
                $kpis = [
                    [
                        'code' => '01',
                        'label' => 'فروش امروز',
                        'value' => number_format($todaySales),
                        'meta' => number_format($todayOrderCount).' سفارش پرداخت‌شده',
                        'tone' => 'dark',
                    ],
                    [
                        'code' => '02',
                        'label' => 'فروش این ماه',
                        'value' => number_format($monthSales),
                        'meta' => number_format($monthGrowth, 1).'٪ نسبت به ماه قبل',
                        'tone' => 'earth',
                    ],
                    [
                        'code' => '03',
                        'label' => 'سفارش‌های جاری',
                        'value' => number_format($processingOrders),
                        'meta' => number_format($ordersCount).' سفارش ثبت‌شده',
                        'tone' => 'accent',
                    ],
                    [
                        'code' => '04',
                        'label' => 'مشتری فعال',
                        'value' => number_format($activeCustomersCount),
                        'meta' => number_format($newCustomersThisMonth).' مشتری جدید این ماه',
                        'tone' => 'light',
                    ],
                ];

                $toneClasses = [
                    'dark' => 'bg-[var(--color-brand-950)] text-white border-[var(--color-brand-900)]',
                    'earth' => 'bg-[var(--color-earth-50)] text-[var(--color-earth-900)] border-[var(--color-earth-200)]',
                    'accent' => 'bg-[var(--color-accent-600)] text-white border-[var(--color-accent-600)]',
                    'light' => 'bg-white text-[var(--color-text-primary)] border-[var(--color-border)]',
                ];
            @endphp

            @foreach($kpis as $kpi)
                <article class="relative overflow-hidden rounded-[1.5rem] border p-5 shadow-[var(--shadow-xs)] {{ $toneClasses[$kpi['tone']] }}">
                    <span class="text-[9px] font-black uppercase tracking-[0.2em] opacity-55">
                        {{ $kpi['label'] }}
                    </span>
                    <strong class="mt-3 block text-2xl font-black tracking-tight sm:text-3xl">
                        {{ $kpi['value'] }}
                    </strong>
                    <span class="mt-2 block text-[10px] font-bold opacity-60">
                        {{ $kpi['meta'] }}
                    </span>
                    <i class="pointer-events-none absolute -bottom-4 left-4 text-5xl font-black opacity-[0.06] not-italic">
                        {{ $kpi['code'] }}
                    </i>
                </article>
            @endforeach
        </section>

        {{-- Action center --}}
        <section class="overflow-hidden rounded-[1.75rem] border border-[var(--color-border)] bg-white shadow-[var(--shadow-xs)]">
            <header class="flex flex-col gap-4 border-b border-[var(--color-border)] px-6 py-6 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-[0.24em] text-[var(--color-accent-600)]">
                        ACTION CENTER
                    </span>
                    <h2 class="mt-2 text-xl font-black text-[var(--color-text-primary)]">
                        کارهایی که الان نیاز به توجه دارند
                    </h2>
                    <p class="mt-1 text-xs leading-6 text-[var(--color-text-muted)]">
                        فقط وضعیت‌هایی که واقعاً می‌توانند روی فروشگاه اثر بگذارند.
                    </p>
                </div>

                <a
                    href="{{ route('admin.orders.index') }}"
                    class="text-xs font-black text-[var(--color-brand-900)] transition hover:text-[var(--color-accent-600)]"
                >
                    مدیریت سفارش‌ها ↗
                </a>
            </header>

            <div class="grid gap-px bg-[var(--color-border)] sm:grid-cols-2 xl:grid-cols-4">
                @php
                    $actions = [
                        [
                            'label' => 'سفارش‌های در حال رسیدگی',
                            'value' => $processingOrders,
                            'meta' => 'pending / processing / shipped',
                            'href' => route('admin.orders.index'),
                            'attention' => $processingOrders > 0,
                        ],
                        [
                            'label' => 'موجودی رو به پایان',
                            'value' => $lowStockCount,
                            'meta' => 'محصول با موجودی ۱ تا ۵',
                            'href' => route('admin.inventory.index'),
                            'attention' => $lowStockCount > 0,
                        ],
                        [
                            'label' => 'نظرات در انتظار',
                            'value' => $pendingReviews,
                            'meta' => 'نیازمند بررسی و انتشار',
                            'href' => route('admin.reviews.index'),
                            'attention' => $pendingReviews > 0,
                        ],
                        [
                            'label' => 'پیام خوانده‌نشده',
                            'value' => $unreadMessages,
                            'meta' => number_format($totalMessages).' پیام در صندوق',
                            'href' => route('admin.contact-messages.index'),
                            'attention' => $unreadMessages > 0,
                        ],
                    ];
                @endphp

                @foreach($actions as $action)
                    <a
                        href="{{ $action['href'] }}"
                        class="group bg-[var(--color-surface)] p-5 transition hover:bg-[var(--color-earth-50)]"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <span class="text-xs font-black text-[var(--color-text-primary)]">
                                    {{ $action['label'] }}
                                </span>

                                <div class="mt-2 flex items-end gap-2">
                                    <strong class="text-2xl font-black text-[var(--color-brand-950)]">
                                        {{ number_format($action['value']) }}
                                    </strong>
                                    @if($action['attention'])
                                        <span class="mb-1 size-2 rounded-full bg-[var(--color-accent-600)]"></span>
                                    @endif
                                </div>

                                <span class="mt-1 block text-[10px] leading-5 text-[var(--color-text-muted)]">
                                    {{ $action['meta'] }}
                                </span>
                            </div>

                            <span class="flex size-9 shrink-0 items-center justify-center rounded-xl border border-[var(--color-border)] text-[var(--color-brand-900)] transition group-hover:-translate-x-1 group-hover:border-[var(--color-brand-300)] group-hover:bg-white">
                                ←
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        {{-- Sales + order flow --}}
        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(320px,.75fr)]">

            <article class="overflow-hidden rounded-[1.75rem] border border-[var(--color-border)] bg-white shadow-[var(--shadow-xs)]">
                <header class="flex flex-col gap-4 border-b border-[var(--color-border)] px-6 py-6 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-[0.22em] text-[var(--color-accent-600)]">
                            SALES SIGNAL
                        </span>
                        <h2 class="mt-2 text-xl font-black">
                            روند فروش
                        </h2>
                        <p class="mt-1 text-xs leading-6 text-[var(--color-text-muted)]">
                            فروش پرداخت‌شده در بازه انتخابی.
                        </p>
                    </div>

                    <label class="inline-flex items-center gap-2 rounded-xl border border-[var(--color-border)] bg-[var(--color-earth-50)] px-3 py-2 text-xs font-bold text-[var(--color-text-secondary)]">
                        <span>بازه</span>
                        <select
                            id="admin-sales-range"
                            class="border-0 bg-transparent p-0 text-xs font-black outline-none"
                        >
                            <option value="daily">روزانه</option>
                            <option value="monthly">ماهانه</option>
                            <option value="yearly">سالانه</option>
                        </select>
                    </label>
                </header>

                <div
                    id="admin-sales-chart"
                    class="grid h-72 items-end gap-2 overflow-hidden px-5 py-6 sm:px-7"
                    style="grid-template-columns: repeat({{ max(1, count($chartData)) }}, minmax(0, 1fr));"
                >
                    @foreach($chartData as $index => $value)
                        @php
                            $height = $value > 0
                                ? max(4, min(100, ((int) $value / $chartMax) * 100))
                                : 2;
                        @endphp

                        <div
                            class="group flex min-w-0 flex-col items-center justify-end gap-2"
                            data-chart-column
                            title="{{ number_format($value) }} تومان"
                        >
                            <div class="relative flex h-56 w-full items-end justify-center">
                                <span
                                    data-chart-bar
                                    class="block w-full max-w-[18px] rounded-t-lg bg-[var(--color-brand-900)] transition-all duration-300 group-hover:bg-[var(--color-accent-600)]"
                                    style="height: {{ $height }}%;"
                                ></span>
                            </div>

                            <span
                                data-chart-label
                                class="block max-w-full truncate text-[8px] font-bold text-[var(--color-text-soft)]"
                            >
                                {{ $chartLabels[$index] ?? '—' }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <footer class="flex items-center justify-between gap-4 border-t border-[var(--color-border)] bg-[var(--color-earth-50)] px-6 py-4 text-[9px] font-bold text-[var(--color-text-muted)]">
                    <span>مبالغ به تومان</span>
                    <span>فقط سفارش‌های پرداخت‌شده</span>
                </footer>
            </article>

            <article class="overflow-hidden rounded-[1.75rem] border border-[var(--color-border)] bg-white shadow-[var(--shadow-xs)]">
                <header class="border-b border-[var(--color-border)] px-6 py-6">
                    <span class="text-[10px] font-black uppercase tracking-[0.22em] text-[var(--color-accent-600)]">
                        ORDER FLOW
                    </span>
                    <h2 class="mt-2 text-xl font-black">
                        وضعیت سفارش‌ها
                    </h2>
                </header>

                <div class="space-y-4 p-6">
                    @foreach($statusLabels as $status => $label)
                        @php
                            $count = (int) ($orderStatusOverview[$status] ?? 0);
                            $totalStatus = max(1, array_sum($orderStatusOverview));
                            $percent = round(($count / $totalStatus) * 100);
                            $barTone = match($statusTone[$status]) {
                                'warning' => 'bg-amber-500',
                                'info' => 'bg-blue-500',
                                'brand' => 'bg-[var(--color-brand-700)]',
                                'success' => 'bg-emerald-500',
                                'danger' => 'bg-[var(--color-accent-600)]',
                            };
                        @endphp

                        <div>
                            <div class="flex items-center justify-between gap-3 text-xs">
                                <span class="font-bold text-[var(--color-text-secondary)]">{{ $label }}</span>
                                <strong class="font-black text-[var(--color-text-primary)]">{{ number_format($count) }}</strong>
                            </div>

                            <div class="mt-2 h-2 overflow-hidden rounded-full bg-[var(--color-neutral-100)]">
                                <span
                                    class="block h-full rounded-full {{ $barTone }}"
                                    style="width: {{ $percent }}%;"
                                ></span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <footer class="border-t border-[var(--color-border)] bg-[var(--color-earth-50)] px-6 py-5">
                    <div class="grid grid-cols-2 gap-3 text-center">
                        <div class="rounded-xl bg-white px-3 py-3">
                            <span class="block text-[9px] font-bold text-[var(--color-text-muted)]">میانگین امروز</span>
                            <strong class="mt-1 block text-sm font-black">{{ number_format($todayAverageOrderValue) }} تومان</strong>
                        </div>
                        <div class="rounded-xl bg-white px-3 py-3">
                            <span class="block text-[9px] font-bold text-[var(--color-text-muted)]">میانگین ماه</span>
                            <strong class="mt-1 block text-sm font-black">{{ number_format($monthAverageOrderValue) }} تومان</strong>
                        </div>
                    </div>
                </footer>
            </article>
        </section>

        {{-- Catalog + best sellers --}}
        <section class="grid gap-6 xl:grid-cols-2">

            <article class="overflow-hidden rounded-[1.75rem] border border-[var(--color-border)] bg-white shadow-[var(--shadow-xs)]">
                <header class="flex items-end justify-between gap-4 border-b border-[var(--color-border)] px-6 py-6">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-[0.22em] text-[var(--color-accent-600)]">
                            INVENTORY
                        </span>
                        <h2 class="mt-2 text-xl font-black">هشدار موجودی</h2>
                    </div>
                    <a href="{{ route('admin.inventory.index') }}" class="text-xs font-black text-[var(--color-brand-900)] hover:text-[var(--color-accent-600)]">
                        مشاهده انبار ↗
                    </a>
                </header>

                @if($lowStockProducts->isNotEmpty())
                    <div class="divide-y divide-[var(--color-border)]">
                        @foreach($lowStockProducts as $product)
                            <a
                                href="{{ route('admin.products.edit', $product) }}"
                                class="flex items-center gap-4 px-6 py-4 transition hover:bg-[var(--color-earth-50)]"
                            >
                                <div class="flex size-11 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-[var(--color-neutral-100)]">
                                    @if($product->primaryImage?->image)
                                        <img
                                            src="{{ asset('storage/'.$product->primaryImage->image) }}"
                                            alt=""
                                            class="h-full w-full object-cover"
                                            loading="lazy"
                                        >
                                    @else
                                        <span class="text-[10px] font-black text-[var(--color-text-soft)]">F</span>
                                    @endif
                                </div>

                                <div class="min-w-0 flex-1">
                                    <strong class="block truncate text-xs font-black">{{ $product->name }}</strong>
                                    <span class="mt-1 block truncate text-[9px] text-[var(--color-text-muted)]">
                                        {{ $product->category?->name ?? 'بدون دسته' }}
                                    </span>
                                </div>

                                <span class="rounded-full px-2.5 py-1 text-[9px] font-black {{ $product->stock === 0 ? 'bg-[var(--color-accent-50)] text-[var(--color-accent-700)]' : 'bg-amber-50 text-amber-700' }}">
                                    {{ $product->stock === 0 ? 'ناموجود' : $product->stock.' عدد' }}
                                </span>

                                <span class="text-[var(--color-text-soft)]">←</span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="px-6 py-14 text-center">
                        <div class="mx-auto flex size-12 items-center justify-center rounded-2xl bg-[var(--color-success-50)] text-[var(--color-success-700)]">✓</div>
                        <p class="mt-3 text-xs font-bold text-[var(--color-text-secondary)]">هشدار موجودی فعالی وجود ندارد.</p>
                    </div>
                @endif
            </article>

            <article class="overflow-hidden rounded-[1.75rem] border border-[var(--color-border)] bg-white shadow-[var(--shadow-xs)]">
                <header class="flex items-end justify-between gap-4 border-b border-[var(--color-border)] px-6 py-6">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-[0.22em] text-[var(--color-accent-600)]">
                            BEST SELLERS
                        </span>
                        <h2 class="mt-2 text-xl font-black">پرفروش‌ترین محصولات</h2>
                    </div>
                    <a href="{{ route('admin.products.index') }}" class="text-xs font-black text-[var(--color-brand-900)] hover:text-[var(--color-accent-600)]">
                        محصولات ↗
                    </a>
                </header>

                @if($bestSellingProducts->isNotEmpty())
                    <div class="divide-y divide-[var(--color-border)]">
                        @foreach($bestSellingProducts as $index => $product)
                            <div class="flex items-center gap-4 px-6 py-4">
                                <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-[var(--color-earth-100)] text-[10px] font-black text-[var(--color-earth-800)]">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </span>

                                <div class="min-w-0 flex-1">
                                    <strong class="block truncate text-xs font-black">
                                        {{ $product->product_name }}
                                    </strong>
                                    <span class="mt-1 block text-[9px] text-[var(--color-text-muted)]">
                                        {{ number_format($product->total_quantity) }} عدد فروخته‌شده
                                    </span>
                                </div>

                                <strong class="shrink-0 text-[10px] font-black text-[var(--color-text-primary)]">
                                    {{ number_format($product->total_sales) }}
                                    <span class="font-bold text-[var(--color-text-soft)]">تومان</span>
                                </strong>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="px-6 py-14 text-center text-xs font-bold text-[var(--color-text-muted)]">
                        هنوز داده‌ای برای رتبه‌بندی فروش ثبت نشده است.
                    </div>
                @endif
            </article>
        </section>

        {{-- Recent orders + reviews/messages --}}
        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_minmax(320px,.8fr)]">

            <article class="overflow-hidden rounded-[1.75rem] border border-[var(--color-border)] bg-white shadow-[var(--shadow-xs)]">
                <header class="flex items-end justify-between gap-4 border-b border-[var(--color-border)] px-6 py-6">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-[0.22em] text-[var(--color-accent-600)]">
                            RECENT ORDERS
                        </span>
                        <h2 class="mt-2 text-xl font-black">آخرین سفارش‌ها</h2>
                    </div>
                    <a href="{{ route('admin.orders.index') }}" class="text-xs font-black text-[var(--color-brand-900)] hover:text-[var(--color-accent-600)]">
                        همه سفارش‌ها ↗
                    </a>
                </header>

                @if($recentOrders->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-right">
                            <thead class="bg-[var(--color-earth-50)] text-[9px] font-black text-[var(--color-text-muted)]">
                                <tr>
                                    <th class="px-6 py-3">سفارش</th>
                                    <th class="px-6 py-3">مشتری</th>
                                    <th class="px-6 py-3">وضعیت</th>
                                    <th class="px-6 py-3">مبلغ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[var(--color-border)]">
                                @foreach($recentOrders as $order)
                                    @php
                                        $status = $order->status;
                                        $tone = $statusTone[$status] ?? 'brand';
                                        $statusClass = match($tone) {
                                            'warning' => 'bg-amber-50 text-amber-700',
                                            'info' => 'bg-blue-50 text-blue-700',
                                            'brand' => 'bg-[var(--color-brand-50)] text-[var(--color-brand-800)]',
                                            'success' => 'bg-[var(--color-success-50)] text-[var(--color-success-700)]',
                                            'danger' => 'bg-[var(--color-accent-50)] text-[var(--color-accent-700)]',
                                        };
                                    @endphp
                                    <tr class="group transition hover:bg-[var(--color-earth-50)]">
                                        <td class="px-6 py-4">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="font-black text-xs text-[var(--color-brand-900)] hover:text-[var(--color-accent-600)]">
                                                {{ $order->order_number }}
                                            </a>
                                            <span class="mt-1 block text-[9px] text-[var(--color-text-muted)]">
                                                {{ $order->created_at?->format('Y/m/d H:i') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="block max-w-32 truncate text-xs font-bold">{{ $order->user?->name ?? 'مشتری حذف‌شده' }}</span>
                                            <span class="mt-1 block text-[9px] text-[var(--color-text-muted)]">{{ number_format($order->items_count) }} قلم</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex rounded-full px-2.5 py-1 text-[9px] font-black {{ $statusClass }}">
                                                {{ $statusLabels[$status] ?? $status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-xs font-black">
                                            {{ number_format($order->total) }}
                                            <span class="text-[8px] font-bold text-[var(--color-text-soft)]">تومان</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-6 py-14 text-center text-xs font-bold text-[var(--color-text-muted)]">
                        هنوز سفارشی ثبت نشده است.
                    </div>
                @endif
            </article>

            <div class="space-y-6">
                <article class="overflow-hidden rounded-[1.75rem] border border-[var(--color-border)] bg-white shadow-[var(--shadow-xs)]">
                    <header class="border-b border-[var(--color-border)] px-6 py-5">
                        <span class="text-[10px] font-black uppercase tracking-[0.22em] text-[var(--color-accent-600)]">
                            REVIEWS
                        </span>
                        <h2 class="mt-2 text-lg font-black">آخرین نظرها</h2>
                    </header>

                    @if($recentReviews->isNotEmpty())
                        <div class="divide-y divide-[var(--color-border)]">
                            @foreach($recentReviews->take(4) as $review)
                                <div class="px-6 py-4">
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="text-[10px] font-black">{{ $review->user?->name ?? 'کاربر' }}</span>
                                        <span class="text-[9px] text-[var(--color-text-muted)]">{{ $review->status === 'pending' ? 'در انتظار' : 'تأیید شده' }}</span>
                                    </div>
                                    <p class="mt-2 line-clamp-2 text-[10px] leading-6 text-[var(--color-text-secondary)]">
                                        {{ $review->comment }}
                                    </p>
                                    <span class="mt-2 block truncate text-[9px] font-bold text-[var(--color-brand-700)]">
                                        {{ $review->product?->name ?? 'محصول حذف‌شده' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-6 py-10 text-center text-xs font-bold text-[var(--color-text-muted)]">نظری ثبت نشده است.</div>
                    @endif
                </article>

                <article class="overflow-hidden rounded-[1.75rem] border border-[var(--color-border)] bg-white shadow-[var(--shadow-xs)]">
                    <header class="border-b border-[var(--color-border)] px-6 py-5">
                        <span class="text-[10px] font-black uppercase tracking-[0.22em] text-[var(--color-accent-600)]">
                            INBOX
                        </span>
                        <h2 class="mt-2 text-lg font-black">آخرین پیام‌ها</h2>
                    </header>

                    @if($recentMessages->isNotEmpty())
                        <div class="divide-y divide-[var(--color-border)]">
                            @foreach($recentMessages->take(4) as $message)
                                <a href="{{ route('admin.contact-messages.show', $message) }}" class="block px-6 py-4 transition hover:bg-[var(--color-earth-50)]">
                                    <div class="flex items-center justify-between gap-3">
                                        <strong class="truncate text-[10px] font-black">{{ $message->name }}</strong>
                                        <span class="shrink-0 text-[8px] text-[var(--color-text-soft)]">{{ $message->created_at?->format('m/d') }}</span>
                                    </div>
                                    <p class="mt-1 line-clamp-1 text-[9px] text-[var(--color-text-muted)]">{{ $message->subject ?: 'پیام جدید' }}</p>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="px-6 py-10 text-center text-xs font-bold text-[var(--color-text-muted)]">پیامی در صندوق نیست.</div>
                    @endif
                </article>
            </div>
        </section>

        {{-- Operational footer --}}
        <section class="grid gap-4 sm:grid-cols-3">
            <a href="{{ route('admin.products.index') }}" class="rounded-[1.5rem] border border-[var(--color-border)] bg-[var(--color-earth-50)] p-5 transition hover:-translate-y-0.5 hover:border-[var(--color-brand-300)]">
                <span class="text-[9px] font-black uppercase tracking-[0.2em] text-[var(--color-text-muted)]">CATALOG</span>
                <strong class="mt-2 block text-sm font-black">محصولات و کاتالوگ</strong>
                <span class="mt-1 block text-[10px] text-[var(--color-text-muted)]">{{ number_format($productsCount) }} محصول · {{ number_format($activeProductsCount) }} فعال</span>
            </a>

            <a href="{{ route('admin.customers.index') }}" class="rounded-[1.5rem] border border-[var(--color-border)] bg-white p-5 transition hover:-translate-y-0.5 hover:border-[var(--color-brand-300)]">
                <span class="text-[9px] font-black uppercase tracking-[0.2em] text-[var(--color-text-muted)]">CUSTOMERS</span>
                <strong class="mt-2 block text-sm font-black">مشتریان</strong>
                <span class="mt-1 block text-[10px] text-[var(--color-text-muted)]">{{ number_format($customersCount) }} حساب · {{ number_format($activeCustomersCount) }} فعال</span>
            </a>

            <a href="{{ route('admin.newsletter.index') }}" class="rounded-[1.5rem] border border-[var(--color-border)] bg-white p-5 transition hover:-translate-y-0.5 hover:border-[var(--color-brand-300)]">
                <span class="text-[9px] font-black uppercase tracking-[0.2em] text-[var(--color-text-muted)]">NEWSLETTER</span>
                <strong class="mt-2 block text-sm font-black">خبرنامه</strong>
                <span class="mt-1 block text-[10px] text-[var(--color-text-muted)]">{{ number_format($newsletterSubscribers) }} مشترک فعال</span>
            </a>
        </section>
    </div>
@endsection

@push('scripts')
<script>
(() => {
    const select = document.getElementById('admin-sales-range');
    const chart = document.getElementById('admin-sales-chart');

    if (!select || !chart) {
        return;
    }

    const renderChart = (payload) => {
        const labels = Array.isArray(payload.labels) ? payload.labels : [];
        const values = Array.isArray(payload.data) ? payload.data.map(Number) : [];
        const max = Math.max(1, ...values);

        if (!values.length) {
            chart.innerHTML = '<div class="col-span-full flex items-center justify-center text-xs font-bold text-[var(--color-text-muted)]">داده‌ای برای این بازه وجود ندارد.</div>';
            chart.style.gridTemplateColumns = '1fr';
            return;
        }

        chart.style.gridTemplateColumns = `repeat(${values.length}, minmax(0, 1fr))`;
        chart.innerHTML = values.map((value, index) => {
            const height = value > 0 ? Math.max(4, Math.min(100, (value / max) * 100)) : 2;
            const label = labels[index] ?? '—';

            return `
                <div class="group flex min-w-0 flex-col items-center justify-end gap-2" title="${new Intl.NumberFormat('fa-IR').format(value)} تومان">
                    <div class="relative flex h-56 w-full items-end justify-center">
                        <span class="block w-full max-w-[18px] rounded-t-lg bg-[var(--color-brand-900)] transition-all duration-300 group-hover:bg-[var(--color-accent-600)]" style="height:${height}%"></span>
                    </div>
                    <span class="block max-w-full truncate text-[8px] font-bold text-[var(--color-text-soft)]">${label}</span>
                </div>
            `;
        }).join('');
    };

    let controller = null;

    select.addEventListener('change', async () => {
        controller?.abort();
        controller = new AbortController();

        chart.classList.add('opacity-60');

        try {
            const url = new URL('{{ route('admin.dashboard.chart') }}', window.location.origin);
            url.searchParams.set('range', select.value);

            const response = await fetch(url, {
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                signal: controller.signal,
            });

            const payload = await response.json();

            if (!response.ok) {
                throw new Error(payload.message || 'دریافت نمودار ناموفق بود.');
            }

            renderChart(payload);
        } catch (error) {
            if (error.name === 'AbortError') {
                return;
            }

            chart.innerHTML = '<div class="col-span-full flex items-center justify-center text-xs font-bold text-[var(--color-danger-700)]">دریافت داده نمودار ناموفق بود.</div>';
            chart.style.gridTemplateColumns = '1fr';
        } finally {
            chart.classList.remove('opacity-60');
        }
    });
})();
</script>
@endpush
