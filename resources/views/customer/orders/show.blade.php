@extends('layouts.app')

@section('title', 'جزئیات سفارش ' . $order->order_number . ' | Farzin')

@section('content')

    <section class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <div class="flex flex-wrap items-center gap-2 text-xs text-gray-400">

            <a
                href="{{ route('customer.orders.index') }}"
                class="hover:text-[#7b20df]"
            >
                سفارش‌های من
            </a>

            <span>/</span>

            <span class="font-bold text-gray-700">
            {{ $order->order_number }}
        </span>

        </div>


        {{-- Header --}}
        <div class="mt-8 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">

            <div>

                <div class="text-xs font-bold uppercase tracking-[0.25em] text-[#7b20df]">
                    Order Details
                </div>

                <h1 class="mt-3 text-3xl font-black tracking-tight text-gray-950">
                    {{ $order->order_number }}
                </h1>

                <p class="mt-2 text-sm text-gray-500">
                    ثبت شده در {{ $order->created_at?->format('Y/m/d H:i') }}
                </p>

            </div>

            @php
                $statusLabel = match($order->status) {
                    'pending' => 'در انتظار پرداخت',
                    'processing' => 'در حال پردازش',
                    'shipped' => 'ارسال شده',
                    'delivered' => 'تحویل شده',
                    'cancelled' => 'لغو شده',
                    default => $order->status,
                };

                $paymentLabel = match($order->payment_status) {
                    'paid' => 'پرداخت شده',
                    'pending' => 'در انتظار پرداخت',
                    'failed' => 'پرداخت ناموفق',
                    'refunded' => 'بازگشت وجه',
                    default => $order->payment_status,
                };
            @endphp

            <div class="flex flex-wrap gap-2">

            <span class="rounded-full bg-[#f3edfb] px-4 py-2 text-xs font-bold text-[#3f207e]">
                {{ $statusLabel }}
            </span>

                <span class="rounded-full bg-gray-100 px-4 py-2 text-xs font-bold text-gray-600">
                {{ $paymentLabel }}
            </span>

            </div>

        </div>


        {{-- Payment CTA --}}
        @if($order->payment_status !== 'paid' && $order->status === 'pending')

            <div class="mt-8 flex flex-col gap-4 rounded-[2rem] border border-[#d8c8eb] bg-[#f8f4fc] p-6 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <div class="text-sm font-black text-[#3f207e]">
                        پرداخت این سفارش هنوز تکمیل نشده
                    </div>

                    <p class="mt-1 text-xs leading-6 text-gray-500">
                        برای ادامه پردازش سفارش، پرداخت را کامل کن.
                    </p>

                </div>

                <a
                    href="{{ route('customer.payment.start', $order) }}"
                    class="inline-flex items-center justify-center gap-3 rounded-xl bg-[#3f207e] px-5 py-3.5 text-xs font-bold text-white transition hover:bg-[#321866]"
                >
                    ادامه پرداخت
                    <span>←</span>
                </a>

            </div>

        @endif


        <div class="mt-8 grid gap-8 lg:grid-cols-[minmax(0,1fr)_340px]">

            {{-- Left --}}
            <div class="space-y-8">


                {{-- Items --}}
                <section class="overflow-hidden rounded-[2rem] border border-gray-200 bg-white">

                    <div class="border-b border-gray-100 px-6 py-6">

                        <h2 class="text-xl font-black text-gray-950">
                            محصولات سفارش
                        </h2>

                    </div>


                    <div class="divide-y divide-gray-100">

                        @foreach($order->items as $item)

                            <div class="flex gap-4 px-6 py-6">

                                <div class="flex size-14 shrink-0 items-center justify-center rounded-2xl bg-[#f5f2f8] text-xs font-black text-[#3f207e]">
                                    {{ $item->quantity }}×
                                </div>

                                <div class="min-w-0 flex-1">

                                    @if($item->product)
                                        <a
                                            href="{{ route('products.show', $item->product) }}"
                                            class="text-sm font-black text-gray-950 hover:text-[#3f207e]"
                                        >
                                            {{ $item->product_name }}
                                        </a>
                                    @else
                                        <div class="text-sm font-black text-gray-950">
                                            {{ $item->product_name }}
                                        </div>
                                    @endif

                                    @if($item->product_sku)
                                        <div class="mt-1 text-[11px] text-gray-400">
                                            SKU:
                                            <span class="font-mono">
                                            {{ $item->product_sku }}
                                        </span>
                                        </div>
                                    @endif

                                </div>

                                <div class="text-left">

                                    <div class="text-sm font-black text-gray-950">
                                        {{ number_format($item->total) }}
                                    </div>

                                    <div class="mt-1 text-[10px] text-gray-400">
                                        تومان
                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </section>


                {{-- Shipping --}}
                <section class="rounded-[2rem] border border-gray-200 bg-white p-6">

                    <div class="text-xs font-bold uppercase tracking-[0.2em] text-[#7b20df]">
                        Shipping Address
                    </div>

                    <h2 class="mt-3 text-xl font-black text-gray-950">
                        آدرس ارسال
                    </h2>

                    <div class="mt-5 grid gap-4 sm:grid-cols-2">

                        <div>
                            <div class="text-[11px] text-gray-400">
                                گیرنده
                            </div>

                            <div class="mt-1 text-sm font-bold text-gray-900">
                                {{ $order->shipping_full_name }}
                            </div>
                        </div>

                        <div>
                            <div class="text-[11px] text-gray-400">
                                تلفن
                            </div>

                            <div class="mt-1 text-sm font-bold text-gray-900">
                                {{ $order->shipping_phone }}
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <div class="text-[11px] text-gray-400">
                                آدرس
                            </div>

                            <div class="mt-1 text-sm leading-7 text-gray-700">
                                {{ $order->shipping_province }}
                                ،
                                {{ $order->shipping_city }}
                                <br>
                                {{ $order->shipping_address }}
                            </div>
                        </div>

                        @if($order->shipping_postal_code)
                            <div>
                                <div class="text-[11px] text-gray-400">
                                    کد پستی
                                </div>

                                <div class="mt-1 font-mono text-sm font-bold text-gray-900">
                                    {{ $order->shipping_postal_code }}
                                </div>
                            </div>
                        @endif

                    </div>

                </section>


                {{-- Timeline --}}
                <section class="rounded-[2rem] border border-gray-200 bg-white p-6">

                    <div class="text-xs font-bold uppercase tracking-[0.2em] text-[#7b20df]">
                        Timeline
                    </div>

                    <h2 class="mt-3 text-xl font-black text-gray-950">
                        روند سفارش
                    </h2>


                    <div class="mt-7 space-y-6">

                        @foreach($order->statusHistories->sortBy('created_at') as $history)

                            @php
                                $historyLabel = match($history->to_status) {
                                    'pending' => 'در انتظار پرداخت',
                                    'processing' => 'در حال پردازش',
                                    'shipped' => 'ارسال شده',
                                    'delivered' => 'تحویل شده',
                                    'cancelled' => 'لغو شده',
                                    default => $history->to_status,
                                };
                            @endphp

                            <div class="relative flex gap-4">

                                <div class="flex shrink-0 flex-col items-center">

                                    <div class="flex size-9 items-center justify-center rounded-full bg-[#f3edfb] text-xs font-black text-[#3f207e]">
                                        ✓
                                    </div>

                                    @unless($loop->last)
                                        <div class="mt-2 h-full w-px bg-gray-200"></div>
                                    @endunless

                                </div>

                                <div class="pb-2">

                                    <div class="text-sm font-black text-gray-900">
                                        {{ $historyLabel }}
                                    </div>

                                    <div class="mt-1 text-[11px] text-gray-400">
                                        {{ $history->created_at?->format('Y/m/d H:i') }}
                                    </div>

                                    @if($history->note)
                                        <p class="mt-2 text-xs leading-6 text-gray-500">
                                            {{ $history->note }}
                                        </p>
                                    @endif

                                </div>

                            </div>

                        @endforeach

                    </div>

                </section>

            </div>


            {{-- Summary --}}
            <aside class="lg:sticky lg:top-28 lg:self-start">

                <div class="overflow-hidden rounded-[2rem] border border-gray-200 bg-white">

                    <div class="border-b border-gray-100 px-6 py-6">

                        <div class="text-xs font-bold uppercase tracking-[0.2em] text-[#7b20df]">
                            Summary
                        </div>

                        <h2 class="mt-2 text-xl font-black text-gray-950">
                            خلاصه مالی
                        </h2>

                    </div>


                    <div class="space-y-5 px-6 py-6">

                        <div class="flex justify-between gap-4 text-sm">
                        <span class="text-gray-500">
                            مبلغ کالاها
                        </span>

                            <span class="font-bold">
                            {{ number_format($order->subtotal) }} تومان
                        </span>
                        </div>

                        @if($order->discount > 0)

                            <div class="flex justify-between gap-4 text-sm">
                            <span class="text-gray-500">
                                تخفیف
                            </span>

                                <span class="font-bold text-emerald-600">
                                − {{ number_format($order->discount) }} تومان
                            </span>
                            </div>

                        @endif

                        <div class="flex justify-between gap-4 text-sm">
                        <span class="text-gray-500">
                            ارسال
                        </span>

                            <span class="font-bold">
                            @if($order->shipping_cost > 0)
                                    {{ number_format($order->shipping_cost) }} تومان
                                @else
                                    <span class="text-emerald-600">
                                    رایگان
                                </span>
                                @endif
                        </span>
                        </div>


                        <div class="border-t border-dashed border-gray-200 pt-5">

                            <div class="text-xs text-gray-400">
                                مبلغ نهایی
                            </div>

                            <div class="mt-2 text-2xl font-black text-gray-950">
                                {{ number_format($order->total) }}
                                <span class="text-xs font-semibold text-gray-400">
                                تومان
                            </span>
                            </div>

                        </div>

                        <a
                            href="{{ route('customer.orders.index') }}"
                            class="flex w-full items-center justify-center rounded-xl border border-gray-200 px-4 py-3.5 text-xs font-bold text-gray-700 transition hover:border-[#7b20df] hover:text-[#7b20df]"
                        >
                            بازگشت به سفارش‌ها
                        </a>

                    </div>

                </div>

            </aside>

        </div>

    </section>

@endsection
