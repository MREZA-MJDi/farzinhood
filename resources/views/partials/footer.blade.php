<footer class="mt-20 border-t border-[var(--color-border)] bg-white">

    {{-- =========================================================
        Main Footer
    ========================================================== --}}

    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-16">

        <div class="grid gap-10 lg:grid-cols-12 lg:gap-12">

            {{-- =====================================================
                Brand
            ====================================================== --}}

            <div class="lg:col-span-4">

                <a
                    href="{{ route('home') }}"
                    class="inline-flex items-center"
                    aria-label="فرزین"
                >
                    <img
                        src="{{ asset('images/brand/logo.png') }}"
                        alt="فرزین"
                        class="h-11 w-auto object-contain"
                        loading="lazy"
                    >
                </a>

                <p class="mt-5 max-w-md text-sm leading-8 text-[var(--color-text-secondary)]">
                    تجربه‌ای مطمئن، ساده و حرفه‌ای برای انتخاب و خرید محصولات مورد نیاز شما.
                    در فرزین تلاش می‌کنیم خرید آنلاین فقط سریع نباشد؛ بلکه آگاهانه و قابل اعتماد باشد.
                </p>

                {{-- Contact mini row --}}
                <div class="mt-6 space-y-3">

                    <a
                        href="tel:+982112345678"
                        class="group inline-flex items-center gap-3 text-sm font-bold text-[var(--color-text-secondary)] transition hover:text-[var(--color-accent-600)]"
                    >
                        <span
                            class="flex h-9 w-9 items-center justify-center rounded-xl bg-[var(--color-neutral-100)] text-[var(--color-brand-900)] transition group-hover:bg-[var(--color-accent-50)] group-hover:text-[var(--color-accent-600)]"
                        >
                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                aria-hidden="true"
                            >
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.79 19.79 0 0 1 3.1 5.18 2 2 0 0 1 5.11 3h3a2 2 0 0 1 2 1.72c.12.9.33 1.78.62 2.63a2 2 0 0 1-.45 2.11L9 10.73a16 16 0 0 0 4.27 4.27l1.27-1.27a2 2 0 0 1 2.11-.45c.85.29 1.73.5 2.63.62A2 2 0 0 1 22 16.92Z"/>
                            </svg>
                        </span>

                        <span dir="ltr">
                            ۰۲۱-۱۲۳۴۵۶۷۸
                        </span>
                    </a>

                    <a
                        href="mailto:info@farzin.ir"
                        class="group inline-flex items-center gap-3 text-sm font-bold text-[var(--color-text-secondary)] transition hover:text-[var(--color-accent-600)]"
                    >
                        <span
                            class="flex h-9 w-9 items-center justify-center rounded-xl bg-[var(--color-neutral-100)] text-[var(--color-brand-900)] transition group-hover:bg-[var(--color-accent-50)] group-hover:text-[var(--color-accent-600)]"
                        >
                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                aria-hidden="true"
                            >
                                <rect x="3" y="4" width="18" height="16" rx="2"/>
                                <path d="m3 7 9 6 9-6"/>
                            </svg>
                        </span>

                        <span dir="ltr">
                            info@farzin.ir
                        </span>
                    </a>

                </div>

            </div>


            {{-- =====================================================
                Quick Links
            ====================================================== --}}

            <div class="lg:col-span-2">

                <h3 class="text-sm font-black text-[var(--color-text-primary)]">
                    دسترسی سریع
                </h3>

                <nav class="mt-5 space-y-3.5">

                    <a
                        href="{{ route('home') }}"
                        class="group flex items-center gap-2 text-sm text-[var(--color-text-secondary)] transition hover:text-[var(--color-accent-600)]"
                    >
                        <span class="h-1.5 w-1.5 rounded-full bg-transparent transition group-hover:bg-[var(--color-accent-600)]"></span>
                        خانه
                    </a>

                    <a
                        href="{{ route('shop.index') }}"
                        class="group flex items-center gap-2 text-sm text-[var(--color-text-secondary)] transition hover:text-[var(--color-accent-600)]"
                    >
                        <span class="h-1.5 w-1.5 rounded-full bg-transparent transition group-hover:bg-[var(--color-accent-600)]"></span>
                        فروشگاه
                    </a>

                    <a
                        href="{{ route('blog.index') }}"
                        class="group flex items-center gap-2 text-sm text-[var(--color-text-secondary)] transition hover:text-[var(--color-accent-600)]"
                    >
                        <span class="h-1.5 w-1.5 rounded-full bg-transparent transition group-hover:bg-[var(--color-accent-600)]"></span>
                        مجله فرزین
                    </a>

                    <a
                        href="{{ route('contact.index') }}"
                        class="group flex items-center gap-2 text-sm text-[var(--color-text-secondary)] transition hover:text-[var(--color-accent-600)]"
                    >
                        <span class="h-1.5 w-1.5 rounded-full bg-transparent transition group-hover:bg-[var(--color-accent-600)]"></span>
                        تماس با ما
                    </a>

                </nav>

            </div>


            {{-- =====================================================
                Customer
            ====================================================== --}}

            <div class="lg:col-span-2">

                <h3 class="text-sm font-black text-[var(--color-text-primary)]">
                    حساب کاربری
                </h3>

                <nav class="mt-5 space-y-3.5">

                    @auth

                        <a
                            href="{{ route('customer.dashboard') }}"
                            class="group flex items-center gap-2 text-sm text-[var(--color-text-secondary)] transition hover:text-[var(--color-accent-600)]"
                        >
                            <span class="h-1.5 w-1.5 rounded-full bg-transparent transition group-hover:bg-[var(--color-accent-600)]"></span>
                            داشبورد من
                        </a>

                        <a
                            href="{{ route('customer.orders.index') }}"
                            class="group flex items-center gap-2 text-sm text-[var(--color-text-secondary)] transition hover:text-[var(--color-accent-600)]"
                        >
                            <span class="h-1.5 w-1.5 rounded-full bg-transparent transition group-hover:bg-[var(--color-accent-600)]"></span>
                            سفارش‌های من
                        </a>

                        <a
                            href="{{ route('customer.wishlist.index') }}"
                            class="group flex items-center gap-2 text-sm text-[var(--color-text-secondary)] transition hover:text-[var(--color-accent-600)]"
                        >
                            <span class="h-1.5 w-1.5 rounded-full bg-transparent transition group-hover:bg-[var(--color-accent-600)]"></span>
                            علاقه‌مندی‌ها
                        </a>

                    @else

                        <a
                            href="{{ route('login') }}"
                            class="group flex items-center gap-2 text-sm text-[var(--color-text-secondary)] transition hover:text-[var(--color-accent-600)]"
                        >
                            <span class="h-1.5 w-1.5 rounded-full bg-transparent transition group-hover:bg-[var(--color-accent-600)]"></span>
                            ورود
                        </a>

                        <a
                            href="{{ route('register') }}"
                            class="group flex items-center gap-2 text-sm text-[var(--color-text-secondary)] transition hover:text-[var(--color-accent-600)]"
                        >
                            <span class="h-1.5 w-1.5 rounded-full bg-transparent transition group-hover:bg-[var(--color-accent-600)]"></span>
                            ثبت‌نام
                        </a>

                    @endauth

                </nav>

            </div>


            {{-- =====================================================
                Newsletter
            ====================================================== --}}

            <div class="lg:col-span-4">

                <div class="rounded-[1.75rem] border border-[var(--color-border)] bg-[var(--color-neutral-50)] p-5 sm:p-6">

                    <div class="flex items-start justify-between gap-4">

                        <div>
                            <span class="text-[11px] font-black uppercase tracking-[0.14em] text-[var(--color-accent-600)]">
                                Newsletter
                            </span>

                            <h3 class="mt-2 text-lg font-black text-[var(--color-text-primary)]">
                                از اخبار فرزین باخبر شوید
                            </h3>
                        </div>

                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[var(--color-brand-900)] text-white"
                        >
                            <svg
                                class="h-5 w-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                aria-hidden="true"
                            >
                                <path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z"/>
                                <path d="m22 6-10 7L2 6"/>
                            </svg>
                        </div>

                    </div>

                    <p class="mt-3 text-sm leading-7 text-[var(--color-text-secondary)]">
                        برای دریافت جدیدترین محصولات، مطالب کاربردی و پیشنهادهای ویژه عضو خبرنامه شوید.
                    </p>


                    <form
                        action="{{ route('newsletter.store') }}"
                        method="POST"
                        class="mt-5"
                    >
                        @csrf

                        <label
                            for="footer-newsletter-email"
                            class="sr-only"
                        >
                            ایمیل
                        </label>

                        <div class="flex flex-col gap-2 sm:flex-row">

                            <input
                                id="footer-newsletter-email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="ایمیل شما"
                                autocomplete="email"
                                required
                                class="min-w-0 flex-1 rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none transition placeholder:text-[var(--color-text-soft)] focus:border-[var(--color-brand-900)] focus:ring-4 focus:ring-[var(--color-brand-900)]/10"
                            >

                            <button
                                type="submit"
                                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-[var(--color-accent-600)] px-5 py-3 text-sm font-black text-white shadow-sm transition hover:bg-[var(--color-accent-700)] focus:outline-none focus:ring-4 focus:ring-[var(--color-accent-600)]/15"
                            >
                                عضویت

                                <svg
                                    class="h-4 w-4"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    aria-hidden="true"
                                >
                                    <path d="M22 2 11 13"/>
                                    <path d="m22 2-7 20-4-9-9-4Z"/>
                                </svg>
                            </button>

                        </div>

                        @error('email')
                        <p class="mt-2 text-xs font-semibold text-red-600">
                            {{ $message }}
                        </p>
                        @enderror

                    </form>

                </div>

            </div>

        </div>


        {{-- =========================================================
            Lower Footer
        ========================================================== --}}

        <div class="mt-12 border-t border-[var(--color-border)] pt-6">

            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                {{-- Copyright --}}
                <div class="text-center lg:text-right">

                    <p class="text-xs font-medium text-[var(--color-text-muted)]">
                        © {{ now()->year }} فرزین. تمامی حقوق محفوظ است.
                    </p>

                </div>


                {{-- Social --}}
                <div class="flex items-center justify-center gap-2">

                    <a
                        href="#"
                        aria-label="Instagram"
                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-[var(--color-border)] bg-white text-[var(--color-text-muted)] transition hover:border-[var(--color-accent-200)] hover:bg-[var(--color-accent-50)] hover:text-[var(--color-accent-600)]"
                    >
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            aria-hidden="true"
                        >
                            <rect x="3" y="3" width="18" height="18" rx="5"/>
                            <circle cx="12" cy="12" r="4"/>
                            <circle cx="17.5" cy="6.5" r=".8" fill="currentColor" stroke="none"/>
                        </svg>
                    </a>

                    <a
                        href="#"
                        aria-label="Telegram"
                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-[var(--color-border)] bg-white text-[var(--color-text-muted)] transition hover:border-[var(--color-brand-200)] hover:bg-[var(--color-brand-50)] hover:text-[var(--color-brand-900)]"
                    >
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            aria-hidden="true"
                        >
                            <path d="M21 3 3.8 9.7c-.8.3-.8 1.4 0 1.7l4.8 1.8 1.8 5.2c.2.7 1.1.9 1.6.3l2.6-3.2 4.4 3.2c.7.5 1.6.1 1.8-.7L23 4.1c.2-.8-1.1-1.5-2-1.1Z"/>
                            <path d="m8.7 13.2 8.8-6.5-6.1 7.7"/>
                        </svg>
                    </a>

                </div>


                {{-- Small links --}}
                <div class="flex items-center justify-center gap-4 text-xs text-[var(--color-text-muted)] lg:justify-end">

                    <a
                        href="{{ route('contact.index') }}"
                        class="transition hover:text-[var(--color-accent-600)]"
                    >
                        پشتیبانی
                    </a>

                    <span class="h-1 w-1 rounded-full bg-[var(--color-neutral-300)]"></span>

                    <a
                        href="{{ route('blog.index') }}"
                        class="transition hover:text-[var(--color-accent-600)]"
                    >
                        مجله
                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        Footer Accent Line
    ========================================================== --}}

    <div class="h-1 bg-gradient-to-l from-[var(--color-brand-950)] via-[var(--color-brand-900)] to-[var(--color-accent-600)]"></div>

</footer>
