<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        ورود | فرزین
    </title>

    <meta
        name="description"
        content="ورود به حساب کاربری فرزین"
    >

    <meta
        name="robots"
        content="noindex, nofollow"
    >

    <meta
        name="theme-color"
        content="#0d1b3d"
    >

    <link
        rel="icon"
        type="image/png"
        href="{{ asset('images/brand/logo.png') }}"
    >

    <link
        rel="apple-touch-icon"
        href="{{ asset('images/brand/logo.png') }}"
    >

    @vite([
    'resources/css/app.css',
    'resources/js/app.js'
    ])
</head>

<body class="min-h-screen bg-[var(--color-neutral-50)] text-[var(--color-text-primary)] antialiased">

<div class="min-h-screen lg:grid lg:grid-cols-[0.9fr_1.1fr]">

    {{-- =========================================================
        BRAND PANEL
    ========================================================== --}}

    <div class="relative hidden overflow-hidden bg-[var(--color-brand-950)] lg:flex">

        {{-- Decorative --}}
        <div
            class="pointer-events-none absolute -right-24 -top-24 h-80 w-80 rounded-full bg-[var(--color-accent-600)]/15 blur-3xl"
        ></div>

        <div
            class="pointer-events-none absolute -bottom-32 -left-20 h-96 w-96 rounded-full bg-[var(--color-brand-700)]/25 blur-3xl"
        ></div>

        <div
            class="pointer-events-none absolute inset-0 opacity-[0.035]"
            style="background-image: linear-gradient(rgba(255,255,255,.8) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.8) 1px, transparent 1px); background-size: 54px 54px;"
        ></div>


        <div class="relative flex w-full flex-col justify-between p-10 xl:p-14">

            {{-- Logo --}}
            <a
                href="{{ route('home') }}"
                class="inline-flex w-fit"
                aria-label="فرزین"
            >
                <img
                    src="{{ asset('images/brand/logo.png') }}"
                    alt="فرزین"
                    class="h-12 w-auto object-contain"
                >
            </a>


            {{-- Main copy --}}
            <div class="max-w-xl">

                <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/[0.05] px-3.5 py-2 text-[10px] font-black uppercase tracking-[0.18em] text-white/60">
                    <span class="h-1.5 w-1.5 rounded-full bg-[var(--color-accent-600)]"></span>
                    FARZIN ACCOUNT
                </div>

                <h1 class="mt-6 text-4xl font-black leading-[1.2] text-white xl:text-5xl">
                    به تجربه خرید
                    <span class="text-[var(--color-accent-500)]">
                        فرزین
                    </span>
                    خوش آمدی.
                </h1>

                <p class="mt-5 max-w-lg text-sm leading-8 text-white/55">
                    وارد حساب کاربری خودت شو و سفارش‌ها، علاقه‌مندی‌ها و
                    مسیر خریدت را راحت‌تر مدیریت کن.
                </p>


                {{-- Benefits --}}
                <div class="mt-8 space-y-3">

                    <div class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/[0.035] px-4 py-3.5">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white/10 text-white">
                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path d="M12 3 5 6v5c0 5 3 8.5 7 10 4-1.5 7-5 7-10V6l-7-3Z"/>
                                <path d="m9 12 2 2 4-4"/>
                            </svg>
                        </div>

                        <div>
                            <p class="text-xs font-black text-white">
                                حساب امن
                            </p>

                            <p class="mt-0.5 text-[10px] text-white/40">
                                اطلاعات حساب شما محافظت می‌شود.
                            </p>
                        </div>

                    </div>


                    <div class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/[0.035] px-4 py-3.5">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white/10 text-white">
                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path d="M3 4h2l2.4 11.2a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 2-1.6L21 7H6"/>
                                <circle cx="10" cy="20" r="1"/>
                                <circle cx="18" cy="20" r="1"/>
                            </svg>
                        </div>

                        <div>
                            <p class="text-xs font-black text-white">
                                مدیریت ساده سفارش‌ها
                            </p>

                            <p class="mt-0.5 text-[10px] text-white/40">
                                سفارش‌ها و خریدهای قبلی را همیشه در دسترس داری.
                            </p>
                        </div>

                    </div>


                    <div class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/[0.035] px-4 py-3.5">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white/10 text-[var(--color-accent-400)]">
                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path d="M20.8 8.9c0 5.1-8.8 10.2-8.8 10.2S3.2 14 3.2 8.9A5 5 0 0 1 8.1 4c1.5 0 3 .7 3.9 1.9A4.9 4.9 0 0 1 15.9 4a5 5 0 0 1 4.9 4.9Z"/>
                            </svg>
                        </div>

                        <div>
                            <p class="text-xs font-black text-white">
                                علاقه‌مندی‌های شما
                            </p>

                            <p class="mt-0.5 text-[10px] text-white/40">
                                محصولات مورد علاقه‌ات را ذخیره و دنبال کن.
                            </p>
                        </div>

                    </div>

                </div>

            </div>


            {{-- Footer --}}
            <p class="text-[10px] font-medium text-white/30">
                © {{ now()->year }} فرزین. تمامی حقوق محفوظ است.
            </p>

        </div>

    </div>


    {{-- =========================================================
        LOGIN AREA
    ========================================================== --}}

    <div class="flex min-h-screen items-center justify-center px-4 py-10 sm:px-6 lg:px-10">

        <div class="w-full max-w-md">

            {{-- Mobile brand --}}
            <div class="mb-8 text-center lg:hidden">

                <a
                    href="{{ route('home') }}"
                    class="inline-flex"
                    aria-label="فرزین"
                >
                    <img
                        src="{{ asset('images/brand/logo.png') }}"
                        alt="فرزین"
                        class="mx-auto h-11 w-auto object-contain"
                    >
                </a>

                <p class="mt-3 text-sm text-[var(--color-text-secondary)]">
                    خوش برگشتی؛ وارد حساب کاربری خودت شو.
                </p>

            </div>


            {{-- Card --}}
            <div class="overflow-hidden rounded-[2rem] border border-[var(--color-border)] bg-white shadow-[var(--shadow-lg)]">

                {{-- Card Header --}}
                <div class="border-b border-[var(--color-border)] px-6 py-7 sm:px-8">

                    <div class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-[var(--color-brand-50)] text-[var(--color-brand-900)]">
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <circle cx="12" cy="8" r="3.5"/>
                            <path d="M5 20a7 7 0 0 1 14 0"/>
                        </svg>
                    </div>

                    <h1 class="mt-5 text-2xl font-black text-[var(--color-text-primary)] sm:text-3xl">
                        ورود به حساب
                    </h1>

                    <p class="mt-2 text-sm leading-7 text-[var(--color-text-secondary)]">
                        اطلاعات حساب خود را وارد کنید تا ادامه دهید.
                    </p>

                </div>


                <div class="px-6 py-6 sm:px-8 sm:py-8">

                    {{-- =================================================
                        Success
                    ================================================== --}}

                    @if(session('success'))

                        <div
                            class="mb-6 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3.5"
                            role="status"
                        >

                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">
                                <svg
                                    class="h-4 w-4"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path d="m5 12 4 4L19 6"/>
                                </svg>
                            </div>

                            <p class="pt-1 text-xs font-bold leading-6 text-emerald-800">
                                {{ session('success') }}
                            </p>

                        </div>

                    @endif


                    {{-- =================================================
                        Errors
                    ================================================== --}}

                    @if($errors->any())

                        <div
                            class="mb-6 overflow-hidden rounded-2xl border border-red-200 bg-white"
                            role="alert"
                        >

                            <div class="flex items-center gap-3 border-b border-red-100 bg-red-50 px-4 py-3">

                                <svg
                                    class="h-5 w-5 shrink-0 text-red-600"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <circle cx="12" cy="12" r="9"/>
                                    <path d="M12 8v5"/>
                                    <path d="M12 16h.01"/>
                                </svg>

                                <p class="text-xs font-black text-red-800">
                                    ورود انجام نشد
                                </p>

                            </div>

                            <div class="px-4 py-3">

                                <ul class="space-y-1.5">

                                    @foreach($errors->all() as $error)

                                        <li class="flex items-start gap-2 text-xs font-medium leading-6 text-red-700">

                                            <span class="mt-2 h-1 w-1 shrink-0 rounded-full bg-red-500"></span>

                                            <span>
                                                {{ $error }}
                                            </span>

                                        </li>

                                    @endforeach

                                </ul>

                            </div>

                        </div>

                    @endif


                    {{-- =================================================
                        Form
                    ================================================== --}}

                    <form
                        action="{{ route('login.store') }}"
                        method="POST"
                        class="space-y-5"
                    >
                        @csrf


                        {{-- Email --}}
                        <div>

                            <label
                                for="email"
                                class="mb-2.5 block text-sm font-black text-[var(--color-text-primary)]"
                            >
                                ایمیل
                            </label>

                            <div class="relative">

                                <span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-[var(--color-text-soft)]">

                                    <svg
                                        class="h-5 w-5"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                    >
                                        <rect
                                            x="3"
                                            y="4"
                                            width="18"
                                            height="16"
                                            rx="2"
                                        />
                                        <path d="m3 7 9 6 9-6"/>
                                    </svg>

                                </span>


                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    autocomplete="email"
                                    required
                                    autofocus
                                    dir="ltr"
                                    placeholder="you@example.com"
                                    class="w-full rounded-2xl border bg-[var(--color-neutral-50)] py-3.5 pr-12 pl-4 text-left text-sm text-[var(--color-text-primary)] outline-none transition duration-200 placeholder:text-[var(--color-text-soft)] focus:bg-white focus:ring-4 @error('email') border-red-300 focus:border-red-500 focus:ring-red-500/10 @else border-[var(--color-border)] focus:border-[var(--color-brand-900)] focus:ring-[var(--color-brand-900)]/10 @enderror"
                                >

                            </div>


                            @error('email')

                            <p class="mt-2 text-xs font-semibold leading-5 text-red-600">
                                {{ $message }}
                            </p>

                            @enderror

                        </div>


                        {{-- Password --}}
                        <div>

                            <label
                                for="password"
                                class="mb-2.5 block text-sm font-black text-[var(--color-text-primary)]"
                            >
                                رمز عبور
                            </label>

                            <div class="relative">

                                <span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-[var(--color-text-soft)]">

                                    <svg
                                        class="h-5 w-5"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                    >
                                        <rect
                                            x="4"
                                            y="10"
                                            width="16"
                                            height="11"
                                            rx="2"
                                        />

                                        <path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                                    </svg>

                                </span>


                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    autocomplete="current-password"
                                    required
                                    dir="ltr"
                                    placeholder="••••••••"
                                    class="w-full rounded-2xl border bg-[var(--color-neutral-50)] py-3.5 pr-12 pl-4 text-left text-sm text-[var(--color-text-primary)] outline-none transition duration-200 placeholder:text-[var(--color-text-soft)] focus:bg-white focus:ring-4 @error('password') border-red-300 focus:border-red-500 focus:ring-red-500/10 @else border-[var(--color-border)] focus:border-[var(--color-brand-900)] focus:ring-[var(--color-brand-900)]/10 @enderror"
                                >

                            </div>


                            @error('password')

                            <p class="mt-2 text-xs font-semibold leading-5 text-red-600">
                                {{ $message }}
                            </p>

                            @enderror

                        </div>


                        {{-- Remember --}}
                        <div class="flex items-center justify-between gap-4">

                            <label class="inline-flex cursor-pointer items-center gap-3">

                                <input
                                    type="checkbox"
                                    name="remember"
                                    value="1"
                                    @checked(old('remember'))
                                class="h-4 w-4 rounded border-[var(--color-border)] accent-[var(--color-accent-600)] focus:ring-[var(--color-accent-600)]"
                                >

                                <span class="text-xs font-bold text-[var(--color-text-secondary)]">
                                    مرا به خاطر بسپار
                                </span>

                            </label>

                        </div>


                        {{-- Submit --}}
                        <button
                            type="submit"
                            class="group flex min-h-14 w-full items-center justify-center gap-3 rounded-2xl bg-[var(--color-accent-600)] px-5 py-4 text-sm font-black text-white shadow-lg shadow-[var(--color-accent-600)]/15 transition duration-200 hover:-translate-y-0.5 hover:bg-[var(--color-accent-700)] focus:outline-none focus:ring-4 focus:ring-[var(--color-accent-600)]/15"
                        >

                            ورود به حساب

                            <svg
                                class="h-4 w-4 transition duration-200 group-hover:-translate-x-1"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                aria-hidden="true"
                            >
                                <path d="m9 18 6-6-6-6"/>
                            </svg>

                        </button>

                    </form>


                    {{-- Register --}}
                    <div class="mt-7 border-t border-[var(--color-border)] pt-6 text-center">

                        <span class="text-sm text-[var(--color-text-secondary)]">
                            هنوز حساب کاربری نداری؟
                        </span>

                        <a
                            href="{{ route('register') }}"
                            class="mr-1 text-sm font-black text-[var(--color-accent-600)] transition hover:text-[var(--color-accent-700)]"
                        >
                            ثبت‌نام کن
                        </a>

                    </div>

                </div>

            </div>


            {{-- Back --}}
            <div class="mt-5 text-center">

                <a
                    href="{{ route('home') }}"
                    class="inline-flex items-center gap-2 text-xs font-bold text-[var(--color-text-secondary)] transition hover:text-[var(--color-brand-900)]"
                >

                    <svg
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <path d="m15 18-6-6 6-6"/>
                    </svg>

                    بازگشت به فروشگاه

                </a>

            </div>

        </div>

    </div>

</div>

</body>
</html>
