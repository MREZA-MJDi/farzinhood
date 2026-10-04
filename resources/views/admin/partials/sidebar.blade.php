<aside
    class="fixed inset-y-0 right-0 z-50 flex w-[280px] -translate-x-full flex-col border-l border-[var(--color-border)] bg-white transition-transform duration-300 lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>
    {{-- Brand --}}
    <div
        class="flex h-[72px] shrink-0 items-center border-b border-[var(--color-border)] px-5"
    >
        <a
            href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-3"
        >
            <span
                class="flex h-11 w-11 items-center justify-center rounded-xl bg-[var(--color-brand-600)] text-lg font-black text-white shadow-sm"
            >
                ف
            </span>

            <span class="leading-tight">
                <span
                    class="block text-sm font-extrabold text-[var(--color-text-primary)]"
                >
                    فرزین
                </span>

                <span
                    class="block text-xs text-[var(--color-text-muted)]"
                >
                    پنل مدیریت
                </span>
            </span>
        </a>

        <button
            type="button"
            @click="sidebarOpen = false"
            class="mr-auto flex h-9 w-9 items-center justify-center rounded-lg text-[var(--color-text-muted)] hover:bg-[var(--color-neutral-50)] lg:hidden"
            aria-label="بستن منو"
        >
            <svg
                class="h-5 w-5"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
            >
                <path d="M6 6l12 12M18 6 6 18" />
            </svg>
        </button>
    </div>

    {{-- Navigation --}}
    <div class="flex-1 overflow-y-auto px-4 py-5">

        {{-- Overview --}}
        <div class="mb-6">
            <div
                class="mb-2 px-3 text-[10px] font-bold uppercase tracking-[0.16em] text-[var(--color-text-muted)]"
            >
                Overview
            </div>

            <nav class="space-y-1">

                <a
                    href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition
                    {{ request()->routeIs('admin.dashboard')
                        ? 'bg-[var(--color-brand-50)] text-[var(--color-brand-700)]'
                        : 'text-[var(--color-text-secondary)] hover:bg-[var(--color-neutral-50)] hover:text-[var(--color-text-primary)]' }}"
                >
                    <svg
                        class="h-5 w-5 shrink-0"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <rect x="4" y="4" width="6" height="6" rx="1" />
                        <rect x="14" y="4" width="6" height="6" rx="1" />
                        <rect x="4" y="14" width="6" height="6" rx="1" />
                        <rect x="14" y="14" width="6" height="6" rx="1" />
                    </svg>

                    داشبورد
                </a>

            </nav>
        </div>

        {{-- Commerce --}}
        <div class="mb-6">
            <div
                class="mb-2 px-3 text-[10px] font-bold uppercase tracking-[0.16em] text-[var(--color-text-muted)]"
            >
                Commerce
            </div>

            <nav class="space-y-1">

                <a
                    href="{{ route('admin.products.index') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition
                    {{ request()->routeIs('admin.products.*')
                        ? 'bg-[var(--color-brand-50)] text-[var(--color-brand-700)]'
                        : 'text-[var(--color-text-secondary)] hover:bg-[var(--color-neutral-50)] hover:text-[var(--color-text-primary)]' }}"
                >
                    <svg
                        class="h-5 w-5 shrink-0"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path d="M4 7h16v13H4z" />
                        <path d="M8 7V5h8v2" />
                    </svg>

                    محصولات
                </a>

                <a
                    href="{{ route('admin.categories.index') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition
                    {{ request()->routeIs('admin.categories.*')
                        ? 'bg-[var(--color-brand-50)] text-[var(--color-brand-700)]'
                        : 'text-[var(--color-text-secondary)] hover:bg-[var(--color-neutral-50)] hover:text-[var(--color-text-primary)]' }}"
                >
                    <svg
                        class="h-5 w-5 shrink-0"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path d="M4 5h16v14H4z" />
                        <path d="M4 10h16M10 5v14" />
                    </svg>

                    دسته‌بندی‌ها
                </a>

                <a
                    href="{{ route('admin.inventory.index') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition
                    {{ request()->routeIs('admin.inventory.*')
                        ? 'bg-[var(--color-brand-50)] text-[var(--color-brand-700)]'
                        : 'text-[var(--color-text-secondary)] hover:bg-[var(--color-neutral-50)] hover:text-[var(--color-text-primary)]' }}"
                >
                    <svg
                        class="h-5 w-5 shrink-0"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path d="M4 7h16v13H4z" />
                        <path d="M8 7V4h8v3" />
                        <path d="M8 12h8M8 16h5" />
                    </svg>

                    انبار
                </a>

                <a
                    href="{{ route('admin.orders.index') }}"
                    class="flex items-center justify-between gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition
                    {{ request()->routeIs('admin.orders.*')
                        ? 'bg-[var(--color-brand-50)] text-[var(--color-brand-700)]'
                        : 'text-[var(--color-text-secondary)] hover:bg-[var(--color-neutral-50)] hover:text-[var(--color-text-primary)]' }}"
                >
                    <span class="flex items-center gap-3">
                        <svg
                            class="h-5 w-5 shrink-0"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="M6 7h12l1 13H5L6 7z" />
                            <path d="M9 7V5h6v2" />
                        </svg>

                        سفارش‌ها
                    </span>

                    @if(($pendingOrders ?? 0) > 0)
                        <span
                            class="inline-flex min-w-6 items-center justify-center rounded-full bg-amber-100 px-1.5 py-1 text-[10px] font-bold text-amber-700"
                        >
                            {{ $pendingOrders }}
                        </span>
                    @endif
                </a>

                <a
                    href="{{ route('admin.customers.index') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition
                    {{ request()->routeIs('admin.customers.*')
                        ? 'bg-[var(--color-brand-50)] text-[var(--color-brand-700)]'
                        : 'text-[var(--color-text-secondary)] hover:bg-[var(--color-neutral-50)] hover:text-[var(--color-text-primary)]' }}"
                >
                    <svg
                        class="h-5 w-5 shrink-0"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <circle cx="12" cy="8" r="3" />
                        <path d="M5 20c1.3-3.3 3.6-5 7-5s5.7 1.7 7 5" />
                    </svg>

                    مشتریان
                </a>

            </nav>
        </div>

        {{-- Content --}}
        <div class="mb-6">
            <div
                class="mb-2 px-3 text-[10px] font-bold uppercase tracking-[0.16em] text-[var(--color-text-muted)]"
            >
                Content
            </div>

            <nav class="space-y-1">

                <a
                    href="{{ route('admin.posts.index') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition
                    {{ request()->routeIs('admin.posts.*')
                        ? 'bg-[var(--color-brand-50)] text-[var(--color-brand-700)]'
                        : 'text-[var(--color-text-secondary)] hover:bg-[var(--color-neutral-50)] hover:text-[var(--color-text-primary)]' }}"
                >
                    <svg
                        class="h-5 w-5 shrink-0"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path d="M5 4h14v16H5z" />
                        <path d="M8 8h8M8 12h8M8 16h5" />
                    </svg>

                    مقالات
                </a>

                <a
                    href="{{ route('admin.blog-categories.index') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition
                    {{ request()->routeIs('admin.blog-categories.*')
                        ? 'bg-[var(--color-brand-50)] text-[var(--color-brand-700)]'
                        : 'text-[var(--color-text-secondary)] hover:bg-[var(--color-neutral-50)] hover:text-[var(--color-text-primary)]' }}"
                >
                    <svg
                        class="h-5 w-5 shrink-0"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path d="M4 5h16v14H4z" />
                        <path d="M8 9h8M8 13h5" />
                    </svg>

                    دسته‌بندی مقالات
                </a>

                <a
                    href="{{ route('admin.reviews.index') }}"
                    class="flex items-center justify-between gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition
                    {{ request()->routeIs('admin.reviews.*')
                        ? 'bg-[var(--color-brand-50)] text-[var(--color-brand-700)]'
                        : 'text-[var(--color-text-secondary)] hover:bg-[var(--color-neutral-50)] hover:text-[var(--color-text-primary)]' }}"
                >
                    <span class="flex items-center gap-3">
                        <svg
                            class="h-5 w-5 shrink-0"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="m12 4 2.3 4.7 5.2.8-3.8 3.7.9 5.2-4.6-2.5-4.6 2.5.9-5.2-3.8-3.7 5.2-.8L12 4z" />
                        </svg>

                        نظرات
                    </span>

                    @if(($pendingReviews ?? 0) > 0)
                        <span
                            class="inline-flex min-w-6 items-center justify-center rounded-full bg-amber-100 px-1.5 py-1 text-[10px] font-bold text-amber-700"
                        >
                            {{ $pendingReviews }}
                        </span>
                    @endif
                </a>

            </nav>
        </div>

        {{-- System --}}
        <div>
            <div
                class="mb-2 px-3 text-[10px] font-bold uppercase tracking-[0.16em] text-[var(--color-text-muted)]"
            >
                System
            </div>

            <nav class="space-y-1">

                <a
                    href="{{ route('admin.contact-messages.index') }}"
                    class="flex items-center justify-between gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition
                    {{ request()->routeIs('admin.contact-messages.*')
                        ? 'bg-[var(--color-brand-50)] text-[var(--color-brand-700)]'
                        : 'text-[var(--color-text-secondary)] hover:bg-[var(--color-neutral-50)] hover:text-[var(--color-text-primary)]' }}"
                >
                    <span class="flex items-center gap-3">
                        <svg
                            class="h-5 w-5 shrink-0"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <rect x="4" y="5" width="16" height="14" rx="2" />
                            <path d="m6 7 6 5 6-5" />
                        </svg>

                        پیام‌ها
                    </span>

                    @if(($unreadMessages ?? 0) > 0)
                        <span
                            class="inline-flex min-w-6 items-center justify-center rounded-full bg-red-100 px-1.5 py-1 text-[10px] font-bold text-red-700"
                        >
                            {{ $unreadMessages }}
                        </span>
                    @endif
                </a>

                <a
                    href="{{ route('admin.newsletter.index') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition
                    {{ request()->routeIs('admin.newsletter.*')
                        ? 'bg-[var(--color-brand-50)] text-[var(--color-brand-700)]'
                        : 'text-[var(--color-text-secondary)] hover:bg-[var(--color-neutral-50)] hover:text-[var(--color-text-primary)]' }}"
                >
                    <svg
                        class="h-5 w-5 shrink-0"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path d="M4 6h16v12H4z" />
                        <path d="m4 7 8 6 8-6" />
                    </svg>

                    خبرنامه
                </a>

                <a
                    href="{{ route('admin.settings.index') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition
                    {{ request()->routeIs('admin.settings.*')
                        ? 'bg-[var(--color-brand-50)] text-[var(--color-brand-700)]'
                        : 'text-[var(--color-text-secondary)] hover:bg-[var(--color-neutral-50)] hover:text-[var(--color-text-primary)]' }}"
                >
                    <svg
                        class="h-5 w-5 shrink-0"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path d="M12 4v2M12 18v2M4 12h2M18 12h2M6.3 6.3l1.4 1.4M16.3 16.3l1.4 1.4M17.7 6.3l-1.4 1.4M7.7 16.3l-1.4 1.4" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>

                    تنظیمات
                </a>

            </nav>
        </div>

    </div>

    {{-- Footer --}}
    <div
        class="shrink-0 border-t border-[var(--color-border)] p-4"
    >
        <div
            class="rounded-2xl bg-[var(--color-neutral-50)] p-4"
        >
            <div
                class="text-xs font-bold text-[var(--color-text-primary)]"
            >
                Farzin Admin
            </div>

            <div
                class="mt-1 text-[11px] leading-5 text-[var(--color-text-muted)]"
            >
                مدیریت فروش، محتوا و موجودی
            </div>
        </div>
    </div>
</aside>
