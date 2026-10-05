<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دسترسی غیرمجاز | فرزین</title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#0d1b3d">
    <link rel="icon" type="image/png" href="{{ asset('images/brand/logo.png') }}">
    @if (!app()->environment('testing'))
        @vite(['resources/css/app.css'])
    @endif
</head>

<body class="min-h-screen bg-[var(--color-neutral-50)] text-[var(--color-text-primary)] antialiased">
    <main class="flex min-h-screen items-center justify-center px-4 py-10">
        <section class="w-full max-w-xl rounded-[2rem] border border-[var(--color-border)] bg-white p-7 text-center shadow-[var(--shadow-lg)] sm:p-10">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[var(--color-danger-50)] text-2xl font-black text-[var(--color-danger-700)]">
                403
            </div>

            <div class="mt-6">
                <span class="farzin-eyebrow">ACCESS / FORBIDDEN</span>

                <h1 class="farzin-page-title mt-3">
                    دسترسی به این بخش مجاز نیست.
                </h1>

                <p class="mx-auto mt-4 max-w-md text-sm leading-7 text-[var(--color-text-secondary)]">
                    حساب فعلی شما اجازه دسترسی به این مسیر را ندارد.
                    از مسیر مربوط به حساب خودتان ادامه دهید.
                </p>
            </div>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="farzin-btn farzin-btn-primary"
                        >
                            بازگشت به پنل مدیریت
                        </a>
                    @elseif(auth()->user()->isCustomer())
                        <a
                            href="{{ route('customer.dashboard') }}"
                            class="farzin-btn farzin-btn-primary"
                        >
                            بازگشت به حساب من
                        </a>
                    @else
                        <a
                            href="{{ route('home') }}"
                            class="farzin-btn farzin-btn-primary"
                        >
                            بازگشت به خانه
                        </a>
                    @endif
                @else
                    <a
                        href="{{ route('login') }}"
                        class="farzin-btn farzin-btn-accent"
                    >
                        ورود به حساب
                    </a>
                @endauth

                <a
                    href="{{ route('home') }}"
                    class="farzin-btn farzin-btn-outline"
                >
                    فروشگاه
                </a>
            </div>
        </section>
    </main>
</body>
</html>
