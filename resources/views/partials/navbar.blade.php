<nav
    x-data="{ open: false }"
    class="sticky top-0 z-50 border-b border-[var(--color-border)] bg-white/95 shadow-[0_1px_12px_rgb(16_23_34_/0.04)] backdrop-blur-xl"
>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="flex min-h-[76px] items-center justify-between gap-4 lg:gap-6">

            {{-- =========================================================
                Logo
            ========================================================== --}}

            <a
                href="{{ route('home') }}"
                class="group flex shrink-0 items-center"
                aria-label="فرزین - صفحه اصلی"
            >
                <img
                    src="{{ asset('images/brand/logo.png') }}"
                    alt="فرزین"
                    class="h-11 w-auto object-contain transition duration-200 group-hover:opacity-90 sm:h-12"
                >
            </a>


            {{-- =========================================================
                Desktop Navigation
            ========================================================== --}}

            <nav
                class="hidden items-center gap-1 lg:flex"
                aria-label="ناوبری اصلی"
            >

                <a
                    href="{{ route('home') }}"
                    class="rounded-xl px-3.5 py-2.5 text-sm font-bold transition duration-200
                    {{ request()->routeIs('home')
                        ? 'bg-[var(--color-brand-50)] text-[var(--color-brand-900)]'
                        : 'text-[var(--color-text-secondary)] hover:bg-[var(--color-neutral-50)] hover:text-[var(--color-brand-900)]' }}"
                >
                    خانه
                </a>

                <a
                    href="{{ route('shop.index') }}"
                    class="rounded-xl px-3.5 py-2.5 text-sm font-bold transition duration-200
                    {{ request()->routeIs('shop.*')
                        ? 'bg-[var(--color-brand-50)] text-[var(--color-brand-900)]'
                        : 'text-[var(--color-text-secondary)] hover:bg-[var(--color-neutral-50)] hover:text-[var(--color-brand-900)]' }}"
                >
                    فروشگاه
                </a>

                <a
                    href="{{ route('blog.index') }}"
                    class="rounded-xl px-3.5 py-2.5 text-sm font-bold transition duration-200
                    {{ request()->routeIs('blog.*')
                        ? 'bg-[var(--color-brand-50)] text-[var(--color-brand-900)]'
                        : 'text-[var(--color-text-secondary)] hover:bg-[var(--color-neutral-50)] hover:text-[var(--color-brand-900)]' }}"
                >
                    مجله
                </a>

                <a
                    href="{{ route('contact.index') }}"
                    class="rounded-xl px-3.5 py-2.5 text-sm font-bold transition duration-200
                    {{ request()->routeIs('contact.*')
                        ? 'bg-[var(--color-brand-50)] text-[var(--color-brand-900)]'
                        : 'text-[var(--color-text-secondary)] hover:bg-[var(--color-neutral-50)] hover:text-[var(--color-brand-900)]' }}"
                >
                    تماس با ما
                </a>

            </nav>


            {{-- =========================================================
                Search
            ========================================================== --}}

            <form
                action="{{ route('shop.index') }}"
                method="GET"
                class="hidden min-w-0 max-w-md flex-1 xl:block"
            >

                <label
                    for="navbar-search"
                    class="sr-only"
                >
                    جستجوی محصولات
                </label>

                <div class="relative">

                    <input
                        id="navbar-search"
                        type="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="جستجوی محصول، برند یا دسته‌بندی..."
                        autocomplete="off"
                        class="w-full rounded-2xl border border-[var(--color-border)] bg-[var(--color-neutral-50)] py-3 pr-4 pl-12 text-sm text-[var(--color-text-primary)] outline-none transition duration-200 placeholder:text-[var(--color-text-soft)] focus:border-[var(--color-brand-900)] focus:bg-white focus:ring-4 focus:ring-[var(--color-brand-900)]/10"
                    >

                    <button
                        type="submit"
                        class="absolute left-1.5 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-xl bg-[var(--color-brand-900)] text-white transition duration-200 hover:bg-[var(--color-brand-950)] focus:outline-none focus:ring-4 focus:ring-[var(--color-brand-900)]/15"
                        aria-label="جستجو"
                    >
                        <svg
                            class="h-4.5 w-4.5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            aria-hidden="true"
                        >
                            <circle cx="11" cy="11" r="7"/>
                            <path d="m20 20-3.5-3.5"/>
                        </svg>
                    </button>

                </div>

            </form>


            {{-- =========================================================
                Actions
            ========================================================== --}}

            <div class="flex shrink-0 items-center gap-2">

                {{-- =====================================================
                    Wishlist
                ====================================================== --}}

                <a
                    href="{{ auth()->check() ? route('customer.wishlist.index') : route('login') }}"
                    class="hidden h-11 w-11 items-center justify-center rounded-xl border border-[var(--color-border)] bg-white text-[var(--color-text-secondary)] transition duration-200 hover:border-[var(--color-accent-200)] hover:bg-[var(--color-accent-50)] hover:text-[var(--color-accent-600)] sm:flex"
                    aria-label="علاقه‌مندی‌ها"
                    title="علاقه‌مندی‌ها"
                >
                    <svg
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                        aria-hidden="true"
                    >
                        <path d="M20.8 8.7c0 5.2-8.8 10.3-8.8 10.3S3.2 13.9 3.2 8.7A4.7 4.7 0 0 1 12 6.2a4.7 4.7 0 0 1 8.8 2.5Z"/>
                    </svg>
                </a>


                {{-- =====================================================
                    Cart
                ====================================================== --}}

                @auth

                    <a
                        href="{{ route('customer.cart.index') }}"
                        class="relative flex h-11 w-11 items-center justify-center rounded-xl border border-[var(--color-border)] bg-white text-[var(--color-text-secondary)] transition duration-200 hover:border-[var(--color-brand-300)] hover:bg-[var(--color-brand-50)] hover:text-[var(--color-brand-900)]"
                        aria-label="سبد خرید"
                        title="سبد خرید"
                    >

                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            aria-hidden="true"
                        >
                            <path d="M3 4h2l2.4 11.2a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 2-1.6L21 7H6"/>
                            <circle cx="10" cy="20" r="1"/>
                            <circle cx="18" cy="20" r="1"/>
                        </svg>

                        @php
                            $cartCount = app(\App\Services\CartService::class)
                                ->itemCount(auth()->user());
                        @endphp

                        @if($cartCount > 0)

                            <span
                                class="absolute -right-1.5 -top-1.5 flex min-h-5 min-w-5 items-center justify-center rounded-full bg-[var(--color-accent-600)] px-1 text-[9px] font-black leading-none text-white shadow-sm"
                            >
                                {{ $cartCount > 99 ? '99+' : $cartCount }}
                            </span>

                        @endif

                    </a>

                @endauth


                {{-- =====================================================
                    Account / Login
                ====================================================== --}}

                @auth

                    <a
                        href="{{ route('customer.dashboard') }}"
                        class="hidden h-11 items-center gap-2 rounded-xl border border-[var(--color-border)] bg-white px-4 text-sm font-bold text-[var(--color-text-secondary)] transition duration-200 hover:border-[var(--color-brand-300)] hover:bg-[var(--color-brand-50)] hover:text-[var(--color-brand-900)] sm:flex"
                    >

                        <span
                            class="flex h-7 w-7 items-center justify-center rounded-lg bg-[var(--color-brand-900)] text-[11px] font-black text-white"
                        >
                            {{ mb_substr(auth()->user()->name ?? 'U', 0, 1) }}
                        </span>

                        <span>
                            حساب من
                        </span>

                    </a>

                @else

                    <a
                        href="{{ route('login') }}"
                        class="hidden h-11 items-center justify-center rounded-xl bg-[var(--color-accent-600)] px-5 text-sm font-black text-white shadow-sm transition duration-200 hover:bg-[var(--color-accent-700)] hover:shadow-md sm:flex"
                    >
                        ورود
                    </a>

                @endauth


                {{-- =====================================================
                    Mobile Menu
                ====================================================== --}}

                <button
                    type="button"
                    @click="open = !open"
                    :aria-expanded="open.toString()"
                    aria-controls="mobile-navigation"
                    aria-label="منوی سایت"
                    class="flex h-11 w-11 items-center justify-center rounded-xl border border-[var(--color-border)] bg-white text-[var(--color-text-secondary)] transition duration-200 hover:border-[var(--color-brand-300)] hover:bg-[var(--color-brand-50)] hover:text-[var(--color-brand-900)] lg:hidden"
                >

                    <svg
                        x-show="!open"
                        x-cloak
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <path d="M4 7h16"/>
                        <path d="M4 12h16"/>
                        <path d="M4 17h16"/>
                    </svg>

                    <svg
                        x-show="open"
                        x-cloak
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <path d="M6 6l12 12"/>
                        <path d="M18 6 6 18"/>
                    </svg>

                </button>

            </div>

        </div>


        {{-- =========================================================
            Mobile Navigation
        ========================================================== --}}

        <div
            id="mobile-navigation"
            x-show="open"
            x-cloak
            x-collapse
            class="border-t border-[var(--color-border)] py-4 lg:hidden"
        >

            <div class="space-y-1">

                {{-- Mobile Search --}}
                <form
                    action="{{ route('shop.index') }}"
                    method="GET"
                    class="pb-3"
                >

                    <label
                        for="mobile-navbar-search"
                        class="sr-only"
                    >
                        جستجوی محصولات
                    </label>

                    <div class="relative">

                        <input
                            id="mobile-navbar-search"
                            type="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="جستجوی محصول یا برند..."
                            class="w-full rounded-2xl border border-[var(--color-border)] bg-[var(--color-neutral-50)] px-4 py-3 pr-4 pl-12 text-sm outline-none transition focus:border-[var(--color-brand-900)] focus:bg-white focus:ring-4 focus:ring-[var(--color-brand-900)]/10"
                        >

                        <button
                            type="submit"
                            class="absolute left-1.5 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-xl bg-[var(--color-brand-900)] text-white"
                            aria-label="جستجو"
                        >
                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                aria-hidden="true"
                            >
                                <circle cx="11" cy="11" r="7"/>
                                <path d="m20 20-3.5-3.5"/>
                            </svg>
                        </button>

                    </div>

                </form>


                {{-- Home --}}
                <a
                    href="{{ route('home') }}"
                    class="flex items-center justify-between rounded-xl px-4 py-3 text-sm font-bold transition
                    {{ request()->routeIs('home')
                        ? 'bg-[var(--color-brand-50)] text-[var(--color-brand-900)]'
                        : 'text-[var(--color-text-secondary)] hover:bg-[var(--color-neutral-50)]' }}"
                >
                    <span>خانه</span>

                    <svg
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                </a>


                {{-- Shop --}}
                <a
                    href="{{ route('shop.index') }}"
                    class="flex items-center justify-between rounded-xl px-4 py-3 text-sm font-bold transition
                    {{ request()->routeIs('shop.*')
                        ? 'bg-[var(--color-brand-50)] text-[var(--color-brand-900)]'
                        : 'text-[var(--color-text-secondary)] hover:bg-[var(--color-neutral-50)]' }}"
                >
                    <span>فروشگاه</span>

                    <svg
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                </a>


                {{-- Blog --}}
                <a
                    href="{{ route('blog.index') }}"
                    class="flex items-center justify-between rounded-xl px-4 py-3 text-sm font-bold transition
                    {{ request()->routeIs('blog.*')
                        ? 'bg-[var(--color-brand-50)] text-[var(--color-brand-900)]'
                        : 'text-[var(--color-text-secondary)] hover:bg-[var(--color-neutral-50)]' }}"
                >
                    <span>مجله</span>

                    <svg
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                </a>


                {{-- Contact --}}
                <a
                    href="{{ route('contact.index') }}"
                    class="flex items-center justify-between rounded-xl px-4 py-3 text-sm font-bold transition
                    {{ request()->routeIs('contact.*')
                        ? 'bg-[var(--color-brand-50)] text-[var(--color-brand-900)]'
                        : 'text-[var(--color-text-secondary)] hover:bg-[var(--color-neutral-50)]' }}"
                >
                    <span>تماس با ما</span>

                    <svg
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                </a>


                {{-- Mobile Account --}}
                <div class="mt-3 grid grid-cols-2 gap-2 border-t border-[var(--color-border)] pt-3">

                    @auth

                        <a
                            href="{{ route('customer.dashboard') }}"
                            class="flex items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-900)] px-4 py-3 text-sm font-black text-white"
                        >
                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                aria-hidden="true"
                            >
                                <circle cx="12" cy="8" r="3.5"/>
                                <path d="M5 20a7 7 0 0 1 14 0"/>
                            </svg>

                            حساب من
                        </a>

                        <a
                            href="{{ route('customer.cart.index') }}"
                            class="flex items-center justify-center gap-2 rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm font-black text-[var(--color-text-secondary)]"
                        >
                            سبد خرید
                        </a>

                    @else

                        <a
                            href="{{ route('login') }}"
                            class="flex items-center justify-center rounded-xl bg-[var(--color-accent-600)] px-4 py-3 text-sm font-black text-white"
                        >
                            ورود
                        </a>

                        <a
                            href="{{ route('register') }}"
                            class="flex items-center justify-center rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm font-black text-[var(--color-text-secondary)]"
                        >
                            ثبت‌نام
                        </a>

                    @endauth

                </div>

            </div>

        </div>

    </div>
</nav>
