@extends('layouts.app')

@section('title', 'سفارش‌های من | Farzin')

@section('content')

    <section class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">

        <div class="mb-10">

            <div class="text-xs font-bold uppercase tracking-[0.25em] text-[#817d45]">
                My Orders
            </div>

            <h1 class="mt-3 text-3xl font-black tracking-tight text-[#292b24] sm:text-4xl">
                سفارش‌های من
            </h1>

            <p class="mt-2 text-sm text-[#6f7168]">
                تاریخچه سفارش‌ها و وضعیت ارسال را اینجا ببین.
            </p>

        </div>


        @if($orders->count())

            <div class="space-y-4">

                @foreach($orders as $order)

                    @php
                        $statusClass = match($order->status) {
                            'delivered' => 'bg-emerald-50 text-emerald-700',
                            'cancelled' => 'bg-red-50 text-red-700',
                            'shipped' => 'bg-blue-50 text-blue-700',
                            default => 'bg-[#f0f0e7] text-[#686438]',
                        };

                        $statusLabel = match($order->status) {
                            'pending' => 'در انتظار پرداخت',
                            'processing' => 'در حال پردازش',
                            'shipped' => 'ارسال شده',
                            'delivered' => 'تحویل شده',
                            'cancelled' => 'لغو شده',
                            default => $order->status,
                        };
                    @endphp

                    <a
                        href="{{ route('customer.orders.show', $order) }}"
                        class="group block rounded-[2rem] border border-[#e7e7df] bg-white p-5 transition duration-300 hover:-translate-y-0.5 hover:border-[#d8d9ce] hover:shadow-lg hover:shadow-[#d5d6ca]/50 sm:p-6"
                    >

                        <div class="flex flex-col gap-5 lg:flex-row lg:items-center">

                            <div class="flex min-w-0 flex-1 items-center gap-4">

                                <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-[#f0f0e7] text-sm font-black text-[#686438]">
                                    #
                                </div>

                                <div class="min-w-0">

                                    <div class="flex flex-wrap items-center gap-2">

                                        <h2 class="text-sm font-black text-[#292b24]">
                                            {{ $order->order_number }}
                                        </h2>

                                        <span class="rounded-full px-3 py-1.5 text-[10px] font-bold {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>

                                    </div>

                                    <div class="mt-2 text-xs text-[#929489]">
                                        {{ $order->created_at?->format('Y/m/d H:i') }}
                                    </div>

                                </div>

                            </div>


                            <div class="flex items-center justify-between gap-8 border-t border-[#efefe9] pt-4 sm:border-0 sm:pt-0">

                                <div>

                                    <div class="text-[10px] text-[#929489]">
                                        مبلغ
                                    </div>

                                    <div class="mt-1 text-sm font-black text-[#292b24]">
                                        {{ number_format($order->total) }}
                                        <span class="text-[10px] font-semibold text-[#929489]">
                                        تومان
                                    </span>
                                    </div>

                                </div>

                                <div>

                                    <div class="text-[10px] text-[#929489]">
                                        اقلام
                                    </div>

                                    <div class="mt-1 text-sm font-black text-[#292b24]">
                                        {{ $order->items_count }}
                                    </div>

                                </div>

                                <span class="text-gray-300 transition group-hover:-translate-x-1 group-hover:text-[#817d45]">
                                ←
                            </span>

                            </div>

                        </div>

                    </a>

                @endforeach

            </div>

            <div class="mt-10">
                {{ $orders->onEachSide(1)->links() }}
            </div>

        @else

            <div class="rounded-[2rem] border border-dashed border-gray-300 bg-white px-6 py-24 text-center">

                <div class="mx-auto flex size-16 items-center justify-center rounded-2xl bg-[#f0f0e7] text-2xl text-[#686438]">
                    🛍
                </div>

                <h2 class="mt-5 text-xl font-black text-[#292b24]">
                    سفارشی وجود ندارد
                </h2>

                <p class="mt-2 text-sm text-[#6f7168]">
                    هنوز خریدی انجام ندادی.
                </p>

                <a
                    href="{{ route('shop.index') }}"
                    class="mt-6 inline-flex rounded-2xl bg-[#686438] px-6 py-4 text-sm font-bold text-white"
                >
                    شروع خرید
                </a>

            </div>

        @endif

    </section>

@endsection
