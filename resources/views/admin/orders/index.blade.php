@extends('layouts.admin')

@section('title', 'سفارش‌ها')

@section('content')
    @php
        $statusLabels = [
            'pending' => 'در انتظار',
            'processing' => 'در حال پردازش',
            'shipped' => 'ارسال شده',
            'delivered' => 'تحویل شده',
            'cancelled' => 'لغو شده',
        ];

        $paymentStatusLabels = [
            'pending' => 'در انتظار پرداخت',
            'paid' => 'پرداخت شده',
            'failed' => 'ناموفق',
            'refunded' => 'برگشت وجه',
        ];
    @endphp

    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-black text-[var(--color-text-primary)]">
                سفارش‌ها
            </h1>

            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                مدیریت و بررسی سفارش‌های ثبت شده
            </p>
        </div>

        {{-- Filters --}}
        <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">
            <form action="{{ route('admin.orders.index') }}"
                  method="GET"
                  class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">

                {{-- Search --}}
                <div class="lg:col-span-2">
                    <label for="search"
                           class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                        جستجو
                    </label>

                    <input type="text"
                           id="search"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="شماره سفارش، نام یا شماره تماس..."
                           class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none transition placeholder:text-[var(--color-text-muted)] focus:border-[var(--color-brand-600)]">
                </div>

                {{-- Order Status --}}
                <div>
                    <label for="status"
                           class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                        وضعیت سفارش
                    </label>

                    <select id="status"
                            name="status"
                            class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none focus:border-[var(--color-brand-600)]">
                        <option value="">همه وضعیت‌ها</option>

                        @foreach($statusLabels as $status => $label)
                            <option value="{{ $status }}"
                                    @selected(request('status') === $status)>
                            {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Payment Status --}}
                <div>
                    <label for="payment_status"
                           class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                        وضعیت پرداخت
                    </label>

                    <select id="payment_status"
                            name="payment_status"
                            class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none focus:border-[var(--color-brand-600)]">
                        <option value="">همه</option>

                        @foreach($paymentStatusLabels as $status => $label)
                            <option value="{{ $status }}"
                                    @selected(request('payment_status') === $status)>
                            {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 lg:col-span-4">
                    <button type="submit"
                            class="rounded-xl bg-[var(--color-brand-600)] px-5 py-3 text-sm font-bold text-white transition hover:bg-[var(--color-brand-700)]">
                        اعمال فیلتر
                    </button>

                    <a href="{{ route('admin.orders.index') }}"
                       class="rounded-xl border border-[var(--color-border)] bg-[var(--color-neutral-50)] px-5 py-3 text-sm font-bold text-[var(--color-text-primary)] hover:bg-[var(--color-neutral-100)]">
                        پاک کردن
                    </a>
                </div>

            </form>
        </div>

        {{-- Orders --}}
        <div class="overflow-hidden rounded-2xl border border-[var(--color-border)] bg-white shadow-sm">

            <div class="overflow-x-auto">
                <table class="min-w-full text-right">

                    <thead class="border-b border-[var(--color-border)] bg-[var(--color-neutral-50)]">
                    <tr>
                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            سفارش
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            مشتری
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            مبلغ
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            وضعیت
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            پرداخت
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            تاریخ
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            عملیات
                        </th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-[var(--color-border)]">

                    @forelse($orders as $order)

                        @php
                            $statusClasses = [
                                'pending' => 'bg-amber-50 text-amber-700',
                                'processing' => 'bg-blue-50 text-blue-700',
                                'shipped' => 'bg-indigo-50 text-indigo-700',
                                'delivered' => 'bg-emerald-50 text-emerald-700',
                                'cancelled' => 'bg-red-50 text-red-700',
                            ];

                            $paymentClasses = [
                                'pending' => 'bg-amber-50 text-amber-700',
                                'paid' => 'bg-emerald-50 text-emerald-700',
                                'failed' => 'bg-red-50 text-red-700',
                                'refunded' => 'bg-purple-50 text-purple-700',
                            ];
                        @endphp

                        <tr class="transition hover:bg-[var(--color-neutral-50)]">

                            {{-- Order --}}
                            <td class="px-5 py-4">
                                <div class="font-black text-[var(--color-text-primary)]">
                                    #{{ $order->order_number }}
                                </div>

                                <div class="mt-1 text-xs text-[var(--color-text-muted)]">
                                    {{ number_format($order->items_count ?? $order->orderItems?->count() ?? 0) }}
                                    آیتم
                                </div>
                            </td>

                            {{-- Customer --}}
                            <td class="px-5 py-4">
                                <div class="font-bold text-[var(--color-text-primary)]">
                                    {{ $order->user?->name ?? $order->shipping_full_name }}
                                </div>

                                <div class="mt-1 text-xs text-[var(--color-text-muted)]">
                                    {{ $order->shipping_phone }}
                                </div>
                            </td>

                            {{-- Total --}}
                            <td class="px-5 py-4">
                                <div class="font-black text-[var(--color-text-primary)]">
                                    {{ number_format($order->total) }}
                                </div>

                                <div class="mt-1 text-xs text-[var(--color-text-muted)]">
                                    تومان
                                </div>
                            </td>

                            {{-- Order Status --}}
                            <td class="px-5 py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $statusLabels[$order->status] ?? $order->status }}
                                </span>
                            </td>

                            {{-- Payment --}}
                            <td class="px-5 py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $paymentClasses[$order->payment_status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $paymentStatusLabels[$order->payment_status] ?? $order->payment_status }}
                                </span>
                            </td>

                            {{-- Date --}}
                            <td class="px-5 py-4">
                                <div class="text-sm font-bold text-[var(--color-text-primary)]">
                                    {{ $order->created_at->locale('fa')->translatedFormat('Y/m/d') }}
                                </div>

                                <div class="mt-1 text-xs text-[var(--color-text-muted)]">
                                    {{ $order->created_at->locale('fa')->translatedFormat('H:i') }}
                                </div>
                            </td>

                            {{-- Action --}}
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="rounded-lg bg-[var(--color-brand-50)] px-4 py-2 text-xs font-bold text-[var(--color-brand-700)] transition hover:bg-[var(--color-brand-100)]">
                                    مشاهده
                                </a>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">

                                <div class="mx-auto max-w-sm">

                                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[var(--color-neutral-100)]">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-8 w-8 text-[var(--color-text-muted)]"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.5">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M9 5h6m-7 4h8m-8 4h8m-8 4h5M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                                        </svg>
                                    </div>

                                    <h3 class="mt-4 text-lg font-black text-[var(--color-text-primary)]">
                                        سفارشی پیدا نشد
                                    </h3>

                                    <p class="mt-2 text-sm text-[var(--color-text-secondary)]">
                                        هنوز سفارشی ثبت نشده یا فیلترهای فعلی نتیجه‌ای ندارند.
                                    </p>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
                <div class="border-t border-[var(--color-border)] px-5 py-4">
                    {{ $orders->withQueryString()->links() }}
                </div>
            @endif

        </div>

    </div>
@endsection
