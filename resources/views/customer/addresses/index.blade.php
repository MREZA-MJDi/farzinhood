@extends('layouts.app')

@section('title', 'آدرس‌های من | Farzin')

@section('meta_description', 'مدیریت آدرس‌های ارسال در حساب کاربری Farzin')

@section('content')

    <section class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">

        {{-- =========================================================
            HEADER
        ========================================================== --}}
        <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">

            <div>

                <div class="text-xs font-bold uppercase tracking-[0.25em] text-[#7b20df]">
                    Addresses
                </div>

                <h1 class="mt-3 text-3xl font-black tracking-tight text-gray-950 sm:text-4xl">
                    آدرس‌های من
                </h1>

                <p class="mt-2 max-w-2xl text-sm leading-7 text-gray-500">
                    آدرس‌های ارسال را مدیریت کن تا در Checkout سریع‌تر سفارشت را ثبت کنی.
                </p>

            </div>


            <a
                href="#add-address"
                class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#3f207e] px-5 py-3.5 text-sm font-bold text-white shadow-lg shadow-[#3f207e]/10 transition duration-300 hover:-translate-y-0.5 hover:bg-[#321866]"
            >
                <span class="text-lg leading-none">+</span>
                افزودن آدرس
            </a>

        </div>


        {{-- =========================================================
            ADDRESS LIST
        ========================================================== --}}
        <div class="mt-10">

            @if($addresses->isNotEmpty())

                <div class="grid gap-5 md:grid-cols-2">

                    @foreach($addresses as $address)

                        <article
                            class="group relative overflow-hidden rounded-[2rem] border border-gray-200 bg-white p-6 transition duration-300 hover:-translate-y-0.5 hover:border-gray-300 hover:shadow-xl hover:shadow-gray-200/40"
                        >

                            {{-- Accent --}}
                            <div
                                class="absolute inset-x-0 top-0 h-1 {{ $address->is_default ? 'bg-[#3f207e]' : 'bg-gray-100' }}"
                            ></div>


                            {{-- Header --}}
                            <div class="flex items-start justify-between gap-4">

                                <div class="flex min-w-0 items-center gap-3">

                                    <div
                                        class="flex size-11 shrink-0 items-center justify-center rounded-2xl {{ $address->is_default ? 'bg-[#f3edfb] text-[#3f207e]' : 'bg-gray-100 text-gray-500' }}"
                                    >
                                        <svg
                                            class="h-5 w-5"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.7"
                                        >
                                            <path d="M12 21s7-6.1 7-12A7 7 0 1 0 5 9c0 5.9 7 12 7 12Z"/>
                                            <circle cx="12" cy="9" r="2.3"/>
                                        </svg>
                                    </div>

                                    <div class="min-w-0">

                                        <h2 class="truncate text-sm font-black text-gray-950">
                                            {{ $address->title ?: 'آدرس ارسال' }}
                                        </h2>

                                        @if($address->is_default)

                                            <div class="mt-1 inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-700">
                                                آدرس پیش‌فرض
                                            </div>

                                        @else

                                            <div class="mt-1 text-[10px] text-gray-400">
                                                آدرس ذخیره‌شده
                                            </div>

                                        @endif

                                    </div>

                                </div>


                                {{-- Actions --}}
                                <div class="flex shrink-0 items-center gap-1">

                                    <a
                                        href="#edit-address-{{ $address->id }}"
                                        class="flex size-9 items-center justify-center rounded-xl text-gray-400 transition hover:bg-[#f3edfb] hover:text-[#3f207e]"
                                        title="ویرایش"
                                        aria-label="ویرایش"
                                    >
                                        <svg
                                            class="h-4 w-4"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.7"
                                        >
                                            <path d="m4 16-.7 4.7L8 20l11.2-11.2a2.5 2.5 0 0 0-3.5-3.5L4.5 16.5"/>
                                            <path d="m14.5 6.5 3 3"/>
                                        </svg>
                                    </a>

                                    <form
                                        action="{{ route('customer.addresses.destroy', $address) }}"
                                        method="POST"
                                        onsubmit="return confirm('آیا از حذف این آدرس مطمئنی؟');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="flex size-9 items-center justify-center rounded-xl text-gray-400 transition hover:bg-red-50 hover:text-red-600"
                                            title="حذف"
                                            aria-label="حذف"
                                        >
                                            <svg
                                                class="h-4 w-4"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.7"
                                            >
                                                <path d="M3 6h18"/>
                                                <path d="M8 6V4h8v2"/>
                                                <path d="M19 6l-1 15H6L5 6"/>
                                                <path d="M10 11v6M14 11v6"/>
                                            </svg>
                                        </button>

                                    </form>

                                </div>

                            </div>


                            {{-- Address body --}}
                            <div class="mt-6 space-y-4">

                                <div>

                                    <div class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                        گیرنده
                                    </div>

                                    <div class="mt-1 text-sm font-bold text-gray-900">
                                        {{ $address->full_name }}
                                    </div>

                                </div>


                                <div class="grid gap-4 sm:grid-cols-2">

                                    <div>

                                        <div class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                            تلفن
                                        </div>

                                        <div class="mt-1 text-sm font-bold text-gray-700">
                                            {{ $address->phone }}
                                        </div>

                                    </div>


                                    <div>

                                        <div class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                            منطقه
                                        </div>

                                        <div class="mt-1 text-sm font-bold text-gray-700">
                                            {{ $address->province }}
                                            ،
                                            {{ $address->city }}
                                        </div>

                                    </div>

                                </div>


                                <div>

                                    <div class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                        آدرس
                                    </div>

                                    <div class="mt-1 text-sm leading-7 text-gray-600">
                                        {{ $address->address }}
                                    </div>

                                </div>


                                @if($address->postal_code)

                                    <div>

                                        <div class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                            کد پستی
                                        </div>

                                        <div class="mt-1 font-mono text-sm font-bold text-gray-700">
                                            {{ $address->postal_code }}
                                        </div>

                                    </div>

                                @endif

                            </div>


                            {{-- Default action --}}
                            @unless($address->is_default)

                                <div class="mt-6 border-t border-gray-100 pt-5">

                                    <form
                                        action="{{ route('customer.addresses.default', $address) }}"
                                        method="POST"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-xs font-bold text-gray-600 transition hover:border-[#3f207e] hover:bg-[#f8f4fc] hover:text-[#3f207e]"
                                        >
                                            انتخاب به‌عنوان آدرس پیش‌فرض
                                        </button>

                                    </form>

                                </div>

                            @else

                                <div class="mt-6 border-t border-gray-100 pt-5">

                                    <div class="flex items-center gap-2 text-xs font-bold text-emerald-600">
                                    <span class="flex size-6 items-center justify-center rounded-full bg-emerald-50">
                                        ✓
                                    </span>

                                        این آدرس برای Checkout پیش‌فرض است.
                                    </div>

                                </div>

                            @endunless

                        </article>

                    @endforeach

                </div>

            @else

                {{-- Empty --}}
                <div class="rounded-[2rem] border border-dashed border-gray-300 bg-white px-6 py-24 text-center">

                    <div class="mx-auto flex size-16 items-center justify-center rounded-[1.5rem] bg-[#f3edfb] text-2xl text-[#3f207e]">
                        +
                    </div>

                    <h2 class="mt-5 text-xl font-black text-gray-950">
                        هنوز آدرسی ثبت نکردی
                    </h2>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-7 text-gray-500">
                        یک آدرس اضافه کن تا موقع خرید لازم نباشه دوباره اطلاعات ارسال را وارد کنی.
                    </p>

                    <a
                        href="#add-address"
                        class="mt-6 inline-flex rounded-2xl bg-[#3f207e] px-6 py-4 text-sm font-bold text-white transition hover:bg-[#321866]"
                    >
                        افزودن اولین آدرس
                    </a>

                </div>

            @endif

        </div>


        {{-- =========================================================
            ADD ADDRESS
        ========================================================== --}}
        <section
            id="add-address"
            class="mt-12 scroll-mt-28 overflow-hidden rounded-[2rem] border border-gray-200 bg-white"
        >

            <div class="border-b border-gray-100 px-6 py-6 sm:px-8">

                <div class="text-xs font-bold uppercase tracking-[0.2em] text-[#7b20df]">
                    New Address
                </div>

                <h2 class="mt-2 text-xl font-black text-gray-950">
                    افزودن آدرس جدید
                </h2>

                <p class="mt-1 text-xs leading-6 text-gray-400">
                    اطلاعات دقیق ارسال را وارد کن.
                </p>

            </div>


            <form
                action="{{ route('customer.addresses.store') }}"
                method="POST"
                class="p-6 sm:p-8"
            >
                @csrf

                <div class="grid gap-5 sm:grid-cols-2">

                    {{-- Title --}}
                    <div>

                        <label
                            for="title"
                            class="text-xs font-bold text-gray-700"
                        >
                            عنوان آدرس
                        </label>

                        <input
                            id="title"
                            type="text"
                            name="title"
                            value="{{ old('title') }}"
                            maxlength="100"
                            placeholder="مثلاً خانه، محل کار..."
                            class="mt-2 w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3.5 text-sm outline-none transition placeholder:text-gray-400 focus:border-[#7b20df] focus:bg-white focus:ring-4 focus:ring-[#7b20df]/10"
                        >

                        @error('title')
                        <div class="mt-2 text-xs font-bold text-red-600">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>


                    {{-- Full Name --}}
                    <div>

                        <label
                            for="full_name"
                            class="text-xs font-bold text-gray-700"
                        >
                            نام گیرنده
                        </label>

                        <input
                            id="full_name"
                            type="text"
                            name="full_name"
                            value="{{ old('full_name') }}"
                            maxlength="255"
                            required
                            class="mt-2 w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3.5 text-sm outline-none transition focus:border-[#7b20df] focus:bg-white focus:ring-4 focus:ring-[#7b20df]/10"
                        >

                        @error('full_name')
                        <div class="mt-2 text-xs font-bold text-red-600">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>


                    {{-- Phone --}}
                    <div>

                        <label
                            for="phone"
                            class="text-xs font-bold text-gray-700"
                        >
                            شماره تماس
                        </label>

                        <input
                            id="phone"
                            type="tel"
                            name="phone"
                            value="{{ old('phone') }}"
                            maxlength="30"
                            required
                            class="mt-2 w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3.5 text-sm outline-none transition focus:border-[#7b20df] focus:bg-white focus:ring-4 focus:ring-[#7b20df]/10"
                        >

                        @error('phone')
                        <div class="mt-2 text-xs font-bold text-red-600">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>


                    {{-- Country --}}
                    <div>

                        <label
                            for="country"
                            class="text-xs font-bold text-gray-700"
                        >
                            کشور
                        </label>

                        <input
                            id="country"
                            type="text"
                            name="country"
                            value="{{ old('country', 'Iran') }}"
                            maxlength="100"
                            required
                            class="mt-2 w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3.5 text-sm outline-none transition focus:border-[#7b20df] focus:bg-white focus:ring-4 focus:ring-[#7b20df]/10"
                        >

                        @error('country')
                        <div class="mt-2 text-xs font-bold text-red-600">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>


                    {{-- Province --}}
                    <div>

                        <label
                            for="province"
                            class="text-xs font-bold text-gray-700"
                        >
                            استان / ایالت
                        </label>

                        <input
                            id="province"
                            type="text"
                            name="province"
                            value="{{ old('province') }}"
                            maxlength="100"
                            required
                            class="mt-2 w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3.5 text-sm outline-none transition focus:border-[#7b20df] focus:bg-white focus:ring-4 focus:ring-[#7b20df]/10"
                        >

                        @error('province')
                        <div class="mt-2 text-xs font-bold text-red-600">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>


                    {{-- City --}}
                    <div>

                        <label
                            for="city"
                            class="text-xs font-bold text-gray-700"
                        >
                            شهر
                        </label>

                        <input
                            id="city"
                            type="text"
                            name="city"
                            value="{{ old('city') }}"
                            maxlength="100"
                            required
                            class="mt-2 w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3.5 text-sm outline-none transition focus:border-[#7b20df] focus:bg-white focus:ring-4 focus:ring-[#7b20df]/10"
                        >

                        @error('city')
                        <div class="mt-2 text-xs font-bold text-red-600">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>


                    {{-- Postal Code --}}
                    <div>

                        <label
                            for="postal_code"
                            class="text-xs font-bold text-gray-700"
                        >
                            کد پستی
                        </label>

                        <input
                            id="postal_code"
                            type="text"
                            name="postal_code"
                            value="{{ old('postal_code') }}"
                            maxlength="20"
                            class="mt-2 w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3.5 text-sm font-mono outline-none transition focus:border-[#7b20df] focus:bg-white focus:ring-4 focus:ring-[#7b20df]/10"
                        >

                        @error('postal_code')
                        <div class="mt-2 text-xs font-bold text-red-600">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>


                    {{-- Address --}}
                    <div class="sm:col-span-2">

                        <label
                            for="address"
                            class="text-xs font-bold text-gray-700"
                        >
                            آدرس کامل
                        </label>

                        <textarea
                            id="address"
                            name="address"
                            rows="4"
                            maxlength="2000"
                            required
                            placeholder="خیابان، کوچه، پلاک، واحد..."
                            class="mt-2 w-full resize-none rounded-xl border border-gray-200 bg-gray-50 px-4 py-3.5 text-sm leading-7 outline-none transition placeholder:text-gray-400 focus:border-[#7b20df] focus:bg-white focus:ring-4 focus:ring-[#7b20df]/10"
                        >{{ old('address') }}</textarea>

                        @error('address')
                        <div class="mt-2 text-xs font-bold text-red-600">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>


                    {{-- Default --}}
                    <div class="sm:col-span-2">

                        <label class="flex cursor-pointer items-center gap-3">

                            <input
                                type="checkbox"
                                name="is_default"
                                value="1"
                                @checked(old('is_default'))
                            class="size-5 rounded border-gray-300 text-[#3f207e] focus:ring-[#7b20df]"
                            >

                            <span class="text-sm font-bold text-gray-700">
                            این آدرس را به‌عنوان پیش‌فرض ذخیره کن.
                        </span>

                        </label>

                        @error('is_default')
                        <div class="mt-2 text-xs font-bold text-red-600">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>

                </div>


                <div class="mt-7 flex flex-col gap-3 sm:flex-row sm:justify-end">

                    <button
                        type="reset"
                        class="rounded-xl border border-gray-200 px-5 py-3.5 text-sm font-bold text-gray-600 transition hover:border-gray-300 hover:bg-gray-50"
                    >
                        پاک کردن
                    </button>

                    <button
                        type="submit"
                        class="rounded-xl bg-[#3f207e] px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-[#3f207e]/10 transition hover:-translate-y-0.5 hover:bg-[#321866]"
                    >
                        ذخیره آدرس
                    </button>

                </div>

            </form>

        </section>


        {{-- =========================================================
            EDIT ADDRESS MODALS
        ========================================================== --}}
        @foreach($addresses as $address)

            <div
                id="edit-address-{{ $address->id }}"
                class="hidden"
            >
            </div>

        @endforeach

    </section>

@endsection
