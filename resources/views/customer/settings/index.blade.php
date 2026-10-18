@extends('layouts.app')

@section('title', 'تنظیمات حساب | Farzin')

@section('meta_description', 'مدیریت اطلاعات حساب کاربری در Farzin')

@section('content')

    <section class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">

        {{-- =========================================================
            HEADER
        ========================================================== --}}
        <div class="mb-10">

            <div class="text-xs font-bold uppercase tracking-[0.25em] text-[#817d45]">
                Account Settings
            </div>

            <h1 class="mt-3 text-3xl font-black tracking-tight text-[#292b24] sm:text-4xl">
                تنظیمات حساب
            </h1>

            <p class="mt-2 max-w-2xl text-sm leading-7 text-[#6f7168]">
                اطلاعات حساب و تنظیمات شخصی خودت را مدیریت کن.
            </p>

        </div>


        <div class="grid gap-8 lg:grid-cols-[220px_minmax(0,1fr)]">

            {{-- =====================================================
                SIDE MENU
            ====================================================== --}}
            <aside class="lg:sticky lg:top-28 lg:self-start">

                <div class="rounded-[2rem] border border-[#e7e7df] bg-white p-3">

                    <a
                        href="{{ route('customer.dashboard') }}"
                        class="flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm font-bold text-[#606258] transition hover:bg-[#f7f7f3] hover:text-[#686438]"
                    >
                        <span>⌂</span>
                        داشبورد
                    </a>

                    <a
                        href="{{ route('customer.orders.index') }}"
                        class="mt-1 flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm font-bold text-[#606258] transition hover:bg-[#f7f7f3] hover:text-[#686438]"
                    >
                        <span>▣</span>
                        سفارش‌ها
                    </a>

                    <a
                        href="{{ route('customer.wishlist.index') }}"
                        class="mt-1 flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm font-bold text-[#606258] transition hover:bg-[#f7f7f3] hover:text-[#686438]"
                    >
                        <span>♡</span>
                        علاقه‌مندی‌ها
                    </a>

                    <a
                        href="{{ route('customer.addresses.index') }}"
                        class="mt-1 flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm font-bold text-[#606258] transition hover:bg-[#f7f7f3] hover:text-[#686438]"
                    >
                        <span>⌖</span>
                        آدرس‌ها
                    </a>

                    <div class="mt-2 border-t border-[#efefe9] pt-2">

                        <a
                            href="{{ route('customer.settings.index') }}"
                            class="flex items-center gap-3 rounded-2xl bg-[#f0f0e7] px-4 py-3.5 text-sm font-black text-[#686438]"
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
                <section class="overflow-hidden rounded-[2rem] border border-[#e7e7df] bg-white">

                    <div class="border-b border-[#efefe9] px-6 py-6 sm:px-8">

                        <div class="flex items-center gap-4">

                            <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-[#f0f0e7] text-lg font-black text-[#686438]">
                                {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                            </div>

                            <div>
                                <div class="text-xs font-bold uppercase tracking-[0.2em] text-[#817d45]">
                                    Profile
                                </div>

                                <h2 class="mt-1 text-xl font-black text-[#292b24]">
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
                                    class="text-xs font-bold text-[#515349]"
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
                                    class="mt-2 w-full rounded-xl border border-[#e7e7df] bg-[#f7f7f3] px-4 py-3.5 text-sm outline-none transition focus:border-[#817d45] focus:bg-white focus:ring-4 focus:ring-[#817d45]/10"
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
                                    class="text-xs font-bold text-[#515349]"
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
                                    class="mt-2 w-full rounded-xl border border-[#e7e7df] bg-[#f7f7f3] px-4 py-3.5 text-sm outline-none transition focus:border-[#817d45] focus:bg-white focus:ring-4 focus:ring-[#817d45]/10"
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
                                    class="text-xs font-bold text-[#515349]"
                                >
                                    نوع حساب
                                </label>

                                <div
                                    id="role"
                                    class="mt-2 flex min-h-[52px] items-center rounded-xl border border-[#e7e7df] bg-[#f7f7f3] px-4 text-sm font-bold text-[#606258]"
                                >
                                    مشتری
                                </div>

                            </div>

                        </div>


                        <div class="mt-7 flex justify-end">

                            <button
                                type="submit"
                                class="rounded-xl bg-[#686438] px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-[#686438]/10 transition duration-300 hover:-translate-y-0.5 hover:bg-[#555333]"
                            >
                                ذخیره تغییرات
                            </button>

                        </div>

                    </form>

                </section>


                {{-- =================================================
                    PASSWORD
                ================================================== --}}
                <section class="overflow-hidden rounded-[2rem] border border-[#e7e7df] bg-white">

                    <div class="border-b border-[#efefe9] px-6 py-6 sm:px-8">

                        <div class="text-xs font-bold uppercase tracking-[0.2em] text-[#817d45]">
                            Security
                        </div>

                        <h2 class="mt-2 text-xl font-black text-[#292b24]">
                            امنیت حساب
                        </h2>

                        <p class="mt-1 text-xs leading-6 text-[#929489]">
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
                                    class="inline-flex items-center gap-2 rounded-xl border border-[#e7e7df] px-5 py-3.5 text-xs font-bold text-[#515349] transition hover:border-[#817d45] hover:bg-[#f7f7f3] hover:text-[#686438]"
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
                <section class="overflow-hidden rounded-[2rem] border border-[#e7e7df] bg-white">

                    <div class="border-b border-[#efefe9] px-6 py-6 sm:px-8">

                        <div class="text-xs font-bold uppercase tracking-[0.2em] text-[#817d45]">
                            Account Status
                        </div>

                        <h2 class="mt-2 text-xl font-black text-[#292b24]">
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

                                    <div class="text-sm font-black text-[#292b24]">
                                        حساب فعال است
                                    </div>

                                    <div class="mt-1 text-xs text-[#929489]">
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
