<header class="site-header">

    <div class="site-header__main">

        <div class="container site-header__inner">

            {{-- Logo --}}
            <a
                href="{{ route('home') }}"
                class="site-header__logo"
                aria-label="فرزین"
            >
                <span class="site-header__logo-text">
                    FARZIN
                </span>

                <span
                    class="site-header__logo-mark"
                    aria-hidden="true"
                >
                    <svg
                        viewBox="0 0 48 48"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M31.8 5H43L33.8 15.2H24.7L31.8 5Z"
                            fill="currentColor"
                        />
                        <path
                            d="M24.8 18.8H37.8L28.4 29H15.5L24.8 18.8Z"
                            fill="currentColor"
                        />
                        <path
                            d="M15.5 32.6H28.4L19.2 42.9H6.2L15.5 32.6Z"
                            fill="currentColor"
                        />
                    </svg>
                </span>
            </a>


            {{-- Desktop Navigation --}}
            <nav
                class="site-header__nav"
                aria-label="منوی اصلی"
            >

                <a
                    href="{{ route('home') }}"
                    class="site-header__nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}"
                >
                    خانه
                </a>

                <a
                    href="{{ route('shop') }}"
                    class="site-header__nav-link {{ request()->routeIs('shop') ? 'is-active' : '' }}"
                >
                    فروشگاه
                </a>

                <a
                    href="{{ route('category.hood') }}"
                    class="site-header__nav-link {{ request()->routeIs('category.hood') ? 'is-active' : '' }}"
                >
                    هود
                </a>

                <a
                    href="{{ route('category.sink') }}"
                    class="site-header__nav-link {{ request()->routeIs('category.sink') ? 'is-active' : '' }}"
                >
                    سینک
                </a>

                <a
                    href="{{ route('blog.index') }}"
                    class="site-header__nav-link {{ request()->routeIs('blog.*') ? 'is-active' : '' }}"
                >
                    مجله
                </a>

                <a
                    href="{{ route('about') }}"
                    class="site-header__nav-link {{ request()->routeIs('about') ? 'is-active' : '' }}"
                >
                    درباره ما
                </a>

                <a
                    href="{{ route('contact') }}"
                    class="site-header__nav-link {{ request()->routeIs('contact') ? 'is-active' : '' }}"
                >
                    تماس با ما
                </a>

            </nav>


            {{-- Actions --}}
            <div class="site-header__actions">

                {{-- Search --}}
                <button
                    type="button"
                    class="icon-btn site-header__action"
                    aria-label="جستجو"
                    aria-expanded="false"
                    data-search-toggle
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <circle
                            cx="11"
                            cy="11"
                            r="6.5"
                        />
                        <path d="M16 16L21 21" />
                    </svg>
                </button>


                {{-- Wishlist --}}
                <a
                    href="{{ route('wishlist') }}"
                    class="icon-btn site-header__action"
                    aria-label="علاقه‌مندی‌ها"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <path
                            d="M20.8 8.8C20.8 13.7 12 19 12 19S3.2 13.7 3.2 8.8C3.2 6.2 5.2 4 7.8 4C9.5 4 11 4.9 12 6.2C13 4.9 14.5 4 16.2 4C18.8 4 20.8 6.2 20.8 8.8Z"
                        />
                    </svg>
                </a>


                {{-- Account --}}
                <a
                    href="#"
                    class="icon-btn site-header__action"
                    aria-label="حساب کاربری"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <circle
                            cx="12"
                            cy="8"
                            r="3.2"
                        />

                        <path
                            d="M5.2 20C5.9 16.6 8.4 14.8 12 14.8C15.6 14.8 18.1 16.6 18.8 20"
                        />
                    </svg>
                </a>


                {{-- Cart --}}
                <a
                    href="{{ route('cart') }}"
                    class="site-header__cart"
                    aria-label="سبد خرید"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <path d="M4 5H6L8.2 15.5H18L20 8H7" />

                        <circle
                            cx="9.5"
                            cy="19"
                            r="1.2"
                        />

                        <circle
                            cx="17"
                            cy="19"
                            r="1.2"
                        />
                    </svg>

                    <span class="site-header__cart-count">
                        {{ $cartCount ?? 0 }}
                    </span>
                </a>


                {{-- Mobile menu --}}
                <button
                    type="button"
                    class="icon-btn site-header__menu-toggle"
                    aria-label="باز کردن منو"
                    aria-expanded="false"
                    aria-controls="mobile-menu"
                    data-menu-toggle
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <path d="M4 7H20" />
                        <path d="M4 12H20" />
                        <path d="M4 17H20" />
                    </svg>
                </button>

            </div>

        </div>

    </div>


    {{-- Search Panel --}}
    <div
        class="site-header__search"
        data-search-panel
        hidden
    >
        <div class="container">

            <form
                action="{{ route('search') }}"
                method="GET"
                class="site-header__search-form"
            >

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    aria-hidden="true"
                >
                    <circle
                        cx="11"
                        cy="11"
                        r="6.5"
                    />

                    <path d="M16 16L21 21" />
                </svg>

                <label
                    for="header-search"
                    class="sr-only"
                >
                    جستجوی محصولات
                </label>

                <input
                    id="header-search"
                    type="search"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="جستجوی هود، سینک و محصولات..."
                    autocomplete="off"
                >

                <button
                    type="button"
                    class="site-header__search-close"
                    aria-label="بستن جستجو"
                    data-search-close
                >
                    ×
                </button>

            </form>

        </div>
    </div>


    <x-layout.mobile-menu />

</header>
