<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ثبت‌نام | {{ config('app.name', 'Farzin') }}</title>

    <meta name="description" content="ساخت حساب کاربری در فرزین برای مدیریت سفارش‌ها، آدرس‌ها و خرید آسان‌تر.">
    <meta name="robots" content="noindex,nofollow">

    <meta name="theme-color" content="#0d1b3d">

    <link rel="icon" type="image/png" href="{{ asset('images/brand/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/brand/logo.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[var(--color-neutral-50)] text-[var(--color-text-primary)]">

<div class="flex min-h-screen">

    {{-- =========================================================
         Brand Panel
    ========================================================== --}}
    <aside class="relative hidden w-[42%] overflow-hidden bg-[var(--color-brand-950)] lg:flex">

        {{-- Decorative background --}}
        <div class="absolute inset-0">
            <div class="absolute -right-32 -top-32 h-96 w-96 rounded-full bg-[var(--color-brand-900)] opacity-70 blur-3xl"></div>
            <div class="absolute -bottom-32 -left-32 h-96 w-96 rounded-full bg-[var(--color-accent-600)] opacity-10 blur-3xl"></div>

            <div
                class="absolute inset-0 opacity-[0.06]"
                style="
                    background-image:
                        linear-gradient(var(--color-neutral-0) 1px, transparent 1px),
                        linear-gradient(90deg, var(--color-neutral-0) 1px, transparent 1px);
                    background-size: 42px 42px;
                "
            ></div>
        </div>

        <div class="relative z-10 flex h-full w-full flex-col justify-between p-10 xl:p-14">

            {{-- Brand --}}
            <div>
                <a href="{{ route('home') }}" class="inline-flex items-center">
                    <div class="rounded-2xl bg-white px-4 py-3 shadow-lg shadow-black/10">
                        <img
                            src="{{ asset('images/brand/logo.png') }}"
                            alt="{{ config('app.name', 'Farzin') }}"
                            class="h-10 w-auto object-contain"
                        >
                    </div>
                </a>

                <div class="mt-10 max-w-lg">

                    <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3.5 py-2 text-xs font-bold text-white/80 backdrop-blur-sm">
                        <span class="h-2 w-2 rounded-full bg-[var(--color-accent-500)]"></span>
                        حساب کاربری فرزین
                    </div>

                    <h1 class="text-4xl font-black leading-[1.25] text-white xl:text-5xl">
                        شروع یک تجربه
                        <span class="text-[var(--color-accent-400)]">
                            بهتر
                        </span>
                        برای خرید
                    </h1>

                    <p class="mt-6 max-w-md text-sm leading-8 text-white/65 xl:text-base">
                        با ساخت حساب کاربری، سفارش‌ها، آدرس‌ها و اطلاعات خریدت را
                        ساده‌تر و منظم‌تر مدیریت کن.
                    </p>

                </div>

                {{-- Benefits --}}
                <div class="mt-10 space-y-4">

                    <div class="flex items-start gap-4 rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-sm">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/10 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="1.8"
                                 class="h-5 w-5">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M20 7H4a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2Z"/>
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2M2 11h20"/>
                            </svg>
                        </div>

                        <div>
                            <h3 class="text-sm font-black text-white">
                                مدیریت سفارش‌ها
                            </h3>

                            <p class="mt-1 text-xs leading-6 text-white/55">
                                وضعیت و سوابق سفارش‌ها را همیشه در دسترس داشته باش.
                            </p>
                        </div>
                    </div>


                    <div class="flex items-start gap-4 rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-sm">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/10 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="1.8"
                                 class="h-5 w-5">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M12 21s7-4.35 7-10V5l-7-3-7 3v6c0 5.65 7 10 7 10Z"/>
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="m9 12 2 2 4-4"/>
                            </svg>
                        </div>

                        <div>
                            <h3 class="text-sm font-black text-white">
                                خرید امن و مطمئن
                            </h3>

                            <p class="mt-1 text-xs leading-6 text-white/55">
                                اطلاعات حساب و خریدت در فضای امن مدیریت می‌شود.
                            </p>
                        </div>
                    </div>


                    <div class="flex items-start gap-4 rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-sm">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[var(--color-accent-600)]/15 text-[var(--color-accent-300)]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="1.8"
                                 class="h-5 w-5">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z"/>
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M8 15h.01M12 15h.01M16 15h.01M8 18h.01M12 18h.01M16 18h.01"/>
                            </svg>
                        </div>

                        <div>
                            <h3 class="text-sm font-black text-white">
                                تجربه خرید سریع‌تر
                            </h3>

                            <p class="mt-1 text-xs leading-6 text-white/55">
                                اطلاعات حساب و آدرس‌ها را یک‌بار ثبت کن و راحت‌تر خرید کن.
                            </p>
                        </div>
                    </div>

                </div>
            </div>


            {{-- Footer --}}
            <div class="flex items-center justify-between gap-4 border-t border-white/10 pt-6">

                <span class="text-xs text-white/35">
                    © {{ now()->year }} {{ config('app.name', 'Farzin') }}
                </span>

                <a href="{{ route('home') }}"
                   class="text-xs font-bold text-white/55 transition hover:text-white">
                    بازگشت به فروشگاه
                </a>

            </div>

        </div>

    </aside>


    {{-- =========================================================
         Register Area
    ========================================================== --}}
    <main class="flex min-h-screen flex-1 items-center justify-center px-4 py-8 sm:px-6 lg:px-10">

        <div class="w-full max-w-lg">

            {{-- Mobile brand --}}
            <div class="mb-8 text-center lg:hidden">

                <a href="{{ route('home') }}"
                   class="inline-flex items-center rounded-2xl bg-white px-4 py-3 shadow-sm ring-1 ring-[var(--color-border)]">

                    <img
                        src="{{ asset('images/brand/logo.png') }}"
                        alt="{{ config('app.name', 'Farzin') }}"
                        class="h-9 w-auto object-contain"
                    >

                </a>

                <p class="mt-3 text-xs text-[var(--color-text-muted)]">
                    فروشگاه آنلاین فرزین
                </p>

            </div>


            {{-- Heading --}}
            <div class="mb-7">

                <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-[var(--color-accent-50)] px-3.5 py-2 text-xs font-black text-[var(--color-accent-700)]">
                    <span class="h-2 w-2 rounded-full bg-[var(--color-accent-600)]"></span>
                    ساخت حساب کاربری
                </div>

                <h2 class="text-2xl font-black tracking-tight text-[var(--color-brand-950)] sm:text-3xl">
                    به فرزین خوش اومدی
                </h2>

                <p class="mt-2 text-sm leading-7 text-[var(--color-text-secondary)]">
                    چند لحظه وقت بذار و حساب خودت رو بساز.
                </p>

            </div>


            {{-- Card --}}
            <div class="rounded-[2rem] border border-[var(--color-border)] bg-white p-5 shadow-[0_20px_60px_rgba(13,27,61,0.08)] sm:p-8">

                {{-- Errors --}}
                @if($errors->any())

                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-4">

                        <div class="flex items-start gap-3">

                            <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="1.8"
                                     class="h-4 w-4">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M12 9v4m0 4h.01M10.3 3.8 2.6 17a2 2 0 0 0 1.73 3h15.34a2 2 0 0 0 1.73-3L13.7 3.8a2 2 0 0 0-3.4 0Z"/>
                                </svg>
                            </div>

                            <div class="min-w-0">
                                <div class="text-sm font-black text-red-800">
                                    لطفاً اطلاعات واردشده را بررسی کن.
                                </div>

                                <ul class="mt-2 space-y-1 text-xs leading-6 text-red-600">
                                    @foreach($errors->all() as $error)
                                        <li class="flex items-start gap-2">
                                            <span class="mt-2 h-1 w-1 shrink-0 rounded-full bg-current"></span>
                                            <span>{{ $error }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                        </div>

                    </div>

                @endif


                {{-- Form --}}
                <form action="{{ route('register.store') }}"
                      method="POST"
                      class="space-y-5">

                    @csrf


                    {{-- Name --}}
                    <div>

                        <label for="name"
                               class="mb-2 block text-sm font-black text-[var(--color-text-primary)]">
                            نام
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            autocomplete="name"
                            required
                            autofocus
                            placeholder="نام شما"
                            class="w-full rounded-2xl border border-[var(--color-border)] bg-[var(--color-neutral-50)] px-4 py-3.5 text-sm text-[var(--color-text-primary)] outline-none transition duration-200 placeholder:text-[var(--color-text-muted)] hover:border-[var(--color-border-strong)] focus:border-[var(--color-brand-600)] focus:bg-white focus:ring-4 focus:ring-[var(--color-brand-100)] @error('name') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                        >

                        @error('name')
                        <p class="mt-2 text-xs font-medium leading-5 text-red-600">
                            {{ $message }}
                        </p>
                        @enderror

                    </div>


                    {{-- Email --}}
                    <div>

                        <label for="email"
                               class="mb-2 block text-sm font-black text-[var(--color-text-primary)]">
                            ایمیل
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            required
                            dir="ltr"
                            inputmode="email"
                            placeholder="you@example.com"
                            class="w-full rounded-2xl border border-[var(--color-border)] bg-[var(--color-neutral-50)] px-4 py-3.5 text-sm text-[var(--color-text-primary)] outline-none transition duration-200 placeholder:text-[var(--color-text-muted)] hover:border-[var(--color-border-strong)] focus:border-[var(--color-brand-600)] focus:bg-white focus:ring-4 focus:ring-[var(--color-brand-100)] @error('email') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                        >

                        @error('email')
                        <p class="mt-2 text-xs font-medium leading-5 text-red-600">
                            {{ $message }}
                        </p>
                        @enderror

                    </div>


                    {{-- Password --}}
                    <div>

                        <label for="password"
                               class="mb-2 block text-sm font-black text-[var(--color-text-primary)]">
                            رمز عبور
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            autocomplete="new-password"
                            required
                            dir="ltr"
                            placeholder="••••••••"
                            minlength="8"
                            class="w-full rounded-2xl border border-[var(--color-border)] bg-[var(--color-neutral-50)] px-4 py-3.5 text-sm text-[var(--color-text-primary)] outline-none transition duration-200 placeholder:text-[var(--color-text-muted)] hover:border-[var(--color-border-strong)] focus:border-[var(--color-brand-600)] focus:bg-white focus:ring-4 focus:ring-[var(--color-brand-100)] @error('password') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                        >

                        <div class="mt-2 flex items-center justify-between gap-3">
                            <p class="text-[11px] leading-5 text-[var(--color-text-muted)]">
                                حداقل ۸ کاراکتر
                            </p>
                        </div>

                        @error('password')
                        <p class="mt-1 text-xs font-medium leading-5 text-red-600">
                            {{ $message }}
                        </p>
                        @enderror

                    </div>


                    {{-- Confirm Password --}}
                    <div>

                        <label for="password_confirmation"
                               class="mb-2 block text-sm font-black text-[var(--color-text-primary)]">
                            تکرار رمز عبور
                        </label>

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            autocomplete="new-password"
                            required
                            dir="ltr"
                            placeholder="••••••••"
                            minlength="8"
                            class="w-full rounded-2xl border border-[var(--color-border)] bg-[var(--color-neutral-50)] px-4 py-3.5 text-sm text-[var(--color-text-primary)] outline-none transition duration-200 placeholder:text-[var(--color-text-muted)] hover:border-[var(--color-border-strong)] focus:border-[var(--color-brand-600)] focus:bg-white focus:ring-4 focus:ring-[var(--color-brand-100)] @error('password_confirmation') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                        >

                        @error('password_confirmation')
                        <p class="mt-2 text-xs font-medium leading-5 text-red-600">
                            {{ $message }}
                        </p>
                        @enderror

                    </div>


                    {{-- Account Notice --}}
                    <div class="rounded-2xl border border-[var(--color-brand-100)] bg-[var(--color-brand-50)] p-4">

                        <div class="flex items-start gap-3">

                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-[var(--color-brand-700)] shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="1.8"
                                     class="h-4 w-4">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M12 17v.01M12 13a3 3 0 1 0-3-3"/>
                                    <circle cx="12"
                                            cy="12"
                                            r="9"/>
                                </svg>
                            </div>

                            <p class="text-xs leading-6 text-[var(--color-text-secondary)]">
                                با ایجاد حساب کاربری، می‌تونی سفارش‌ها، آدرس‌ها و اطلاعات خریدت رو راحت‌تر مدیریت کنی.
                            </p>

                        </div>

                    </div>


                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="group inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-[var(--color-accent-600)] px-5 py-3.5 text-sm font-black text-white shadow-lg shadow-[rgba(226,31,47,0.18)] transition duration-200 hover:-translate-y-0.5 hover:bg-[var(--color-accent-700)] hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-[var(--color-accent-100)] active:translate-y-0">

                        <span>
                            ایجاد حساب کاربری
                        </span>

                        <svg xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="1.8"
                             class="h-4 w-4 transition-transform duration-200 group-hover:-translate-x-1">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M5 12h14M13 6l6 6-6 6"/>
                        </svg>

                    </button>

                </form>


                {{-- Login --}}
                <div class="mt-7 border-t border-[var(--color-border)] pt-6 text-center">

                    <span class="text-sm text-[var(--color-text-secondary)]">
                        قبلاً حساب ساخته‌ای؟
                    </span>

                    <a
                        href="{{ route('login') }}"
                        class="mr-1 text-sm font-black text-[var(--color-brand-700)] transition hover:text-[var(--color-accent-600)]">
                        وارد شو
                    </a>

                </div>

            </div>


            {{-- Bottom Links --}}
            <div class="mt-5 flex items-center justify-center gap-4 text-xs text-[var(--color-text-muted)]">

                <a href="{{ route('home') }}"
                   class="font-bold transition hover:text-[var(--color-brand-800)]">
                    فروشگاه
                </a>

                <span class="h-1 w-1 rounded-full bg-[var(--color-border-strong)]"></span>

                <a href="{{ route('contact.index') }}"
                   class="font-bold transition hover:text-[var(--color-brand-800)]">
                    تماس با ما
                </a>

            </div>

        </div>

    </main>

</div>

</body>
</html>
