@extends('layouts.app')

@section('title', 'تماس با ما | فروشگاه فرزین')

@section('content')

    <div class="bg-[#f7f5f1]">

        {{-- Hero --}}
        <section class="relative overflow-hidden border-b border-[#e6e0d7] bg-[#f4f1eb]">
            <div class="absolute -left-24 -top-24 h-72 w-72 rounded-full bg-[#3f207e]/5 blur-3xl"></div>
            <div class="absolute -right-24 bottom-0 h-80 w-80 rounded-full bg-[#7b20df]/5 blur-3xl"></div>

            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
                <div class="max-w-3xl">
                    <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-[#ddd4e8] bg-white px-4 py-2 text-sm font-medium text-[#3f207e] shadow-sm">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v8Z"/>
                        </svg>
                        در ارتباطیم
                    </div>

                    <h1 class="text-4xl font-black tracking-tight text-[#17151b] sm:text-5xl lg:text-6xl">
                        با ما در ارتباط باشید
                    </h1>

                    <p class="mt-6 max-w-2xl text-base leading-8 text-[#6f6a72] sm:text-lg">
                        سوالی درباره محصولات، سفارش، ارسال یا همکاری دارید؟
                        پیام خودتان را برای ما بفرستید؛ تیم فرزین در سریع‌ترین زمان ممکن پاسخگوی شما خواهد بود.
                    </p>
                </div>
            </div>
        </section>

        {{-- Main Contact Section --}}
        <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
            <div class="grid gap-8 lg:grid-cols-12">

                {{-- Contact Info --}}
                <div class="lg:col-span-5">
                    <div class="rounded-[2rem] bg-[#211d25] p-7 text-white shadow-xl sm:p-9">
                        <div class="max-w-md">
                        <span class="text-sm font-bold tracking-wide text-[#cdb9e9]">
                            راه‌های ارتباطی
                        </span>

                            <h2 class="mt-3 text-2xl font-black sm:text-3xl">
                                همیشه خوشحالیم که صدای شما را بشنویم
                            </h2>

                            <p class="mt-4 text-sm leading-7 text-white/65">
                                برای پیگیری سفارش، دریافت مشاوره خرید، همکاری یا هر موضوع دیگری
                                می‌توانید از طریق اطلاعات زیر با ما تماس بگیرید.
                            </p>
                        </div>

                        <div class="mt-8 space-y-4">

                            {{-- Phone --}}
                            <a
                                href="tel:+982112345678"
                                class="group flex items-center gap-4 rounded-2xl border border-white/10 bg-white/[0.04] p-4 transition hover:bg-white/[0.08]"
                            >
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white/10 text-[#d8c8eb]">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.79 19.79 0 0 1 3.1 5.18 2 2 0 0 1 5.11 3h3a2 2 0 0 1 2 1.72c.12.9.33 1.78.62 2.63a2 2 0 0 1-.45 2.11L9 10.73a16 16 0 0 0 4.27 4.27l1.27-1.27a2 2 0 0 1 2.11-.45c.85.29 1.73.5 2.63.62A2 2 0 0 1 22 16.92Z"/>
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <p class="text-xs text-white/45">تلفن تماس</p>
                                    <p class="mt-1 text-sm font-bold text-white">
                                        ۰۲۱-۱۲۳۴۵۶۷۸
                                    </p>
                                </div>

                                <svg
                                    class="mr-auto h-4 w-4 text-white/30 transition group-hover:-translate-x-1 group-hover:text-white"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path d="m9 18 6-6-6-6"/>
                                </svg>
                            </a>

                            {{-- Email --}}
                            <a
                                href="mailto:info@farzin.ir"
                                class="group flex items-center gap-4 rounded-2xl border border-white/10 bg-white/[0.04] p-4 transition hover:bg-white/[0.08]"
                            >
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white/10 text-[#d8c8eb]">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z"/>
                                        <path d="m22 6-10 7L2 6"/>
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <p class="text-xs text-white/45">ایمیل</p>
                                    <p class="mt-1 truncate text-sm font-bold text-white">
                                        info@farzin.ir
                                    </p>
                                </div>

                                <svg
                                    class="mr-auto h-4 w-4 text-white/30 transition group-hover:-translate-x-1 group-hover:text-white"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path d="m9 18 6-6-6-6"/>
                                </svg>
                            </a>

                            {{-- Address --}}
                            <div class="flex items-center gap-4 rounded-2xl border border-white/10 bg-white/[0.04] p-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white/10 text-[#d8c8eb]">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/>
                                        <circle cx="12" cy="10" r="2.5"/>
                                    </svg>
                                </div>

                                <div>
                                    <p class="text-xs text-white/45">آدرس</p>
                                    <p class="mt-1 text-sm font-bold leading-6 text-white">
                                        تهران، خیابان مثال، پلاک ۱۲۳
                                    </p>
                                </div>
                            </div>

                            {{-- Working Hours --}}
                            <div class="flex items-center gap-4 rounded-2xl border border-white/10 bg-white/[0.04] p-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white/10 text-[#d8c8eb]">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <circle cx="12" cy="12" r="9"/>
                                        <path d="M12 7v5l3 2"/>
                                    </svg>
                                </div>

                                <div>
                                    <p class="text-xs text-white/45">ساعات پاسخگویی</p>
                                    <p class="mt-1 text-sm font-bold text-white">
                                        شنبه تا پنجشنبه · ۹ تا ۱۸
                                    </p>
                                </div>
                            </div>

                        </div>

                        {{-- Social --}}
                        <div class="mt-8 border-t border-white/10 pt-7">
                            <p class="text-xs text-white/45">
                                ما را دنبال کنید
                            </p>

                            <div class="mt-4 flex items-center gap-3">
                                <a
                                    href="#"
                                    aria-label="Instagram"
                                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 text-white transition hover:bg-white/15"
                                >
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                        <rect x="3" y="3" width="18" height="18" rx="5"/>
                                        <circle cx="12" cy="12" r="4"/>
                                        <circle cx="17.5" cy="6.5" r=".8" fill="currentColor" stroke="none"/>
                                    </svg>
                                </a>

                                <a
                                    href="#"
                                    aria-label="Telegram"
                                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 text-white transition hover:bg-white/15"
                                >
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                        <path d="M21 3 3.8 9.7c-.8.3-.8 1.4 0 1.7l4.8 1.8 1.8 5.2c.2.7 1.1.9 1.6.3l2.6-3.2 4.4 3.2c.7.5 1.6.1 1.8-.7L23 4.1c.2-.8-1.1-1.5-2-1.1Z"/>
                                        <path d="m8.7 13.2 8.8-6.5-6.1 7.7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contact Form --}}
                <div class="lg:col-span-7">
                    <div class="rounded-[2rem] border border-[#e4ded6] bg-white p-6 shadow-[0_18px_55px_rgba(40,32,45,0.06)] sm:p-9">

                        <div>
                        <span class="text-sm font-bold text-[#7b20df]">
                            پیام شما
                        </span>

                            <h2 class="mt-2 text-2xl font-black text-[#17151b] sm:text-3xl">
                                چطور می‌توانیم کمکتان کنیم؟
                            </h2>

                            <p class="mt-3 text-sm leading-7 text-[#77717e]">
                                فرم زیر را تکمیل کنید تا کارشناسان ما با شما تماس بگیرند.
                            </p>
                        </div>

                        <form
                            action="{{ route('contact.store') }}"
                            method="POST"
                            class="mt-8 space-y-6"
                        >
                            @csrf

                            <div class="grid gap-5 sm:grid-cols-2">

                                {{-- Name --}}
                                <div>
                                    <label for="name" class="mb-2 block text-sm font-bold text-[#302c34]">
                                        نام و نام خانوادگی
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <input
                                        id="name"
                                        type="text"
                                        name="name"
                                        value="{{ old('name') }}"
                                        required
                                        maxlength="100"
                                        autocomplete="name"
                                        placeholder="مثلاً رضا محمدی"
                                        class="block w-full rounded-2xl border border-[#ddd8d1] bg-[#faf9f7] px-4 py-3.5 text-sm text-[#17151b] outline-none transition placeholder:text-[#a8a2a8] focus:border-[#7b20df] focus:bg-white focus:ring-4 focus:ring-[#7b20df]/10 @error('name') border-red-400 @enderror"
                                    >

                                    @error('name')
                                    <p class="mt-2 text-xs font-medium text-red-500">
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label for="email" class="mb-2 block text-sm font-bold text-[#302c34]">
                                        ایمیل
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <input
                                        id="email"
                                        type="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        maxlength="150"
                                        autocomplete="email"
                                        dir="ltr"
                                        placeholder="name@example.com"
                                        class="block w-full rounded-2xl border border-[#ddd8d1] bg-[#faf9f7] px-4 py-3.5 text-sm text-[#17151b] outline-none transition placeholder:text-[#a8a2a8] focus:border-[#7b20df] focus:bg-white focus:ring-4 focus:ring-[#7b20df]/10 @error('email') border-red-400 @enderror"
                                    >

                                    @error('email')
                                    <p class="mt-2 text-xs font-medium text-red-500">
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                            </div>

                            <div class="grid gap-5 sm:grid-cols-2">

                                {{-- Phone --}}
                                <div>
                                    <label for="phone" class="mb-2 block text-sm font-bold text-[#302c34]">
                                        شماره تماس
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <input
                                        id="phone"
                                        type="tel"
                                        name="phone"
                                        value="{{ old('phone') }}"
                                        required
                                        maxlength="30"
                                        autocomplete="tel"
                                        dir="ltr"
                                        placeholder="0912 123 4567"
                                        class="block w-full rounded-2xl border border-[#ddd8d1] bg-[#faf9f7] px-4 py-3.5 text-sm text-[#17151b] outline-none transition placeholder:text-[#a8a2a8] focus:border-[#7b20df] focus:bg-white focus:ring-4 focus:ring-[#7b20df]/10 @error('phone') border-red-400 @enderror"
                                    >

                                    @error('phone')
                                    <p class="mt-2 text-xs font-medium text-red-500">
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                {{-- Subject --}}
                                <div>
                                    <label for="subject" class="mb-2 block text-sm font-bold text-[#302c34]">
                                        موضوع
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <select
                                        id="subject"
                                        name="subject"
                                        required
                                        class="block w-full rounded-2xl border border-[#ddd8d1] bg-[#faf9f7] px-4 py-3.5 text-sm text-[#17151b] outline-none transition focus:border-[#7b20df] focus:bg-white focus:ring-4 focus:ring-[#7b20df]/10 @error('subject') border-red-400 @enderror"
                                    >
                                        <option value="">انتخاب موضوع</option>
                                        <option value="مشاوره خرید" @selected(old('subject') === 'مشاوره خرید')>
                                        مشاوره خرید
                                        </option>
                                        <option value="پیگیری سفارش" @selected(old('subject') === 'پیگیری سفارش')>
                                        پیگیری سفارش
                                        </option>
                                        <option value="ارسال و تحویل" @selected(old('subject') === 'ارسال و تحویل')>
                                        ارسال و تحویل
                                        </option>
                                        <option value="خدمات پس از فروش" @selected(old('subject') === 'خدمات پس از فروش')>
                                        خدمات پس از فروش
                                        </option>
                                        <option value="همکاری" @selected(old('subject') === 'همکاری')>
                                        همکاری
                                        </option>
                                        <option value="سایر" @selected(old('subject') === 'سایر')>
                                        سایر
                                        </option>
                                    </select>

                                    @error('subject')
                                    <p class="mt-2 text-xs font-medium text-red-500">
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                            </div>

                            {{-- Order Number --}}
                            <div>
                                <label for="order_number" class="mb-2 block text-sm font-bold text-[#302c34]">
                                    شماره سفارش
                                    <span class="text-xs font-normal text-[#9a949b]">
                                    (اختیاری)
                                </span>
                                </label>

                                <input
                                    id="order_number"
                                    type="text"
                                    name="order_number"
                                    value="{{ old('order_number') }}"
                                    maxlength="100"
                                    placeholder="در صورت پیگیری سفارش وارد کنید"
                                    class="block w-full rounded-2xl border border-[#ddd8d1] bg-[#faf9f7] px-4 py-3.5 text-sm text-[#17151b] outline-none transition placeholder:text-[#a8a2a8] focus:border-[#7b20df] focus:bg-white focus:ring-4 focus:ring-[#7b20df]/10 @error('order_number') border-red-400 @enderror"
                                >

                                @error('order_number')
                                <p class="mt-2 text-xs font-medium text-red-500">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            {{-- Message --}}
                            <div>
                                <label for="message" class="mb-2 block text-sm font-bold text-[#302c34]">
                                    پیام شما
                                    <span class="text-red-500">*</span>
                                </label>

                                <textarea
                                    id="message"
                                    name="message"
                                    rows="7"
                                    required
                                    maxlength="3000"
                                    placeholder="پیام، سوال یا درخواست خود را برای ما بنویسید..."
                                    class="block w-full resize-none rounded-2xl border border-[#ddd8d1] bg-[#faf9f7] px-4 py-3.5 text-sm leading-7 text-[#17151b] outline-none transition placeholder:text-[#a8a2a8] focus:border-[#7b20df] focus:bg-white focus:ring-4 focus:ring-[#7b20df]/10 @error('message') border-red-400 @enderror"
                                >{{ old('message') }}</textarea>

                                @error('message')
                                <p class="mt-2 text-xs font-medium text-red-500">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            {{-- Submit --}}
                            <div class="flex flex-col gap-4 border-t border-[#eee9e3] pt-6 sm:flex-row sm:items-center sm:justify-between">

                                <p class="text-xs leading-6 text-[#8a848b]">
                                    اطلاعات شما فقط برای پاسخگویی به درخواستتان استفاده می‌شود.
                                </p>

                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#3f207e] px-7 py-3.5 text-sm font-bold text-white shadow-lg shadow-[#3f207e]/15 transition hover:-translate-y-0.5 hover:bg-[#321866] focus:outline-none focus:ring-4 focus:ring-[#3f207e]/15"
                                >
                                    ارسال پیام

                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M22 2 11 13"/>
                                        <path d="m22 2-7 20-4-9-9-4Z"/>
                                    </svg>
                                </button>

                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </section>

        {{-- FAQ / Help --}}
        <section class="border-t border-[#e6e0d7] bg-white">
            <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-16">

                <div class="mx-auto max-w-2xl text-center">
                <span class="text-sm font-bold text-[#7b20df]">
                    قبل از تماس
                </span>

                    <h2 class="mt-2 text-3xl font-black text-[#17151b]">
                        شاید پاسخ سوالتان همین‌جا باشد
                    </h2>

                    <p class="mt-3 text-sm leading-7 text-[#77717e]">
                        برای سوالات متداول، راهنمای خرید و اطلاعات بیشتر می‌توانید بخش‌های مختلف فروشگاه را هم بررسی کنید.
                    </p>
                </div>

                <div class="mt-10 grid gap-5 md:grid-cols-3">

                    <a
                        href="{{ route('shop.index') }}"
                        class="group rounded-3xl border border-[#e4ded6] bg-[#faf9f7] p-6 transition hover:-translate-y-1 hover:border-[#d3c8df] hover:bg-white hover:shadow-lg"
                    >
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#eee8f5] text-[#3f207e]">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="m3 9 9-6 9 6"/>
                                <path d="M5 10v10h14V10"/>
                                <path d="M9 20v-6h6v6"/>
                            </svg>
                        </div>

                        <h3 class="mt-5 text-lg font-black text-[#17151b]">
                            مشاهده محصولات
                        </h3>

                        <p class="mt-2 text-sm leading-7 text-[#77717e]">
                            محصولات، قیمت‌ها و مشخصات فنی را بررسی کنید.
                        </p>

                        <span class="mt-4 inline-flex items-center gap-2 text-sm font-bold text-[#3f207e]">
                        رفتن به فروشگاه
                        <svg class="h-4 w-4 transition group-hover:-translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M15 18 9 12l6-6"/>
                        </svg>
                    </span>
                    </a>

                    <a
                        href="{{ route('blog.index') }}"
                        class="group rounded-3xl border border-[#e4ded6] bg-[#faf9f7] p-6 transition hover:-translate-y-1 hover:border-[#d3c8df] hover:bg-white hover:shadow-lg"
                    >
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#eee8f5] text-[#3f207e]">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M4 5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5Z"/>
                                <path d="M8 8h8M8 12h8M8 16h5"/>
                            </svg>
                        </div>

                        <h3 class="mt-5 text-lg font-black text-[#17151b]">
                            مجله فرزین
                        </h3>

                        <p class="mt-2 text-sm leading-7 text-[#77717e]">
                            راهنمای خرید، نکات کاربردی و مطالب تخصصی را بخوانید.
                        </p>

                        <span class="mt-4 inline-flex items-center gap-2 text-sm font-bold text-[#3f207e]">
                        مطالعه مطالب
                        <svg class="h-4 w-4 transition group-hover:-translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M15 18 9 12l6-6"/>
                        </svg>
                    </span>
                    </a>

                    @auth
                        <a
                            href="{{ route('customer.orders.index') }}"
                            class="group rounded-3xl border border-[#e4ded6] bg-[#faf9f7] p-6 transition hover:-translate-y-1 hover:border-[#d3c8df] hover:bg-white hover:shadow-lg"
                        >
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#eee8f5] text-[#3f207e]">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M6 4h12v16H6z"/>
                                    <path d="M9 8h6M9 12h6M9 16h4"/>
                                </svg>
                            </div>

                            <h3 class="mt-5 text-lg font-black text-[#17151b]">
                                سفارش‌های من
                            </h3>

                            <p class="mt-2 text-sm leading-7 text-[#77717e]">
                                وضعیت سفارش‌ها و جزئیات خریدهای خود را مشاهده کنید.
                            </p>

                            <span class="mt-4 inline-flex items-center gap-2 text-sm font-bold text-[#3f207e]">
                            مشاهده سفارش‌ها
                            <svg class="h-4 w-4 transition group-hover:-translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M15 18 9 12l6-6"/>
                            </svg>
                        </span>
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="group rounded-3xl border border-[#e4ded6] bg-[#faf9f7] p-6 transition hover:-translate-y-1 hover:border-[#d3c8df] hover:bg-white hover:shadow-lg"
                        >
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#eee8f5] text-[#3f207e]">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/>
                                    <path d="M4 21a8 8 0 0 1 16 0"/>
                                </svg>
                            </div>

                            <h3 class="mt-5 text-lg font-black text-[#17151b]">
                                حساب کاربری
                            </h3>

                            <p class="mt-2 text-sm leading-7 text-[#77717e]">
                                وارد حساب خود شوید و به امکانات و سفارش‌های خود دسترسی داشته باشید.
                            </p>

                            <span class="mt-4 inline-flex items-center gap-2 text-sm font-bold text-[#3f207e]">
                            ورود به حساب
                            <svg class="h-4 w-4 transition group-hover:-translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M15 18 9 12l6-6"/>
                            </svg>
                        </span>
                        </a>
                    @endauth

                </div>
            </div>
        </section>

    </div>

@endsection
