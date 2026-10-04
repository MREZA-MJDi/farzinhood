@extends('layouts.app')

@section('title', 'تکمیل سفارش | Farzin')

@section(
    'meta_description',
    'تکمیل سفارش و انتخاب آدرس و روش پرداخت در Farzin'
)

@section('content')

    <div
        x-data="{
        selectedAddress: @js(
            old(
                'address_id',
                $addresses->firstWhere('is_default', true)?->id
                    ?? $addresses->first()?->id
            )
        ),
        paymentMethod: @js(
            old('payment_method', 'gateway')
        )
    }"
        class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8"
    >

        {{-- =========================================================
            HEADER
        ========================================================== --}}
        <div class="mb-10">

            <div class="text-xs font-bold uppercase tracking-[0.25em] text-[#7b20df]">
                Checkout
            </div>

            <h1 class="mt-3 text-3xl font-black tracking-tight text-gray-950 sm:text-4xl">
                تکمیل سفارش
            </h1>

            <p class="mt-2 text-sm leading-7 text-gray-500">
                آدرس ارسال و روش پرداخت را انتخاب کن تا سفارشت ثبت شود.
            </p>

        </div>


        {{-- =========================================================
            PROGRESS
        ========================================================== --}}
        <div class="mb-10 overflow-hidden rounded-[1.75rem] border border-gray-200 bg-white p-4 sm:p-5">

            <div class="grid grid-cols-3 gap-3">

                <div class="flex items-center gap-3 rounded-2xl bg-[#f3edfb] px-4 py-3">

                    <div class="flex size-9 shrink-0 items-center justify-center rounded-full bg-[#3f207e] text-xs font-black text-white">
                        01
                    </div>

                    <div class="hidden sm:block">
                        <div class="text-[10px] font-bold text-gray-400">
                            مرحله اول
                        </div>

                        <div class="mt-0.5 text-xs font-black text-gray-900">
                            آدرس ارسال
                        </div>
                    </div>

                </div>


                <div class="flex items-center gap-3 rounded-2xl bg-gray-50 px-4 py-3">

                    <div class="flex size-9 shrink-0 items-center justify-center rounded-full border border-gray-200 bg-white text-xs font-black text-gray-500">
                        02
                    </div>

                    <div class="hidden sm:block">
                        <div class="text-[10px] font-bold text-gray-400">
                            مرحله دوم
                        </div>

                        <div class="mt-0.5 text-xs font-black text-gray-900">
                            پرداخت
                        </div>
                    </div>

                </div>


                <div class="flex items-center gap-3 rounded-2xl bg-gray-50 px-4 py-3">

                    <div class="flex size-9 shrink-0 items-center justify-center rounded-full border border-gray-200 bg-white text-xs font-black text-gray-500">
                        03
                    </div>

                    <div class="hidden sm:block">
                        <div class="text-[10px] font-bold text-gray-400">
                            مرحله سوم
                        </div>

                        <div class="mt-0.5 text-xs font-black text-gray-900">
                            تأیید
                        </div>
                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
            CHECKOUT FORM
        ========================================================== --}}
        <form
            action="{{ route('customer.checkout.store') }}"
            method="POST"
        >
            @csrf

            <div class="grid gap-8 xl:grid-cols-[minmax(0,1fr)_380px]">

                {{-- =================================================
                    LEFT COLUMN
                ================================================== --}}
                <div class="space-y-8">


                    {{-- =================================================
                        ADDRESSES
                    ================================================== --}}
                    <section class="overflow-hidden rounded-[2rem] border border-gray-200 bg-white">

                        <div class="border-b border-gray-100 px-6 py-6 sm:px-8">

                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                                <div>

                                    <div class="text-xs font-bold uppercase tracking-[0.2em] text-[#7b20df]">
                                        Delivery
                                    </div>

                                    <h2 class="mt-2 text-xl font-black text-gray-950">
                                        آدرس ارسال
                                    </h2>

                                    <p class="mt-1 text-xs leading-6 text-gray-400">
                                        یکی از آدرس‌های ذخیره‌شده را برای ارسال سفارش انتخاب کن.
                                    </p>

                                </div>

                                <a
                                    href="{{ route('customer.addresses.index') }}"
                                    class="inline-flex items-center justify-center rounded-xl border border-gray-200 px-4 py-2.5 text-xs font-bold text-gray-700 transition hover:border-[#7b20df] hover:text-[#7b20df]"
                                >
                                    مدیریت آدرس‌ها
                                </a>

                            </div>

                        </div>


                        <div class="p-6 sm:p-8">

                            @if($addresses->isNotEmpty())

                                <div class="grid gap-4 md:grid-cols-2">

                                    @foreach($addresses as $address)

                                        <label class="group block cursor-pointer">

                                            <input
                                                type="radio"
                                                name="address_id"
                                                value="{{ $address->id }}"
                                                x-model="selectedAddress"
                                                class="peer sr-only"
                                            >

                                            <div
                                                class="relative h-full rounded-[1.5rem] border-2 border-gray-200 bg-gray-50/70 p-5 transition duration-300 peer-checked:border-[#3f207e] peer-checked:bg-[#f8f4fc] peer-checked:shadow-lg peer-checked:shadow-[#3f207e]/5 group-hover:border-gray-300"
                                            >

                                                {{-- Selected Indicator --}}
                                                <div
                                                    class="absolute left-4 top-4 flex size-6 items-center justify-center rounded-full border border-gray-300 bg-white text-white transition"
                                                    :class="selectedAddress == {{ $address->id }}
                                                        ? 'border-[#3f207e] bg-[#3f207e]'
                                                        : 'border-gray-300 bg-white'"
                                                >
                                                <span
                                                    x-show="selectedAddress == {{ $address->id }}"
                                                    x-transition
                                                    class="text-xs font-black"
                                                >
                                                    ✓
                                                </span>
                                                </div>


                                                {{-- Default Badge --}}
                                                @if($address->is_default)

                                                    <div class="mb-4 inline-flex rounded-full bg-emerald-50 px-3 py-1.5 text-[10px] font-bold text-emerald-700">
                                                        آدرس پیش‌فرض
                                                    </div>

                                                @else

                                                    <div class="mb-4 h-6"></div>

                                                @endif


                                                <div class="pl-8">

                                                    <div class="text-sm font-black text-gray-950">
                                                        {{ $address->title ?: 'آدرس ارسال' }}
                                                    </div>

                                                    <div class="mt-2 text-xs font-bold text-gray-700">
                                                        {{ $address->full_name }}
                                                    </div>

                                                    <div class="mt-1 text-xs text-gray-400">
                                                        {{ $address->phone }}
                                                    </div>

                                                    <div class="mt-4 text-xs leading-7 text-gray-500">

                                                        {{ $address->province }}
                                                        ،
                                                        {{ $address->city }}

                                                        <br>

                                                        {{ $address->address }}

                                                    </div>

                                                    @if($address->postal_code)

                                                        <div class="mt-3 text-[11px] text-gray-400">

                                                            کد پستی:

                                                            <span class="font-mono font-bold text-gray-600">
                                                            {{ $address->postal_code }}
                                                        </span>

                                                        </div>

                                                    @endif

                                                </div>

                                            </div>

                                        </label>

                                    @endforeach

                                </div>

                            @else

                                <div class="rounded-[1.5rem] border border-dashed border-gray-300 bg-gray-50 px-6 py-12 text-center">

                                    <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-[#f3edfb] text-2xl font-black text-[#3f207e]">
                                        +
                                    </div>

                                    <h3 class="mt-4 text-base font-black text-gray-950">
                                        هنوز آدرسی نداری
                                    </h3>

                                    <p class="mx-auto mt-2 max-w-md text-xs leading-7 text-gray-500">
                                        برای ادامه ثبت سفارش، ابتدا یک آدرس ارسال اضافه کن.
                                    </p>

                                    <a
                                        href="{{ route('customer.addresses.index') }}"
                                        class="mt-5 inline-flex rounded-xl bg-[#3f207e] px-5 py-3 text-xs font-bold text-white transition hover:bg-[#321866]"
                                    >
                                        افزودن آدرس
                                    </a>

                                </div>

                            @endif


                            @error('address_id')

                            <div class="mt-4 rounded-xl bg-red-50 px-4 py-3 text-xs font-bold text-red-700">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>

                    </section>


                    {{-- =================================================
                        PAYMENT METHOD
                    ================================================== --}}
                    <section class="overflow-hidden rounded-[2rem] border border-gray-200 bg-white">

                        <div class="border-b border-gray-100 px-6 py-6 sm:px-8">

                            <div class="text-xs font-bold uppercase tracking-[0.2em] text-[#7b20df]">
                                Payment
                            </div>

                            <h2 class="mt-2 text-xl font-black text-gray-950">
                                روش پرداخت
                            </h2>

                        </div>


                        <div class="p-6 sm:p-8">

                            <label class="group block cursor-pointer">

                                <input
                                    type="radio"
                                    name="payment_method"
                                    value="gateway"
                                    x-model="paymentMethod"
                                    class="peer sr-only"
                                >

                                <div
                                    class="flex items-center gap-4 rounded-[1.5rem] border-2 border-gray-200 bg-gray-50/70 p-5 transition duration-300 peer-checked:border-[#3f207e] peer-checked:bg-[#f8f4fc]"
                                >

                                    <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-white text-[#3f207e] shadow-sm">

                                        <svg
                                            class="h-5 w-5"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.7"
                                        >
                                            <rect
                                                x="3"
                                                y="5"
                                                width="18"
                                                height="14"
                                                rx="2"
                                            />

                                            <path d="M3 10h18"/>

                                            <path d="M7 15h4"/>
                                        </svg>

                                    </div>


                                    <div class="flex-1">

                                        <div class="flex items-center justify-between gap-4">

                                            <div>

                                                <div class="text-sm font-black text-gray-950">
                                                    پرداخت آنلاین
                                                </div>

                                                <div class="mt-1 text-xs leading-6 text-gray-400">
                                                    پرداخت امن از طریق درگاه آنلاین
                                                </div>

                                            </div>


                                            <div
                                                class="flex size-6 items-center justify-center rounded-full border bg-white text-white transition"
                                                :class="paymentMethod === 'gateway'
                                                ? 'border-[#3f207e] bg-[#3f207e]'
                                                : 'border-gray-300'"
                                            >

                                            <span
                                                x-show="paymentMethod === 'gateway'"
                                                class="text-xs font-black"
                                            >
                                                ✓
                                            </span>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </label>


                            @error('payment_method')

                            <div class="mt-4 rounded-xl bg-red-50 px-4 py-3 text-xs font-bold text-red-700">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>

                    </section>


                    {{-- =================================================
                        ORDER NOTES
                    ================================================== --}}
                    <section class="overflow-hidden rounded-[2rem] border border-gray-200 bg-white">

                        <div class="border-b border-gray-100 px-6 py-6 sm:px-8">

                            <div class="text-xs font-bold uppercase tracking-[0.2em] text-[#7b20df]">
                                Optional
                            </div>

                            <h2 class="mt-2 text-xl font-black text-gray-950">
                                توضیحات سفارش
                            </h2>

                        </div>


                        <div class="p-6 sm:p-8">

                            <label
                                for="notes"
                                class="text-xs font-bold text-gray-700"
                            >
                                توضیحات
                            </label>

                            <textarea
                                id="notes"
                                name="notes"
                                rows="5"
                                maxlength="2000"
                                placeholder="مثلاً زمان مناسب برای تحویل یا توضیحی برای سفارش..."
                                class="mt-3 w-full resize-none rounded-2xl border border-gray-200 bg-gray-50 px-5 py-4 text-sm leading-7 outline-none transition placeholder:text-gray-400 focus:border-[#7b20df] focus:bg-white focus:ring-4 focus:ring-[#7b20df]/10"
                            >{{ old('notes') }}</textarea>

                            @error('notes')

                            <div class="mt-2 text-xs font-bold text-red-600">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>

                    </section>

                </div>


                {{-- =================================================
                    RIGHT COLUMN / SUMMARY
                ================================================== --}}
                <aside class="xl:sticky xl:top-28 xl:self-start">

                    <div class="overflow-hidden rounded-[2rem] border border-gray-200 bg-white">


                        {{-- Header --}}
                        <div class="border-b border-gray-100 px-6 py-6">

                            <div class="text-xs font-bold uppercase tracking-[0.2em] text-[#7b20df]">
                                Order Summary
                            </div>

                            <h2 class="mt-2 text-xl font-black text-gray-950">
                                خلاصه سفارش
                            </h2>

                        </div>


                        {{-- Items --}}
                        <div class="max-h-[360px] overflow-y-auto border-b border-gray-100 px-6 py-2">

                            @foreach($items as $item)

                                @php
                                    $product = $item->product;
                                    $image = $product?->primaryImage?->image;
                                    $lineTotal = (int) $item->unit_price * (int) $item->quantity;
                                @endphp

                                <div class="flex gap-4 py-5">

                                    <div class="relative h-16 w-16 shrink-0 overflow-hidden rounded-xl bg-[#f5f2f8]">

                                        @if($image)

                                            <img
                                                src="{{ asset('storage/' . $image) }}"
                                                alt="{{ $product?->name }}"
                                                class="h-full w-full object-cover"
                                                loading="lazy"
                                            >

                                        @else

                                            <div class="flex h-full w-full items-center justify-center text-gray-300">

                                                <svg
                                                    class="h-7 w-7"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="1.2"
                                                >
                                                    <path d="M3 5h18v14H3z"/>
                                                    <circle cx="8" cy="10" r="1.5"/>
                                                    <path d="m21 16-5-5-4 4-2-2-7 7"/>
                                                </svg>

                                            </div>

                                        @endif


                                        <span class="absolute bottom-1 left-1 flex size-5 items-center justify-center rounded-md bg-gray-900/80 text-[9px] font-bold text-white">
                                        {{ $item->quantity }}
                                    </span>

                                    </div>


                                    <div class="min-w-0 flex-1">

                                        <div class="line-clamp-2 text-xs font-bold leading-6 text-gray-900">
                                            {{ $item->product_name ?? $product?->name }}
                                        </div>

                                        <div class="mt-1 text-[11px] text-gray-400">
                                            {{ number_format($item->unit_price) }}
                                            تومان
                                        </div>

                                    </div>


                                    <div class="shrink-0 text-left text-xs font-black text-gray-950">

                                        {{ number_format($lineTotal) }}

                                        <span class="text-[9px] font-normal text-gray-400">
                                        تومان
                                    </span>

                                    </div>

                                </div>

                            @endforeach

                        </div>


                        {{-- =================================================
                            TOTALS
                        ================================================== --}}
                        <div class="space-y-5 px-6 py-6">

                            {{-- Subtotal --}}
                            <div class="flex items-center justify-between gap-4 text-sm">

                            <span class="text-gray-500">
                                مبلغ کالاها
                            </span>

                                <span class="font-bold text-gray-900">
                                {{ number_format($summary['subtotal']) }}
                                تومان
                            </span>

                            </div>


                            {{-- Discount --}}
                            <div class="flex items-center justify-between gap-4 text-sm">

                            <span class="text-gray-500">
                                تخفیف
                            </span>

                                @if($summary['discount'] > 0)

                                    <span class="font-bold text-emerald-600">
                                    − {{ number_format($summary['discount']) }}
                                    تومان
                                </span>

                                @else

                                    <span class="font-bold text-gray-400">
                                    —
                                </span>

                                @endif

                            </div>


                            {{-- Shipping --}}
                            <div class="flex items-center justify-between gap-4 text-sm">

                            <span class="text-gray-500">
                                هزینه ارسال
                            </span>

                                @if($summary['shipping'] > 0)

                                    <span class="font-bold text-gray-900">
                                    {{ number_format($summary['shipping']) }}
                                    تومان
                                </span>

                                @else

                                    <span class="font-bold text-emerald-600">
                                    رایگان
                                </span>

                                @endif

                            </div>


                            {{-- Final Total --}}
                            <div class="border-t border-dashed border-gray-200 pt-5">

                                <div class="flex items-end justify-between gap-4">

                                    <div>

                                        <div class="text-xs text-gray-400">
                                            مبلغ نهایی
                                        </div>

                                        <div class="mt-1 text-2xl font-black text-gray-950">

                                            {{ number_format($summary['total']) }}

                                            <span class="text-xs font-semibold text-gray-400">
                                            تومان
                                        </span>

                                        </div>

                                    </div>


                                    @if($summary['shipping'] === 0)

                                        <div class="rounded-full bg-emerald-50 px-3 py-1.5 text-[10px] font-bold text-emerald-700">
                                            ارسال رایگان
                                        </div>

                                    @endif

                                </div>

                            </div>


                            {{-- Submit --}}
                            <button
                                type="submit"
                                @disabled($addresses->isEmpty())
                                class="group flex w-full items-center justify-center gap-3 rounded-2xl bg-[#3f207e] px-5 py-4 text-sm font-bold text-white shadow-lg shadow-[#3f207e]/15 transition duration-300 hover:-translate-y-0.5 hover:bg-[#321866] disabled:cursor-not-allowed disabled:opacity-40 disabled:hover:translate-y-0"
                                >

                                ثبت سفارش و ادامه پرداخت

                                <span class="transition-transform duration-300 group-hover:-translate-x-1">
                                ←
                            </span>

                            </button>


                            {{-- Back --}}
                            <a
                                href="{{ route('customer.cart.index') }}"
                                class="flex w-full items-center justify-center rounded-2xl border border-gray-200 px-5 py-4 text-sm font-bold text-gray-700 transition hover:border-[#7b20df] hover:text-[#7b20df]"
                            >
                                بازگشت به سبد خرید
                            </a>

                        </div>


                        {{-- =================================================
                            TRUST
                        ================================================== --}}
                        <div class="border-t border-gray-100 bg-gray-50/70 px-6 py-5">

                            <div class="space-y-4">

                                <div class="flex items-start gap-3">

                                    <div class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-white text-[#3f207e] shadow-sm">
                                        ✓
                                    </div>

                                    <div>

                                        <div class="text-xs font-bold text-gray-900">
                                            پرداخت امن
                                        </div>

                                        <p class="mt-1 text-[11px] leading-6 text-gray-400">
                                            اطلاعات پرداخت شما در محیط امن درگاه انجام می‌شود.
                                        </p>

                                    </div>

                                </div>


                                <div class="flex items-start gap-3">

                                    <div class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-white text-[#3f207e] shadow-sm">
                                        ✓
                                    </div>

                                    <div>

                                        <div class="text-xs font-bold text-gray-900">
                                            قیمت شفاف
                                        </div>

                                        <p class="mt-1 text-[11px] leading-6 text-gray-400">
                                            مبلغ نهایی بر اساس قیمت فعلی محصولات محاسبه می‌شود.
                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </aside>

            </div>

        </form>

    </div>

@endsection
