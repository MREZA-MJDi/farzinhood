@extends('layouts.app')

@section('title', 'داشبورد من | Farzin')

@section('meta_description', 'داشبورد حساب کاربری شما در Farzin')

@section('content')

    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="relative overflow-hidden rounded-[2.5rem] bg-[#20133d] p-7 text-white shadow-xl shadow-[#3f207e]/10 sm:p-10">

            <div class="absolute -left-20 -top-20 size-64 rounded-full bg-[#7b20df]/20 blur-3xl"></div>

            <div class="absolute -bottom-28 right-1/3 size-72 rounded-full bg-fuchsia-500/10 blur-3xl"></div>

            <div class="relative flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">

                <div>
                    <div class="text-xs font-bold uppercase tracking-[0.25em] text-white/45">
                        My Account
                    </div>

                    <h1 class="mt-3 text-3xl font-black tracking-tight sm:text-4xl">
                        سلام، {{ auth()->user()->name }} 👋
                    </h1>

                    <p class="mt-3 max-w-xl text-sm leading-7 text-white/60">
                        اینجا می‌تونی سفارش‌ها، آدرس‌ها و اطلاعات حسابت رو مدیریت کنی.
                    </p>
                </div>

                <a
                    href="{{ route('customer.orders.index') }}"
                    class="inline-flex items-center justify-center gap-3 rounded-2xl bg-white px-5 py-3.5 text-sm font-bold text-[#3f207e] transition hover:-translate-y-0.5"
                >
                    مشاهده سفارش‌ها
                    <span>←</span>
                </a>

            </div>

        </div>


        {{-- Quick Stats --}}
        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

            <div class="rounded-[1.75rem] border border-gray-200 bg-white p-5">
                <div class="text-xs font-bold text-gray-400">
                    سفارش‌ها
                </div>

                <div class="mt-3 text-3xl font-black text-gray-950">
                    {{ auth()->user()->orders()->count() }}
                </div>
            </div>

            <div class="rounded-[1.75rem] border border-gray-200 bg-white p-5">
                <div class="text-xs font-bold text-gray-400">
                    علاقه‌مندی‌ها
                </div>

                <div class="mt-3 text-3xl font-black text-gray-950">
                    {{ auth()->user()->wishlists()->count() }}
                </div>
            </div>

            <div class="rounded-[1.75rem] border border-gray-200 bg-white p-5">
                <div class="text-xs font-bold text-gray-400">
                    آدرس‌ها
                </div>

                <div class="mt-3 text-3xl font-black text-gray-950">
                    {{ auth()->user()->addresses()->count() }}
                </div>
            </div>

            <div class="rounded-[1.75rem] border border-gray-200 bg-white p-5">
                <div class="text-xs font-bold text-gray-400">
                    ایمیل
                </div>

                <div class="mt-3 truncate text-sm font-black text-gray-950">
                    {{ auth()->user()->email }}
                </div>
            </div>

        </div>


        {{-- Main --}}
        <div class="mt-8 grid gap-8 lg:grid-cols-[minmax(0,1fr)_330px]">

            {{-- Recent Orders --}}
            <section class="overflow-hidden rounded-[2rem] border border-gray-200 bg-white">

                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-6">

                    <div>
                        <div class="text-xs font-bold uppercase tracking-[0.2em] text-[#7b20df]">
                            Recent Orders
                        </div>

                        <h2 class="mt-2 text-xl font-black text-gray-950">
                            سفارش‌های اخیر
                        </h2>
                    </div>

                    <a
                        href="{{ route('customer.orders.index') }}"
                        class="text-xs font-bold text-[#3f207e] hover:text-[#7b20df]"
                    >
                        همه سفارش‌ها ←
                    </a>

                </div>


                @php
                    $recentOrders = auth()->user()
                        ->orders()
                        ->withCount('items')
                        ->latest()
                        ->take(5)
                        ->get();
                @endphp


                @if($recentOrders->isNotEmpty())

                    <div class="divide-y divide-gray-100">

                        @foreach($recentOrders as $order)

                            <a
                                href="{{ route('customer.orders.show', $order) }}"
                                class="flex items-center gap-4 px-6 py-5 transition hover:bg-gray-50/70"
                            >

                                <div class="flex size-11 shrink-0 items-center justify-center rounded-2xl bg-[#f3edfb] text-xs font-black text-[#3f207e]">
                                    #
                                </div>

                                <div class="min-w-0 flex-1">

                                    <div class="flex flex-wrap items-center gap-2">

                                    <span class="text-sm font-black text-gray-900">
                                        {{ $order->order_number }}
                                    </span>

                                        <span class="rounded-full bg-gray-100 px-2.5 py-1 text-[10px] font-bold text-gray-500">
                                        {{ $order->items_count }} آیتم
                                    </span>

                                    </div>

                                    <div class="mt-1 text-xs text-gray-400">
                                        {{ $order->created_at?->format('Y/m/d H:i') }}
                                    </div>

                                </div>

                                <div class="text-left">

                                    <div class="text-sm font-black text-gray-950">
                                        {{ number_format($order->total) }}
                                        <span class="text-[10px] font-semibold text-gray-400">
                                        تومان
                                    </span>
                                    </div>

                                    <div class="mt-1 text-[10px] font-bold
                                    {{ $order->status === 'delivered' ? 'text-emerald-600' : ($order->status === 'cancelled' ? 'text-red-600' : 'text-[#7b20df]') }}"
                                    >
                                        {{ match($order->status) {
                                            'pending' => 'در انتظار پرداخت',
                                            'processing' => 'در حال پردازش',
                                            'shipped' => 'ارسال شده',
                                            'delivered' => 'تحویل شده',
                                            'cancelled' => 'لغو شده',
                                            default => $order->status,
                                        } }}
                                    </div>

                                </div>

                                <span class="hidden text-gray-300 sm:block">
                                ←
                            </span>

                            </a>

                        @endforeach

                    </div>

                @else

                    <div class="px-6 py-16 text-center">

                        <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-[#f3edfb] text-[#3f207e]">
                            🛍
                        </div>

                        <h3 class="mt-4 text-base font-black text-gray-950">
                            هنوز سفارشی ثبت نکردی
                        </h3>

                        <p class="mt-2 text-xs leading-6 text-gray-500">
                            اولین خریدت رو از Farzin شروع کن.
                        </p>

                        <a
                            href="{{ route('shop.index') }}"
                            class="mt-5 inline-flex rounded-xl bg-[#3f207e] px-5 py-3 text-xs font-bold text-white"
                        >
                            رفتن به فروشگاه
                        </a>

                    </div>

                @endif

            </section>


            {{-- Account Links --}}
            <aside class="space-y-4">

                <div class="rounded-[2rem] border border-gray-200 bg-white p-5">

                    <div class="text-xs font-bold uppercase tracking-[0.2em] text-[#7b20df]">
                        Account
                    </div>

                    <h2 class="mt-2 text-xl font-black text-gray-950">
                        مدیریت حساب
                    </h2>


                    <div class="mt-5 space-y-2">

                        <a
                            href="{{ route('customer.orders.index') }}"
                            class="flex items-center justify-between rounded-2xl bg-gray-50 px-4 py-4 text-sm font-bold text-gray-700 transition hover:bg-[#f3edfb] hover:text-[#3f207e]"
                        >
                            <span>سفارش‌های من</span>
                            <span>←</span>
                        </a>

                        <a
                            href="{{ route('customer.wishlist.index') }}"
                            class="flex items-center justify-between rounded-2xl bg-gray-50 px-4 py-4 text-sm font-bold text-gray-700 transition hover:bg-[#f3edfb] hover:text-[#3f207e]"
                        >
                            <span>علاقه‌مندی‌ها</span>
                            <span>←</span>
                        </a>

                        <a
                            href="{{ route('customer.addresses.index') }}"
                            class="flex items-center justify-between rounded-2xl bg-gray-50 px-4 py-4 text-sm font-bold text-gray-700 transition hover:bg-[#f3edfb] hover:text-[#3f207e]"
                        >
                            <span>آدرس‌ها</span>
                            <span>←</span>
                        </a>

                        <a
                            href="{{ route('customer.settings.index') }}"
                            class="flex items-center justify-between rounded-2xl bg-gray-50 px-4 py-4 text-sm font-bold text-gray-700 transition hover:bg-[#f3edfb] hover:text-[#3f207e]"
                        >
                            <span>تنظیمات حساب</span>
                            <span>←</span>
                        </a>

                    </div>

                </div>


                {{-- Logout --}}
                <div class="rounded-[2rem] border border-red-100 bg-red-50/70 p-5">

                    <div class="text-sm font-black text-red-800">
                        خروج از حساب
                    </div>

                    <p class="mt-2 text-xs leading-6 text-red-600/70">
                        برای حفظ امنیت، بعد از استفاده از حساب خارج شو.
                    </p>

                    <form
                        action="{{ route('logout') }}"
                        method="POST"
                        class="mt-4"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="w-full rounded-xl border border-red-200 bg-white px-4 py-3 text-xs font-bold text-red-700 transition hover:bg-red-100"
                        >
                            خروج
                        </button>
                    </form>

                </div>

            </aside>

        </div>

    </section>

@endsection
