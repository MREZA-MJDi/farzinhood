@extends('layouts.app')

@section('title', 'تنظیمات حساب | Farzin')

@section('meta_description', 'مدیریت اطلاعات حساب کاربری در Farzin')

@section('content')

    <section class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">

        {{-- =========================================================
            HEADER
        ========================================================== --}}
        <div class="mb-10">

            <div class="text-xs font-bold uppercase tracking-[0.25em] text-[#7b20df]">
                Account Settings
            </div>

            <h1 class="mt-3 text-3xl font-black tracking-tight text-gray-950 sm:text-4xl">
                تنظیمات حساب
            </h1>

            <p class="mt-2 max-w-2xl text-sm leading-7 text-gray-500">
                اطلاعات حساب و تنظیمات شخصی خودت را مدیریت کن.
            </p>

        </div>


        <div class="grid gap-8 lg:grid-cols-[220px_minmax(0,1fr)]">

            {{-- =====================================================
                SIDE MENU
            ====================================================== --}}
            <aside class="lg:sticky lg:top-28 lg:self-start">

                <div class="rounded-[2rem] border border-gray-200 bg-white p-3">

                    <a
                        href="{{ route('customer.dashboard') }}"
                        class="flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm font-bold text-gray-600 transition hover:bg-gray-50 hover:text-[#3f207e]"
                    >
                        <span>⌂</span>
                        داشبورد
                    </a>

                    <a
                        href="{{ route('customer.orders.index') }}"
                        class="mt-1 flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm font-bold text-gray-600 transition hover:bg-gray-50 hover:text-[#3f207e]"
                    >
                        <span>▣</span>
                        سفارش‌ها
                    </a>

                    <a
                        href="{{ route('customer.wishlist.index') }}"
                        class="mt-1 flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm font-bold text-gray-600 transition hover:bg-gray-50 hover:text-[#3f207e]"
                    >
                        <span>♡</span>
                        علاقه‌مندی‌ها
                    </a>

                    <a
                        href="{{ route('customer.addresses.index') }}"
                        class="mt-1 flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm font-bold text-gray-600 transition hover:bg-gray-50 hover:text-[#3f207e]"
                    >
                        <span>⌖</span>
                        آدرس‌ها
                    </a>

                    <div class="mt-2 border-t border-gray-100 pt-2">

                        <a
                            href="{{ route('customer.settings.index') }}"
                            class="flex items-center gap-3 rounded-2xl bg-[#f3edfb] px-4 py-3.5 text-sm font-black text-[#3f207e]"
                        >
                            <span>⚙</span>
                            تنظیمات
                        </a>

                    </div>

                </div>

            </aside>


            {{-- =====================================================
                CONTENT
            ====================================================== --}}
            <div class="space-y-8">


                {{-- =================================================
                    PROFILE
                ================================================== --}}
                <section class="overflow-hidden rounded-[2rem] border border-gray-200 bg-white">

                    <div class="border-b border-gray-100 px-6 py-6 sm:px-8">

                        <div class="flex items-center gap-4">

                            <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-[#f3edfb] text-lg font-black text-[#3f207e]">
                                {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                            </div>

                            <div>
                                <div class="text-xs font-bold uppercase tracking-[0.2em] text-[#7b20df]">
                                    Profile
                                </div>

                                <h2 class="mt-1 text-xl font-black text-gray-950">
                                    اطلاعات شخصی
                                </h2>
                            </div>

                        </div>

                    </div>


                    <form
                        action="{{ route('customer.settings.update') }}"
                        method="POST"
                        class="p-6 sm:p-8"
                    >
                        @csrf
                        @method('PUT')

                        <div class="grid gap-5 sm:grid-cols-2">

                            {{-- Name --}}
                            <div class="sm:col-span-2">

                                <label
                                    for="name"
                                    class="text-xs font-bold text-gray-700"
                                >
                                    نام و نام خانوادگی
                                </label>

                                <input
                                    id="name"
                                    type="text"
                                    name="name"
                                    value="{{ old('name', auth()->user()->name) }}"
                                    maxlength="255"
                                    required
                                    class="mt-2 w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3.5 text-sm outline-none transition focus:border-[#7b20df] focus:bg-white focus:ring-4 focus:ring-[#7b20df]/10"
                                >

                                @error('name')
                                <div class="mt-2 text-xs font-bold text-red-600">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>


                            {{-- Email --}}
                            <div>

                                <label
                                    for="email"
                                    class="text-xs font-bold text-gray-700"
                                >
                                    ایمیل
                                </label>

                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email', auth()->user()->email) }}"
                                    maxlength="255"
                                    required
                                    class="mt-2 w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3.5 text-sm outline-none transition focus:border-[#7b20df] focus:bg-white focus:ring-4 focus:ring-[#7b20df]/10"
                                >

                                @error('email')
                                <div class="mt-2 text-xs font-bold text-red-600">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>


                            {{-- Account Role --}}
                            <div>

                                <label
                                    for="role"
                                    class="text-xs font-bold text-gray-700"
                                >
                                    نوع حساب
                                </label>

                                <div
                                    id="role"
                                    class="mt-2 flex min-h-[52px] items-center rounded-xl border border-gray-200 bg-gray-50 px-4 text-sm font-bold text-gray-600"
                                >
                                    مشتری
                                </div>

                            </div>

                        </div>


                        <div class="mt-7 flex justify-end">

                            <button
                                type="submit"
                                class="rounded-xl bg-[#3f207e] px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-[#3f207e]/10 transition duration-300 hover:-translate-y-0.5 hover:bg-[#321866]"
                            >
                                ذخیره تغییرات
                            </button>

                        </div>

                    </form>

                </section>


                {{-- =================================================
                    PASSWORD
                ================================================== --}}
                <section class="overflow-hidden rounded-[2rem] border border-gray-200 bg-white">

                    <div class="border-b border-gray-100 px-6 py-6 sm:px-8">

                        <div class="text-xs font-bold uppercase tracking-[0.2em] text-[#7b20df]">
                            Security
                        </div>

                        <h2 class="mt-2 text-xl font-black text-gray-950">
                            امنیت حساب
                        </h2>

                        <p class="mt-1 text-xs leading-6 text-gray-400">
                            برای امنیت بیشتر، رمز عبور قوی انتخاب کن.
                        </p>

                    </div>


                    <div class="p-6 sm:p-8">

                        <div class="rounded-[1.5rem] border border-amber-100 bg-amber-50/70 p-5">

                            <div class="flex items-start gap-3">

                                <div class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-white font-bold text-amber-600 shadow-sm">
                                    !
                                </div>

                                <div>

                                    <div class="text-sm font-bold text-amber-900">
                                        مدیریت رمز عبور
                                    </div>

                                    <p class="mt-1 text-xs leading-6 text-amber-700/70">
                                        تغییر رمز عبور باید از مسیر امن احراز هویت انجام شود.
                                    </p>

                                </div>

                            </div>

                        </div>

                        @if(Route::has('password.request'))

                            <div class="mt-5">

                                <a
                                    href="{{ route('password.request') }}"
                                    class="inline-flex items-center gap-2 rounded-xl border border-gray-200 px-5 py-3.5 text-xs font-bold text-gray-700 transition hover:border-[#7b20df] hover:bg-[#f8f4fc] hover:text-[#3f207e]"
                                >
                                    بازیابی / تغییر رمز عبور
                                    <span>←</span>
                                </a>

                            </div>

                        @endif

                    </div>

                </section>


                {{-- =================================================
                    ACCOUNT STATUS
                ================================================== --}}
                <section class="overflow-hidden rounded-[2rem] border border-gray-200 bg-white">

                    <div class="border-b border-gray-100 px-6 py-6 sm:px-8">

                        <div class="text-xs font-bold uppercase tracking-[0.2em] text-[#7b20df]">
                            Account Status
                        </div>

                        <h2 class="mt-2 text-xl font-black text-gray-950">
                            وضعیت حساب
                        </h2>

                    </div>


                    <div class="p-6 sm:p-8">

                        <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                            <div class="flex items-center gap-4">

                                <div class="flex size-11 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                                    ✓
                                </div>

                                <div>

                                    <div class="text-sm font-black text-gray-900">
                                        حساب فعال است
                                    </div>

                                    <div class="mt-1 text-xs text-gray-400">
                                        امکان خرید و مدیریت سفارش‌ها فعال است.
                                    </div>

                                </div>

                            </div>


                            <div class="rounded-full bg-emerald-50 px-4 py-2 text-xs font-bold text-emerald-700">
                                Active
                            </div>

                        </div>

                    </div>

                </section>


                {{-- =================================================
                    DANGER ZONE
                ================================================== --}}
                <section class="rounded-[2rem] border border-red-100 bg-red-50/50 p-6 sm:p-8">

                    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                        <div>

                            <div class="text-sm font-black text-red-900">
                                خروج از حساب
                            </div>

                            <p class="mt-1 text-xs leading-6 text-red-700/70">
                                برای حفظ امنیت، می‌توانی از حساب کاربری خود خارج شوی.
                            </p>

                        </div>

                        <form
                            action="{{ route('logout') }}"
                            method="POST"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="rounded-xl border border-red-200 bg-white px-5 py-3 text-xs font-bold text-red-700 transition hover:bg-red-100"
                            >
                                خروج از حساب
                            </button>

                        </form>

                    </div>

                </section>

            </div>

        </div>

    </section>

@endsection
