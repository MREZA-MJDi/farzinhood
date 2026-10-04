@extends('layouts.admin')

@section('title', 'جزئیات سفارش')

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

        $paymentStatusLabels = [
            'pending' => 'در انتظار پرداخت',
            'paid' => 'پرداخت شده',
            'failed' => 'ناموفق',
            'refunded' => 'برگشت وجه',
        ];

        $paymentClasses = [
            'pending' => 'bg-amber-50 text-amber-700',
            'paid' => 'bg-emerald-50 text-emerald-700',
            'failed' => 'bg-red-50 text-red-700',
            'refunded' => 'bg-purple-50 text-purple-700',
        ];
    @endphp

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <div class="flex flex-wrap items-center gap-3">

                    <h1 class="text-2xl font-black text-[var(--color-text-primary)]">
                        سفارش #{{ $order->order_number }}
                    </h1>

                    <span class="rounded-full px-3 py-1 text-xs font-bold {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-700' }}">
                        {{ $statusLabels[$order->status] ?? $order->status }}
                    </span>

                </div>

                <p class="mt-2 text-sm text-[var(--color-text-secondary)]">
                    ثبت شده در
                    {{ $order->created_at->locale('fa')->translatedFormat('l، j F Y - H:i') }}
                </p>
            </div>

            <a href="{{ route('admin.orders.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-[var(--color-border)] bg-white px-5 py-3 text-sm font-bold text-[var(--color-text-primary)] hover:bg-[var(--color-neutral-50)]">
                بازگشت به سفارش‌ها
            </a>

        </div>


        {{-- Order Status --}}
        <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

            <div class="mb-5">
                <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                    تغییر وضعیت سفارش
                </h2>

                <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                    وضعیت سفارش را به‌روزرسانی کنید.
                </p>
            </div>

            <form action="{{ route('admin.orders.status', $order) }}"
                  method="POST"
                  class="grid grid-cols-1 gap-4 md:grid-cols-3">

                @csrf
                @method('PATCH')

                <div>
                    <label for="status"
                           class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                        وضعیت جدید
                    </label>

                    <select id="status"
                            name="status"
                            class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm outline-none focus:border-[var(--color-brand-600)]">

                        @foreach($statusLabels as $status => $label)
                            <option value="{{ $status }}"
                                    @selected($order->status === $status)>
                                {{ $label }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div>
                    <label for="note"
                           class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                        یادداشت
                    </label>

                    <input type="text"
                           id="note"
                           name="note"
                           value="{{ old('note') }}"
                           placeholder="مثلاً سفارش تحویل پست شد"
                           class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm outline-none focus:border-[var(--color-brand-600)]">

                    @error('note')
                    <p class="mt-2 text-xs text-red-600">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="flex items-end">
                    <button type="submit"
                            class="w-full rounded-xl bg-[var(--color-brand-600)] px-5 py-3 text-sm font-bold text-white hover:bg-[var(--color-brand-700)]">
                        بروزرسانی وضعیت
                    </button>
                </div>

            </form>

        </div>


        {{-- Main Grid --}}
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

            {{-- Order Items --}}
            <div class="overflow-hidden rounded-2xl border border-[var(--color-border)] bg-white shadow-sm xl:col-span-2">

                <div class="border-b border-[var(--color-border)] p-5">
                    <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                        اقلام سفارش
                    </h2>
                </div>

                <div class="divide-y divide-[var(--color-border)]">

                    @forelse($order->items as $item)

                        <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between">

                            <div class="min-w-0">
                                <div class="font-black text-[var(--color-text-primary)]">
                                    {{ $item->product_name }}
                                </div>

                                @if($item->product_sku)
                                    <div class="mt-1 text-xs text-[var(--color-text-muted)]"
                                         dir="ltr">
                                        SKU: {{ $item->product_sku }}
                                    </div>
                                @endif

                                <div class="mt-2 text-sm text-[var(--color-text-secondary)]">
                                    {{ number_format($item->quantity) }}
                                    ×
                                    {{ number_format($item->unit_price) }}
                                    تومان
                                </div>
                            </div>

                            <div class="text-left sm:text-right">
                                <div class="font-black text-[var(--color-text-primary)]">
                                    {{ number_format($item->total) }}
                                    تومان
                                </div>
                            </div>

                        </div>

                    @empty

                        <div class="p-10 text-center text-sm text-[var(--color-text-secondary)]">
                            آیتمی برای این سفارش ثبت نشده است.
                        </div>

                    @endforelse

                </div>

                {{-- Totals --}}
                <div class="border-t border-[var(--color-border)] bg-[var(--color-neutral-50)] p-5">

                    <div class="space-y-3">

                        <div class="flex items-center justify-between text-sm">
                            <span class="text-[var(--color-text-secondary)]">
                                مجموع کالاها
                            </span>

                            <span class="font-bold text-[var(--color-text-primary)]">
                                {{ number_format($order->subtotal) }} تومان
                            </span>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <span class="text-[var(--color-text-secondary)]">
                                تخفیف
                            </span>

                            <span class="font-bold text-[var(--color-text-primary)]">
                                {{ number_format($order->discount) }} تومان
                            </span>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <span class="text-[var(--color-text-secondary)]">
                                هزینه ارسال
                            </span>

                            <span class="font-bold text-[var(--color-text-primary)]">
                                {{ number_format($order->shipping_cost) }} تومان
                            </span>
                        </div>

                        <div class="border-t border-[var(--color-border)] pt-3">
                            <div class="flex items-center justify-between">
                                <span class="font-black text-[var(--color-text-primary)]">
                                    مبلغ نهایی
                                </span>

                                <span class="text-xl font-black text-[var(--color-brand-700)]">
                                    {{ number_format($order->total) }} تومان
                                </span>
                            </div>
                        </div>

                    </div>

                </div>

            </div>


            {{-- Customer / Payment --}}
            <div class="space-y-6">

                {{-- Customer --}}
                <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

                    <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                        مشتری
                    </h2>

                    <div class="mt-5 space-y-4">

                        <div>
                            <div class="text-xs text-[var(--color-text-muted)]">
                                نام
                            </div>

                            <div class="mt-1 font-bold text-[var(--color-text-primary)]">
                                {{ $order->user?->name ?? $order->shipping_full_name }}
                            </div>
                        </div>

                        @if($order->user)
                            <div>
                                <div class="text-xs text-[var(--color-text-muted)]">
                                    ایمیل
                                </div>

                                <div class="mt-1 text-sm text-[var(--color-text-secondary)]"
                                     dir="ltr">
                                    {{ $order->user->email }}
                                </div>
                            </div>
                        @endif

                        <div>
                            <div class="text-xs text-[var(--color-text-muted)]">
                                شماره تماس
                            </div>

                            <div class="mt-1 text-sm text-[var(--color-text-secondary)]"
                                 dir="ltr">
                                {{ $order->shipping_phone }}
                            </div>
                        </div>

                    </div>

                </div>


                {{-- Shipping Address --}}
                <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

                    <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                        آدرس ارسال
                    </h2>

                    <div class="mt-5 space-y-3 text-sm">

                        <div class="font-bold text-[var(--color-text-primary)]">
                            {{ $order->shipping_full_name }}
                        </div>

                        <div class="text-[var(--color-text-secondary)]">
                            {{ $order->shipping_phone }}
                        </div>

                        @if($order->shipping_country)
                            <div class="text-[var(--color-text-secondary)]">
                                {{ $order->shipping_country }}
                            </div>
                        @endif

                        <div class="text-[var(--color-text-secondary)]">
                            {{ $order->shipping_province }}
                            -
                            {{ $order->shipping_city }}
                        </div>

                        @if($order->shipping_postal_code)
                            <div class="text-[var(--color-text-secondary)]">
                                کد پستی:
                                <span dir="ltr">
                                    {{ $order->shipping_postal_code }}
                                </span>
                            </div>
                        @endif

                        <div class="rounded-xl bg-[var(--color-neutral-50)] p-4 leading-7 text-[var(--color-text-secondary)]">
                            {{ $order->shipping_address }}
                        </div>

                    </div>

                </div>


                {{-- Payment --}}
                <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

                    <div class="flex items-center justify-between gap-3">

                        <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                            پرداخت
                        </h2>

                        <span class="rounded-full px-3 py-1 text-xs font-bold {{ $paymentClasses[$order->payment_status] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ $paymentStatusLabels[$order->payment_status] ?? $order->payment_status }}
                        </span>

                    </div>

                    @if($order->latestPayment)
                        <div class="mt-5 space-y-4">

                            <div>
                                <div class="text-xs text-[var(--color-text-muted)]">
                                    درگاه
                                </div>

                                <div class="mt-1 text-sm font-bold text-[var(--color-text-primary)]">
                                    {{ $order->latestPayment->gateway }}
                                </div>
                            </div>

                            @if($order->latestPayment->transaction_id)
                                <div>
                                    <div class="text-xs text-[var(--color-text-muted)]">
                                        شناسه تراکنش
                                    </div>

                                    <div class="mt-1 break-all text-sm text-[var(--color-text-secondary)]"
                                         dir="ltr">
                                        {{ $order->latestPayment->transaction_id }}
                                    </div>
                                </div>
                            @endif

                            @if($order->latestPayment->tracking_code)
                                <div>
                                    <div class="text-xs text-[var(--color-text-muted)]">
                                        کد پیگیری
                                    </div>

                                    <div class="mt-1 text-sm font-bold text-[var(--color-text-secondary)]"
                                         dir="ltr">
                                        {{ $order->latestPayment->tracking_code }}
                                    </div>
                                </div>
                            @endif

                            <div>
                                <div class="text-xs text-[var(--color-text-muted)]">
                                    مبلغ
                                </div>

                                <div class="mt-1 text-sm font-bold text-[var(--color-text-primary)]">
                                    {{ number_format($order->latestPayment->amount) }}
                                    تومان
                                </div>
                            </div>

                            @if($order->latestPayment->paid_at)
                                <div>
                                    <div class="text-xs text-[var(--color-text-muted)]">
                                        تاریخ پرداخت
                                    </div>

                                    <div class="mt-1 text-sm text-[var(--color-text-secondary)]">
                                        {{ $order->latestPayment->paid_at->locale('fa')->translatedFormat('Y/m/d H:i') }}
                                    </div>
                                </div>
                            @endif

                        </div>
                    @else
                        <div class="mt-5 rounded-xl bg-[var(--color-neutral-50)] p-4 text-sm text-[var(--color-text-secondary)]">
                            هنوز تراکنش پرداختی برای این سفارش ثبت نشده است.
                        </div>
                    @endif

                </div>

            </div>

        </div>


        {{-- Status History --}}
        <div class="rounded-2xl border border-[var(--color-border)] bg-white shadow-sm">

            <div class="border-b border-[var(--color-border)] p-5">
                <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                    تاریخچه وضعیت سفارش
                </h2>
            </div>

            <div class="divide-y divide-[var(--color-border)]">

                @forelse($order->statusHistories as $history)

                    <div class="flex flex-col gap-3 p-5 md:flex-row md:items-start md:justify-between">

                        <div>

                            <div class="flex flex-wrap items-center gap-2">

                                @if($history->from_status)
                                    <span class="rounded-full bg-[var(--color-neutral-100)] px-3 py-1 text-xs font-bold text-[var(--color-text-secondary)]">
                                        {{ $statusLabels[$history->from_status] ?? $history->from_status }}
                                    </span>

                                    <span class="text-[var(--color-text-muted)]">
                                        ←
                                    </span>
                                @endif

                                <span class="rounded-full bg-[var(--color-brand-50)] px-3 py-1 text-xs font-bold text-[var(--color-brand-700)]">
                                    {{ $statusLabels[$history->to_status] ?? $history->to_status }}
                                </span>

                            </div>

                            @if($history->note)
                                <p class="mt-3 text-sm leading-6 text-[var(--color-text-secondary)]">
                                    {{ $history->note }}
                                </p>
                            @endif

                            <div class="mt-2 text-xs text-[var(--color-text-muted)]">
                                توسط:
                                {{ $history->changedBy?->name ?? 'سیستم' }}
                            </div>

                        </div>

                        <div class="text-xs text-[var(--color-text-muted)]">
                            {{ $history->created_at->locale('fa')->translatedFormat('Y/m/d H:i') }}
                        </div>

                    </div>

                @empty

                    <div class="p-10 text-center text-sm text-[var(--color-text-secondary)]">
                        تاریخچه‌ای برای این سفارش ثبت نشده است.
                    </div>

                @endforelse

            </div>

        </div>


        {{-- Notes --}}
        @if($order->notes)
            <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

                <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                    یادداشت سفارش
                </h2>

                <div class="mt-4 rounded-xl bg-[var(--color-neutral-50)] p-4 text-sm leading-7 text-[var(--color-text-secondary)]">
                    {{ $order->notes }}
                </div>

            </div>
        @endif

    </div>
@endsection
