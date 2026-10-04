<nav
    class="store-nav sticky top-0 z-50 border-b border-[var(--color-border)] bg-[color-mix(in_srgb,var(--color-surface)_92%,transparent)] shadow-[0_1px_18px_rgb(52_38_28_/0.05)] backdrop-blur-xl"
    aria-label="ناوبری اصلی"
>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex min-h-[72px] items-center gap-3 lg:gap-5">

            <a
                href="{{ route('home') }}"
                class="store-nav__brand shrink-0"
                aria-label="فرزین - صفحه اصلی"
            >
                <img
                    src="{{ asset('images/brand/logo.png') }}"
                    alt="فرزین"
                    class="h-10 w-auto object-contain sm:h-11"
                >
            </a>

            <div class="hidden items-center gap-1 lg:flex" role="navigation" aria-label="لینک‌های اصلی">
                <a href="{{ route('home') }}" class="store-nav__link {{ request()->routeIs('home') ? 'is-active' : '' }}">خانه</a>
                <a href="{{ route('shop.index') }}" class="store-nav__link {{ request()->routeIs('shop.*', 'products.*', 'categories.*') ? 'is-active' : '' }}">فروشگاه</a>
                <a href="{{ route('blog.index') }}" class="store-nav__link {{ request()->routeIs('blog.*') ? 'is-active' : '' }}">مجله</a>
                <a href="{{ route('contact.index') }}" class="store-nav__link {{ request()->routeIs('contact.*') ? 'is-active' : '' }}">تماس با ما</a>
            </div>

            <form
                action="{{ route('shop.index') }}"
                method="GET"
                class="store-nav__search hidden min-w-0 max-w-xl flex-1 xl:flex"
                role="search"
            >
                <label for="navbar-search" class="sr-only">جستجوی محصولات</label>
                <div class="relative w-full">
                    <input
                        id="navbar-search"
                        type="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="جستجوی محصول، برند یا دسته‌بندی..."
                        autocomplete="off"
                        class="w-full rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface-muted)] py-3 pr-4 pl-12 text-sm text-[var(--color-text-primary)] outline-none transition placeholder:text-[var(--color-text-soft)] focus:border-[var(--color-brand-400)] focus:bg-[var(--color-surface)] focus:ring-4 focus:ring-[var(--color-brand-900)]/10"
                    >
                    <button
                        type="submit"
                        class="absolute left-1.5 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-xl bg-[var(--color-brand-900)] text-white transition hover:bg-[var(--color-brand-950)]"
                        aria-label="جستجو"
                    >
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <circle cx="11" cy="11" r="7"/>
                            <path d="m20 20-3.5-3.5"/>
                        </svg>
                    </button>
                </div>
            </form>

            <div class="mr-auto flex items-center gap-2">
                @if(auth()->check() && auth()->user()->isCustomer())
                    <details class="store-nav__account hidden sm:block">
                        <summary class="store-nav__account-trigger">
                            <span class="store-nav__avatar">{{ mb_substr(auth()->user()->name ?? 'م', 0, 1) }}</span>
                            <span class="hidden xl:inline">حساب من</span>
                            <span aria-hidden="true">⌄</span>
                        </summary>
                        <div class="store-nav__account-menu">
                            <div class="store-nav__account-head">
                                <span>{{ auth()->user()->name }}</span>
                                <small>{{ auth()->user()->email }}</small>
                            </div>
                            <a href="{{ route('customer.dashboard') }}" class="{{ request()->routeIs('customer.dashboard') ? 'is-active' : '' }}">داشبورد</a>
                            <a href="{{ route('customer.orders.index') }}" class="{{ request()->routeIs('customer.orders.*') ? 'is-active' : '' }}">سفارش‌های من</a>
                            <a href="{{ route('customer.wishlist.index') }}" class="{{ request()->routeIs('customer.wishlist.*') ? 'is-active' : '' }}">علاقه‌مندی‌ها</a>
                            <a href="{{ route('customer.addresses.index') }}" class="{{ request()->routeIs('customer.addresses.*') ? 'is-active' : '' }}">آدرس‌ها</a>
                            <a href="{{ route('customer.settings.index') }}" class="{{ request()->routeIs('customer.settings.*') ? 'is-active' : '' }}">تنظیمات</a>
                            <div class="store-nav__account-divider"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit">خروج از حساب</button>
                            </form>
                        </div>
                    </details>
                @else
                    <a href="{{ route('login') }}" class="store-nav__auth-button hidden sm:inline-flex">ورود</a>
                @endif

                @if(auth()->check() && auth()->user()->isCustomer())
                    <a
                        href="{{ route('customer.wishlist.index') }}"
                        class="store-nav__icon hidden sm:inline-flex {{ request()->routeIs('customer.wishlist.*') ? 'is-active' : '' }}"
                        aria-label="علاقه‌مندی‌ها"
                        title="علاقه‌مندی‌ها"
                    >
                        ♡
                    </a>
                    <a
                        href="{{ route('customer.cart.index') }}"
                        class="store-nav__cart"
                        aria-label="سبد خرید"
                        title="سبد خرید"
                    >
                        <span aria-hidden="true">🛒</span>
                        @php($cartCount = app(\App\Services\CartService::class)->itemCount(auth()->user()))
                        @if($cartCount > 0)
                            <b>{{ $cartCount > 99 ? '99+' : $cartCount }}</b>
                        @endif
                    </a>
                @endif

                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="store-nav__admin hidden sm:inline-flex">مدیریت</a>
                    @endif
                @endauth

                <details class="store-nav__mobile">
                    <summary aria-label="باز کردن منوی موبایل">
                        <span class="store-nav__mobile-icon" aria-hidden="true">☰</span>
                    </summary>
                    <div class="store-nav__mobile-panel">
                        <form action="{{ route('shop.index') }}" method="GET" role="search">
                            <label for="mobile-navbar-search" class="sr-only">جستجوی محصولات</label>
                            <input
                                id="mobile-navbar-search"
                                type="search"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="جستجوی محصول یا برند..."
                                autocomplete="off"
                            >
                            <button type="submit" aria-label="جستجو">⌕</button>
                        </form>

                        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'is-active' : '' }}">خانه</a>
                        <a href="{{ route('shop.index') }}" class="{{ request()->routeIs('shop.*', 'products.*', 'categories.*') ? 'is-active' : '' }}">فروشگاه</a>
                        <a href="{{ route('blog.index') }}" class="{{ request()->routeIs('blog.*') ? 'is-active' : '' }}">مجله</a>
                        <a href="{{ route('contact.index') }}" class="{{ request()->routeIs('contact.*') ? 'is-active' : '' }}">تماس با ما</a>

                        @if(auth()->check() && auth()->user()->isCustomer())
                            <div class="store-nav__mobile-section">
                                <span>حساب کاربری</span>
                                <a href="{{ route('customer.dashboard') }}">داشبورد</a>
                                <a href="{{ route('customer.orders.index') }}">سفارش‌ها</a>
                                <a href="{{ route('customer.wishlist.index') }}">علاقه‌مندی‌ها</a>
                                <a href="{{ route('customer.addresses.index') }}">آدرس‌ها</a>
                                <a href="{{ route('customer.settings.index') }}">تنظیمات</a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit">خروج از حساب</button>
                                </form>
                            </div>
                        @elseif(!auth()->check())
                            <div class="store-nav__mobile-actions">
                                <a href="{{ route('login') }}">ورود</a>
                                <a href="{{ route('register') }}">ثبت‌نام</a>
                            </div>
                        @endif
                    </div>
                </details>
            </div>
        </div>
    </div>
</nav>
