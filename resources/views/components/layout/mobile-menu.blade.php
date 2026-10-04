<div
    id="mobile-menu"
    class="mobile-menu"
    data-mobile-menu
    hidden
>
    <div
        class="mobile-menu__inner"
        role="dialog"
        aria-modal="true"
        aria-label="منوی سایت"
    >

        {{-- Header --}}
        <div class="mobile-menu__header">

            <span class="mobile-menu__title">
                منو
            </span>

            <button
                type="button"
                class="icon-btn icon-btn--border"
                aria-label="بستن منو"
                data-menu-close
            >
                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.7"
                    aria-hidden="true"
                >
                    <path
                        d="M6 6L18 18"
                        stroke-linecap="round"
                    />

                    <path
                        d="M18 6L6 18"
                        stroke-linecap="round"
                    />
                </svg>
            </button>

        </div>


        {{-- Navigation --}}
        <nav
            class="mobile-menu__nav"
            aria-label="منوی موبایل"
        >

            <a
                href="{{ route('home') }}"
                class="mobile-menu__link {{ request()->routeIs('home') ? 'is-active' : '' }}"
            >
                <span>خانه</span>
                <span aria-hidden="true">←</span>
            </a>

            <a
                href="{{ route('shop') }}"
                class="mobile-menu__link {{ request()->routeIs('shop') ? 'is-active' : '' }}"
            >
                <span>فروشگاه</span>
                <span aria-hidden="true">←</span>
            </a>

            <a
                href="{{ route('category.hood') }}"
                class="mobile-menu__link {{ request()->routeIs('category.hood') ? 'is-active' : '' }}"
            >
                <span>هود</span>
                <span aria-hidden="true">←</span>
            </a>

            <a
                href="{{ route('category.sink') }}"
                class="mobile-menu__link {{ request()->routeIs('category.sink') ? 'is-active' : '' }}"
            >
                <span>سینک</span>
                <span aria-hidden="true">←</span>
            </a>

            <a
                href="{{ route('blog.index') }}"
                class="mobile-menu__link {{ request()->routeIs('blog.*') ? 'is-active' : '' }}"
            >
                <span>مجله</span>
                <span aria-hidden="true">←</span>
            </a>

            <a
                href="{{ route('about') }}"
                class="mobile-menu__link {{ request()->routeIs('about') ? 'is-active' : '' }}"
            >
                <span>درباره ما</span>
                <span aria-hidden="true">←</span>
            </a>

            <a
                href="{{ route('contact') }}"
                class="mobile-menu__link {{ request()->routeIs('contact') ? 'is-active' : '' }}"
            >
                <span>تماس با ما</span>
                <span aria-hidden="true">←</span>
            </a>

            <a
                href="{{ route('wishlist') }}"
                class="mobile-menu__link {{ request()->routeIs('wishlist') ? 'is-active' : '' }}"
            >
                <span>علاقه‌مندی‌ها</span>
                <span aria-hidden="true">←</span>
            </a>

            <a
                href="{{ route('cart') }}"
                class="mobile-menu__link {{ request()->routeIs('cart') ? 'is-active' : '' }}"
            >
                <span>سبد خرید</span>
                <span aria-hidden="true">←</span>
            </a>

        </nav>


        {{-- Footer --}}
        <div class="mobile-menu__footer">

            <a
                href="{{ route('contact') }}"
                class="mobile-menu__link"
            >
                <span>
                    پشتیبانی و تماس
                </span>

                <span aria-hidden="true">
                    ←
                </span>
            </a>

        </div>

    </div>
</div>
