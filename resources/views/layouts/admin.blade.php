<!DOCTYPE html>
<html
    lang="fa"
    dir="rtl"
>
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

    {{-- =========================================================
        SEO
    ========================================================== --}}

    <title>
        @yield('title', 'پنل مدیریت | فرزین')
    </title>

    <meta
        name="description"
        content="@yield('meta_description', 'پنل مدیریت فروشگاه فرزین')"
    >

    <meta
        name="robots"
        content="noindex, nofollow"
    >

    {{-- =========================================================
        Brand
    ========================================================== --}}

    <meta
        name="theme-color"
        content="#090909"
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

    {{-- =========================================================
        Assets
    ========================================================== --}}

    @if (!app()->environment('testing'))
        @vite(['resources/js/app.js'])
    @endif

    @stack('head')
</head>

<body
    class="min-h-screen bg-[var(--color-neutral-50)] text-[var(--color-text-primary)] antialiased"
>

<div class="min-h-screen">

    {{-- =========================================================
        Mobile Backdrop
    ========================================================== --}}

    <div
        data-admin-sidebar-backdrop
        hidden
        class="hidden fixed inset-0 z-40 bg-[var(--color-brand-950)]/45 backdrop-blur-sm lg:hidden"
        aria-hidden="true"
    ></div>


    {{-- =========================================================
        Sidebar
    ========================================================== --}}

    @include('admin.partials.sidebar')


    {{-- =========================================================
        Main Area
    ========================================================== --}}

    <div class="min-h-screen lg:mr-[280px]">

        {{-- =====================================================
            Topbar
        ====================================================== --}}

        <header
            class="sticky top-0 z-30 border-b border-[var(--color-border)] bg-white/90 backdrop-blur-xl"
        >
            <div
                class="flex min-h-[72px] items-center justify-between gap-4 px-4 sm:px-6 lg:px-8"
            >

                {{-- =================================================
                    Right Side
                ================================================== --}}

                <div class="flex min-w-0 items-center gap-3">

                    {{-- Mobile Menu --}}
                    <button
                        type="button"
                        data-admin-sidebar-open
                        aria-expanded="false"
                        class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-[var(--color-border)] bg-white text-[var(--color-text-secondary)] transition duration-200 hover:border-[var(--color-brand-300)] hover:bg-[var(--color-brand-50)] hover:text-[var(--color-brand-900)] lg:hidden"
                        aria-label="باز کردن منو"
                        aria-controls="admin-sidebar"
                    >
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            aria-hidden="true"
                        >
                            <path d="M4 7h16" />
                            <path d="M4 12h16" />
                            <path d="M4 17h16" />
                        </svg>
                    </button>


                    {{-- Page Heading --}}
                    <div class="min-w-0">

                        <div
                            class="truncate text-sm font-black text-[var(--color-text-primary)] sm:text-base"
                        >
                            @yield('page_title', 'داشبورد')
                        </div>

                        <div
                            class="mt-0.5 hidden text-xs text-[var(--color-text-muted)] sm:block"
                        >
                            مدیریت فروشگاه فرزین
                        </div>

                    </div>

                </div>


                {{-- =================================================
                    Left Side
                ================================================== --}}

                <div class="flex shrink-0 items-center gap-2">


                    {{-- =================================================
                        Visit Store
                    ================================================== --}}

                    <a
                        href="{{ route('home') }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="hidden h-10 items-center gap-2 rounded-xl border border-[var(--color-border)] bg-white px-3 text-sm font-bold text-[var(--color-text-secondary)] transition duration-200 hover:border-[var(--color-brand-300)] hover:bg-[var(--color-brand-50)] hover:text-[var(--color-brand-900)] sm:inline-flex"
                    >

                        <svg
                            class="h-4 w-4"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            aria-hidden="true"
                        >
                            <path d="M3 10.5 12 4l9 6.5" />
                            <path d="M5.5 9.5V20h13V9.5" />
                            <path d="M9.5 20v-5h5v5" />
                        </svg>

                        مشاهده سایت

                    </a>


                    {{-- =================================================
                        Profile
                    ================================================== --}}

                    <div class="relative" data-admin-profile>

                        <button
                            type="button"
                            data-admin-profile-toggle
                            aria-expanded="false"
                            aria-haspopup="menu"
                            class="flex items-center gap-2 rounded-xl border border-[var(--color-border)] bg-white px-2 py-2 transition duration-200 hover:border-[var(--color-brand-300)] hover:bg-[var(--color-brand-50)] sm:gap-3 sm:px-2.5"
                        >

                            {{-- Avatar --}}
                            <span
                                class="relative flex h-9 w-9 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-[var(--color-brand-900)] text-sm font-black text-white shadow-sm"
                            >
                                {{ mb_substr(auth()->user()->name ?? 'A', 0, 1) }}

                                <span
                                    class="absolute bottom-0.5 left-0.5 h-2.5 w-2.5 rounded-full border-2 border-[var(--color-brand-900)] bg-emerald-400"
                                    aria-hidden="true"
                                ></span>
                            </span>


                            {{-- User Details --}}
                            <span class="hidden text-right sm:block">

                                <span
                                    class="block max-w-[130px] truncate text-xs font-black text-[var(--color-text-primary)]"
                                >
                                    {{ auth()->user()->name ?? 'Admin' }}
                                </span>

                                <span
                                    class="mt-0.5 block text-[11px] font-medium text-[var(--color-text-muted)]"
                                >
                                    مدیر سیستم
                                </span>

                            </span>


                            {{-- Chevron --}}
                            <svg
                                data-admin-profile-chevron
                                class="hidden h-4 w-4 text-[var(--color-text-muted)] transition duration-200 sm:block"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                aria-hidden="true"
                            >
                                <path d="m7 10 5 5 5-5" />
                            </svg>

                        </button>


                        {{-- =================================================
                            Profile Dropdown
                        ================================================== --}}

                        <div
                            data-admin-profile-menu
                            hidden
                            class="hidden absolute left-0 top-[calc(100%+10px)] z-50 w-64 overflow-hidden rounded-2xl border border-[var(--color-border)] bg-white p-2 shadow-[var(--shadow-lg)]"
                            role="menu"
                        >

                            {{-- Profile header --}}
                            <div
                                class="mb-1 rounded-xl bg-[var(--color-brand-50)] p-3"
                            >
                                <div class="flex items-center gap-3">

                                    <span
                                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[var(--color-brand-900)] text-sm font-black text-white"
                                    >
                                        {{ mb_substr(auth()->user()->name ?? 'A', 0, 1) }}
                                    </span>

                                    <div class="min-w-0">

                                        <p
                                            class="truncate text-xs font-black text-[var(--color-text-primary)]"
                                        >
                                            {{ auth()->user()->name ?? 'Admin' }}
                                        </p>

                                        <p
                                            class="mt-0.5 truncate text-[11px] text-[var(--color-text-muted)]"
                                        >
                                            {{ auth()->user()->email ?? 'admin' }}
                                        </p>

                                    </div>

                                </div>
                            </div>


                            {{-- Visit Website --}}
                            <a
                                href="{{ route('home') }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                role="menuitem"
                                data-admin-profile-close
                                class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-bold text-[var(--color-text-secondary)] transition duration-150 hover:bg-[var(--color-neutral-50)] hover:text-[var(--color-text-primary)]"
                            >

                                <span
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-[var(--color-neutral-100)] text-[var(--color-brand-900)]"
                                >
                                    <svg
                                        class="h-4 w-4"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        aria-hidden="true"
                                    >
                                        <path d="M3 10.5 12 4l9 6.5" />
                                        <path d="M5.5 9.5V20h13V9.5" />
                                        <path d="M9.5 20v-5h5v5" />
                                    </svg>
                                </span>

                                مشاهده سایت

                            </a>


                            <div
                                class="my-1.5 border-t border-[var(--color-border)]"
                            ></div>


                            {{-- Logout --}}
                            <form
                                method="POST"
                                action="{{ route('logout') }}"
                            >
                                @csrf

                                <button
                                    type="submit"
                                    role="menuitem"
                                    class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-bold text-[var(--color-danger-700)] transition duration-150 hover:bg-[var(--color-danger-50)]"
                                >

                                    <span
                                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-[var(--color-danger-50)]"
                                    >
                                        <svg
                                            class="h-4 w-4"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                            aria-hidden="true"
                                        >
                                            <path d="M10 17l5-5-5-5" />
                                            <path d="M15 12H3" />
                                            <path d="M21 3v18" />
                                        </svg>
                                    </span>

                                    خروج از حساب

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>
        </header>


        {{-- =====================================================
            Page Content
        ====================================================== --}}

        <main
            class="px-4 py-6 sm:px-6 lg:px-8 lg:py-8"
        >

            {{-- =================================================
                Flash Success
            ================================================== --}}

            @if(session('success'))

                <div
                    class="mb-6 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm text-emerald-800 shadow-sm"
                    data-dismissible-alert
                    role="status"
                >

                    <div
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700"
                    >
                        <svg
                            class="h-4 w-4"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            aria-hidden="true"
                        >
                            <path d="m5 12 4 4L19 6" />
                        </svg>
                    </div>

                    <div class="min-w-0 flex-1 pt-1 font-bold">
                        {{ session('success') }}
                    </div>

                    <button type="button" data-alert-dismiss class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-emerald-700 transition hover:bg-emerald-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-700" aria-label="بستن پیام موفقیت">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m6 6 12 12"/><path d="M18 6 6 18"/></svg>
                    </button>

                </div>

            @endif


            {{-- =================================================
                Flash Error
            ================================================== --}}

            @if(session('error'))

                <div
                    class="mb-6 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm text-red-800 shadow-sm"
                    data-dismissible-alert
                    role="alert"
                >

                    <div
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-red-100 text-red-700"
                    >
                        <svg
                            class="h-4 w-4"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            aria-hidden="true"
                        >
                            <circle cx="12" cy="12" r="9" />
                            <path d="M12 8v5" />
                            <path d="M12 16h.01" />
                        </svg>
                    </div>

                    <div class="min-w-0 flex-1 pt-1 font-bold">
                        {{ session('error') }}
                    </div>

                    <button type="button" data-alert-dismiss class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-red-700 transition hover:bg-red-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-700" aria-label="بستن پیام خطا">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m6 6 12 12"/><path d="M18 6 6 18"/></svg>
                    </button>

                </div>

            @endif


            {{-- =================================================
                Validation Errors
            ================================================== --}}

            @if($errors->any())
                <section
                    id="validation-summary"
                    data-validation-summary
                    tabindex="-1"
                    aria-labelledby="validation-error-heading"
                    class="mb-6 overflow-hidden rounded-2xl border border-red-200 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-red-300 focus:ring-offset-2"
                    role="alert"
                >
                    <div class="flex items-center gap-3 border-b border-red-100 bg-red-50 px-4 py-3.5">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-red-100 text-red-700" aria-hidden="true">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9" /><path d="M12 8v5" /><path d="M12 16h.01" /></svg>
                        </div>
                        <div>
                            <h2 id="validation-error-heading" class="text-sm font-black text-red-800">خطا در اطلاعات واردشده</h2>
                            <p class="mt-0.5 text-xs text-red-600">برای رفتن به فیلد مربوط، روی هر خطا کلیک کنید.</p>
                        </div>
                    </div>

                    <div class="px-4 py-4">
                        <ul class="space-y-2">
                            @foreach($errors->getMessages() as $field => $messages)
                                @php($fieldId = \Illuminate\Support\Str::before($field, '.'))
                                @foreach($messages as $error)
                                    <li class="flex items-start gap-2 text-xs font-semibold leading-6 text-red-700">
                                        <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-red-500" aria-hidden="true"></span>
                                        <a
                                            href="#{{ $fieldId }}"
                                            data-validation-error-link
                                            data-validation-target="{{ $fieldId }}"
                                            class="underline decoration-dotted underline-offset-4 hover:text-red-900 focus-visible:rounded focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-700"
                                        >{{ $error }} <span class="sr-only">— رفتن به فیلد مربوط</span></a>
                                    </li>
                                @endforeach
                            @endforeach
                        </ul>
                    </div>
                </section>
            @endif


            {{-- =================================================
                Content
            ================================================== --}}

            @yield('content')

        </main>

    </div>

</div>


{{-- =============================================================
    Scripts
============================================================== --}}

@stack('scripts')

</body>
</html>
