<footer class="site-footer">

    <div class="container">

        <div class="site-footer__main">

            {{-- Brand --}}
            <div class="site-footer__brand">

                <a
                    href="{{ route('home') }}"
                    class="site-footer__logo"
                    aria-label="فرزین"
                >
                    FARZIN
                </a>

                <p class="site-footer__description">
                    انتخابی مطمئن برای هود و سینک مدرن،
                    با تمرکز بر کیفیت، طراحی و تجربه خرید حرفه‌ای.
                </p>

            </div>


            {{-- Shopping --}}
            <div>

                <h3 class="site-footer__title">
                    خرید
                </h3>

                <nav
                    class="site-footer__links"
                    aria-label="لینک‌های خرید"
                >

                    <a
                        href="{{ route('shop') }}"
                        class="site-footer__link"
                    >
                        فروشگاه
                    </a>

                    <a
                        href="{{ route('category.hood') }}"
                        class="site-footer__link"
                    >
                        هود
                    </a>

                    <a
                        href="{{ route('category.sink') }}"
                        class="site-footer__link"
                    >
                        سینک
                    </a>

                    <a
                        href="{{ route('wishlist') }}"
                        class="site-footer__link"
                    >
                        علاقه‌مندی‌ها
                    </a>

                    <a
                        href="{{ route('cart') }}"
                        class="site-footer__link"
                    >
                        سبد خرید
                    </a>

                </nav>

            </div>


            {{-- Information --}}
            <div>

                <h3 class="site-footer__title">
                    اطلاعات
                </h3>

                <nav
                    class="site-footer__links"
                    aria-label="اطلاعات سایت"
                >

                    <a
                        href="{{ route('about') }}"
                        class="site-footer__link"
                    >
                        درباره ما
                    </a>

                    <a
                        href="{{ route('blog.index') }}"
                        class="site-footer__link"
                    >
                        مجله
                    </a>

                    <a
                        href="{{ route('contact') }}"
                        class="site-footer__link"
                    >
                        تماس با ما
                    </a>

                </nav>

            </div>


            {{-- Contact --}}
            <div>

                <h3 class="site-footer__title">
                    تماس
                </h3>

                <div class="site-footer__contact">

                    <a
                        href="tel:+982112345678"
                        class="site-footer__contact-item"
                    >
                        ۰۲۱-۱۲۳۴۵۶۷۸
                    </a>

                    <a
                        href="mailto:info@example.com"
                        class="site-footer__contact-item"
                    >
                        info@example.com
                    </a>

                    <span class="site-footer__contact-item">
                        شنبه تا پنجشنبه
                    </span>

                </div>

            </div>

        </div>


        <div class="site-footer__bottom">

            <span>
                © {{ date('Y') }} Farzin.
                تمامی حقوق محفوظ است.
            </span>

            <span>
                طراحی شده برای یک تجربه خرید بهتر.
            </span>

        </div>

    </div>

</footer>
