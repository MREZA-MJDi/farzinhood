@extends('layouts.app')

@section('title', 'فرزین | خرید مطمئن، انتخاب حرفه‌ای')

@section('meta_description', 'فرزین؛ فروشگاه آنلاین برای انتخاب محصولات باکیفیت، خرید مطمئن و تجربه‌ای حرفه‌ای و ساده.')

@section('content')

    {{-- =========================================================
        HERO
        Premium product rail. Backend intentionally supplies only the
        latest 10 active products with a primary image.
    ========================================================== --}}

    @php($heroSlides = ($heroProducts ?? collect())->values())

    <section
        class="farzin-hero-rail"
        data-home-hero
        aria-label="جدیدترین محصولات فرزین"
    >
        <div class="farzin-hero-rail__glow farzin-hero-rail__glow--one" aria-hidden="true"></div>
        <div class="farzin-hero-rail__glow farzin-hero-rail__glow--two" aria-hidden="true"></div>
        <div class="farzin-hero-rail__grid" aria-hidden="true"></div>

        <div class="farzin-hero-rail__inner">
            <div class="farzin-hero-rail__copy">
                <span class="farzin-hero-rail__eyebrow">
                    <span></span>
                    جدیدترین محصولات فرزین
                </span>

                <h1 class="farzin-hero-rail__title">
                    انتخاب تازه،
                    <strong>با نگاه حرفه‌ای.</strong>
                </h1>

                <p class="farzin-hero-rail__description">
                    تازه‌ترین محصولات فروشگاه را در یک نگاه ببین،
                    انتخاب کن و مستقیم وارد جزئیات محصول شو.
                </p>

                <div class="farzin-hero-rail__actions">
                    <a
                        href="{{ route('shop.index', ['sort' => 'latest']) }}"
                        class="farzin-hero-rail__primary"
                    >
                        مشاهده تازه‌ها
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="m9 18 6-6-6-6"/>
                        </svg>
                    </a>
                    <span class="farzin-hero-rail__meta">
                        حداکثر ۱۰ محصول تازه
                    </span>
                </div>
            </div>

            <div class="farzin-hero-rail__stage">
                @forelse($heroSlides as $index => $product)
                    <article
                        class="farzin-hero-rail__slide {{ $index === 0 ? 'is-active' : '' }}"
                        data-hero-slide="{{ $index }}"
                        style="--hero-index: {{ $index }};"
                        aria-hidden="{{ $index === 0 ? 'false' : 'true' }}"
                    >
                        <a
                            href="{{ route('products.show', $product) }}"
                            class="farzin-hero-rail__product"
                            tabindex="{{ $index === 0 ? '0' : '-1' }}"
                        >
                            <div class="farzin-hero-rail__image-wrap">
                                <img
                                    src="{{ asset('storage/' . $product->primaryImage->image) }}"
                                    alt="{{ $product->primaryImage->alt ?: $product->name }}"
                                    class="farzin-hero-rail__image"
                                    {{ $index === 0 ? 'loading=eager' : 'loading=lazy' }}
                                    decoding="async"
                                >
                            </div>

                            <div class="farzin-hero-rail__product-info">
                                <div>
                                    <span class="farzin-hero-rail__category">
                                        {{ $product->category?->name ?? 'محصول جدید' }}
                                    </span>
                                    <h2>{{ $product->name }}</h2>
                                </div>

                                <strong>{{ number_format($product->price) }} <small>تومان</small></strong>
                            </div>
                        </a>
                    </article>
                @empty
                    <div class="farzin-hero-rail__empty">
                        هنوز محصول فعالی برای نمایش در Hero ثبت نشده است.
                    </div>
                @endforelse

                @if($heroSlides->count() > 1)
                    <button
                        type="button"
                        class="farzin-hero-rail__nav farzin-hero-rail__nav--prev"
                        data-hero-prev
                        aria-label="محصول قبلی"
                    >
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="m14 18-6-6 6-6"/>
                        </svg>
                    </button>

                    <button
                        type="button"
                        class="farzin-hero-rail__nav farzin-hero-rail__nav--next"
                        data-hero-next
                        aria-label="محصول بعدی"
                    >
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="m10 18 6-6-6-6"/>
                        </svg>
                    </button>

                    <div class="farzin-hero-rail__dots" role="tablist" aria-label="محصولات Hero">
                        @foreach($heroSlides as $index => $product)
                            <button
                                type="button"
                                data-hero-dot="{{ $index }}"
                                aria-label="محصول {{ $index + 1 }}"
                                aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                                class="{{ $index === 0 ? 'is-active' : '' }}"
                            ></button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- =========================================================
        TRUST STRIP
    ========================================================== --}}

    <section class="farzin-home-trust border-y border-[var(--color-border)] bg-white" data-home-section data-home-trust-strip>

        <div class="farzin-container farzin-home-trust__grid">

            <div class="bg-white px-5 py-7">
                <div class="text-xl font-black text-[var(--color-accent-600)]">
                    01
                </div>

                <div class="mt-2 text-sm font-black text-[var(--color-text-primary)]">
                    انتخاب دقیق
                </div>

                <div class="mt-1 text-xs leading-6 text-[var(--color-text-muted)]">
                    تمرکز روی محصولاتی که ارزش خرید دارند.
                </div>
            </div>

            <div class="bg-white px-5 py-7">
                <div class="text-xl font-black text-[var(--color-accent-600)]">
                    02
                </div>

                <div class="mt-2 text-sm font-black text-[var(--color-text-primary)]">
                    تجربه سریع
                </div>

                <div class="mt-1 text-xs leading-6 text-[var(--color-text-muted)]">
                    مسیر کوتاه از کشف محصول تا پرداخت.
                </div>
            </div>

            <div class="bg-white px-5 py-7">
                <div class="text-xl font-black text-[var(--color-accent-600)]">
                    03
                </div>

                <div class="mt-2 text-sm font-black text-[var(--color-text-primary)]">
                    پرداخت امن
                </div>

                <div class="mt-1 text-xs leading-6 text-[var(--color-text-muted)]">
                    فرآیند ساده و شفاف برای خرید.
                </div>
            </div>

            <div class="bg-white px-5 py-7">
                <div class="text-xl font-black text-[var(--color-accent-600)]">
                    04
                </div>

                <div class="mt-2 text-sm font-black text-[var(--color-text-primary)]">
                    پشتیبانی انسانی
                </div>

                <div class="mt-1 text-xs leading-6 text-[var(--color-text-muted)]">
                    وقتی نیاز داری، یک نفر پاسخ می‌دهد.
                </div>
            </div>

        </div>

    </section>


    {{-- =========================================================
        CATEGORIES
    ========================================================== --}}

    <section class="farzin-section farzin-home-section farzin-home-section--categories" data-home-section>

        <div class="farzin-container">

            <div class="farzin-home-section__head flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between" data-home-reveal>

            <div>

                <div class="farzin-eyebrow text-[11px] font-black uppercase tracking-[0.25em] text-[var(--color-accent-600)]">
                    Explore
                </div>

                <h2 class="mt-3 farzin-section-title text-3xl font-black tracking-tight text-[var(--color-text-primary)] sm:text-4xl">
                    دسته‌بندی‌ها
                </h2>

                <p class="mt-3 max-w-2xl farzin-section-description text-sm leading-7 text-[var(--color-text-secondary)]">
                    مسیرت را سریع‌تر پیدا کن و مستقیماً وارد دسته مورد نظرت شو.
                </p>

            </div>


            <a
                href="{{ route('shop.index') }}"
                class="inline-flex items-center gap-2 text-sm font-black text-[var(--color-brand-900)] transition hover:text-[var(--color-accent-600)]"
            >
                مشاهده همه

                <svg
                    class="h-4 w-4"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path d="m9 18 6-6-6-6"/>
                </svg>
            </a>

            </div>

            <div class="farzin-home-grid mt-10 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5" data-home-reveal>

            @forelse($categories ?? [] as $category)

                <a
                    href="{{ route('categories.show', $category) }}"
                    class="group relative overflow-hidden rounded-[1.75rem] border border-[var(--color-border)] bg-white p-3 shadow-[var(--shadow-xs)] transition duration-300 hover:-translate-y-1 hover:border-[var(--color-brand-300)] hover:shadow-[var(--shadow-md)]"
                >

                    <div
                        class="relative aspect-[1.05] overflow-hidden rounded-[1.4rem] bg-gradient-to-br from-[var(--color-neutral-100)] to-[var(--color-neutral-200)]"
                    >

                        @if($category->image)

                            <img
                                src="{{ asset('storage/' . $category->image) }}"
                                alt="{{ $category->name }}"
                                class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                loading="lazy"
                                decoding="async"
                            >

                        @else

                            <div class="flex h-full items-center justify-center text-[var(--color-text-soft)]">

                                <svg
                                    class="h-12 w-12"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.2"
                                >
                                    <rect x="3" y="4" width="18" height="16" rx="2"/>
                                    <circle cx="8.5" cy="9" r="1.3"/>
                                    <path d="m21 15-5-5-4.5 4.5-2.5-2.5L3 18"/>
                                </svg>

                            </div>

                        @endif


                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 transition duration-300 group-hover:opacity-100"
                        ></div>

                    </div>


                    <div class="px-2 pb-2 pt-4">

                        <div class="flex items-center justify-between gap-2">

                            <span class="truncate text-sm font-black text-[var(--color-text-primary)]">
                                {{ $category->name }}
                            </span>

                            <span
                                class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-[var(--color-brand-50)] text-[var(--color-brand-900)] transition group-hover:bg-[var(--color-accent-50)] group-hover:text-[var(--color-accent-600)]"
                            >
                                <svg
                                    class="h-3.5 w-3.5"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path d="m9 18 6-6-6-6"/>
                                </svg>
                            </span>

                        </div>

                        @if($category->description)

                            <p class="mt-1 line-clamp-2 text-[11px] leading-5 text-[var(--color-text-muted)]">
                                {{ $category->description }}
                            </p>

                        @endif

                    </div>

                </a>

            @empty

                <div class="col-span-full rounded-3xl border border-dashed border-[var(--color-border-strong)] bg-white px-6 py-12 text-center text-sm text-[var(--color-text-muted)]">
                    هنوز دسته‌بندی‌ای ثبت نشده است.
                </div>

            @endforelse

            </div>

        </div>

    </section>


    {{-- =========================================================
        FEATURED PRODUCTS
    ========================================================== --}}

    <section class="farzin-section farzin-section--muted farzin-home-section farzin-home-section--featured" data-home-section>

        <div class="farzin-container">

            <div class="farzin-home-section__head flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between" data-home-reveal>

                <div>

                    <div class="farzin-eyebrow text-[11px] font-black uppercase tracking-[0.25em] text-[var(--color-accent-600)]">
                        Farzin Selection
                    </div>

                    <h2 class="mt-3 farzin-section-title text-3xl font-black tracking-tight text-[var(--color-text-primary)] sm:text-4xl">
                        انتخاب‌های ویژه
                    </h2>

                    <p class="mt-3 max-w-2xl farzin-section-description text-sm leading-7 text-[var(--color-text-secondary)]">
                        محصولاتی که برای کیفیت، کاربرد و ارزش خرید بیشتر انتخاب شده‌اند.
                    </p>

                </div>


                <a
                    href="{{ route('shop.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-black text-[var(--color-brand-900)] transition hover:text-[var(--color-accent-600)]"
                >
                    دیدن فروشگاه

                    <svg
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                </a>

            </div>


            <div class="farzin-home-grid mt-10 grid grid-cols-2 gap-x-4 gap-y-8 md:grid-cols-3 lg:grid-cols-4" data-home-reveal>

                @forelse($featuredProducts ?? [] as $product)

                    @include('partials.product_card', [
                        'product' => $product
                    ])

                @empty

                    <div
                        class="col-span-full rounded-3xl border border-dashed border-[var(--color-border-strong)] bg-white px-6 py-16 text-center text-sm text-[var(--color-text-muted)]"
                    >
                        هنوز محصول ویژه‌ای موجود نیست.
                    </div>

                @endforelse

            </div>

        </div>

    </section>


    {{-- =========================================================
        EDITORIAL
    ========================================================== --}}

    <section class="farzin-section farzin-home-section farzin-home-section--editorial" data-home-section>

        <div class="farzin-container">

            <div
            class="farzin-editorial-card overflow-hidden rounded-[2.5rem] bg-[var(--color-brand-900)] text-white" data-home-reveal
        >

            <div class="grid items-center lg:grid-cols-[1fr_360px]">

                <div class="p-8 sm:p-12 lg:p-14">

                    <div class="text-[11px] font-black uppercase tracking-[0.24em] text-white/40">
                        Farzin Journal
                    </div>

                    <h2 class="mt-4 max-w-2xl text-3xl font-black leading-tight sm:text-5xl">
                        قبل از خرید،
                        <span class="text-[var(--color-accent-400)]">
                            بهتر انتخاب کن.
                        </span>
                    </h2>

                    <p class="mt-5 max-w-xl text-sm leading-8 text-white/60 sm:text-base">
                        راهنماهای خرید، مقایسه‌ها و محتوای تخصصی که کمک می‌کنند
                        انتخاب دقیق‌تر و مطمئن‌تری داشته باشی.
                    </p>

                    <a
                        href="{{ route('blog.index') }}"
                        class="mt-8 inline-flex items-center gap-2 rounded-2xl bg-[var(--color-accent-600)] px-6 py-4 text-sm font-black text-white transition hover:-translate-y-0.5 hover:bg-[var(--color-accent-700)]"
                    >
                        ورود به مجله

                        <svg
                            class="h-4 w-4"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="m9 18 6-6-6-6"/>
                        </svg>
                    </a>

                </div>


                <div class="hidden p-8 lg:block">

                    <div class="rounded-[2rem] border border-white/10 bg-white/[0.04] p-5">

                        <div class="rounded-[1.5rem] border border-white/10 bg-white/[0.03] p-5">

                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-white/35">
                                    FARZIN JOURNAL
                                </span>

                                <span class="h-2 w-2 rounded-full bg-[var(--color-accent-600)]"></span>
                            </div>

                            <div class="mt-6 space-y-3">

                                <div class="h-20 rounded-2xl bg-white/[0.05]"></div>
                                <div class="h-16 rounded-2xl bg-white/[0.035]"></div>
                                <div class="h-20 rounded-2xl bg-white/[0.05]"></div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        LATEST PRODUCTS
    ========================================================== --}}

    <section class="farzin-section farzin-section--compact farzin-home-section farzin-home-section--latest" data-home-section>

        <div class="farzin-container">

            <div class="farzin-home-section__head flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between" data-home-reveal>

                <div>

                    <div class="farzin-eyebrow text-[11px] font-black uppercase tracking-[0.25em] text-[var(--color-accent-600)]">
                        New In
                    </div>

                    <h2 class="mt-3 farzin-section-title text-3xl font-black tracking-tight text-[var(--color-text-primary)] sm:text-4xl">
                        تازه‌های فرزین
                    </h2>

                    <p class="mt-3 farzin-section-description text-sm leading-7 text-[var(--color-text-secondary)]">
                        جدیدترین محصولاتی که به فروشگاه اضافه شده‌اند.
                    </p>

                </div>


                <a
                    href="{{ route('shop.index', ['sort' => 'latest']) }}"
                    class="inline-flex items-center gap-2 text-sm font-black text-[var(--color-brand-900)] transition hover:text-[var(--color-accent-600)]"
                >
                    تازه‌ترین محصولات

                    <svg
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                </a>

            </div>


            <div class="farzin-home-grid mt-10 grid grid-cols-2 gap-x-4 gap-y-8 md:grid-cols-3 lg:grid-cols-4" data-home-reveal>

                @forelse($latestProducts ?? [] as $product)

                    @include('partials.product_card', [
                        'product' => $product
                    ])

                @empty

                    <div
                        class="col-span-full rounded-3xl border border-dashed border-[var(--color-border-strong)] bg-white px-6 py-16 text-center text-sm text-[var(--color-text-muted)]"
                    >
                        هنوز محصولی ثبت نشده است.
                    </div>

                @endforelse

            </div>

        </div>

    </section>


    {{-- =========================================================
        FINAL CTA
    ========================================================== --}}

    <section class="farzin-section farzin-section--compact farzin-section--bordered farzin-home-section farzin-home-section--cta" data-home-section>

        <div class="farzin-container">

            <div
                class="relative overflow-hidden rounded-[2rem] bg-[var(--color-neutral-100)] p-7 sm:p-10 lg:p-12"
            >

                <div
                    class="pointer-events-none absolute -right-20 -top-20 h-56 w-56 rounded-full bg-[var(--color-accent-600)]/10 blur-3xl"
                ></div>

                <div class="farzin-home-cta__inner relative flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between" data-home-reveal>

                    <div class="max-w-2xl">

                        <span class="text-[11px] font-black uppercase tracking-[0.2em] text-[var(--color-accent-600)]">
                            FARZIN
                        </span>

                        <h2 class="mt-3 text-3xl font-black text-[var(--color-text-primary)] sm:text-4xl">
                            آماده‌ای انتخاب بهتری داشته باشی؟
                        </h2>

                        <p class="mt-4 farzin-section-description text-sm leading-7 text-[var(--color-text-secondary)]">
                            محصولات را ببین، مقایسه کن و خریدت را با اطمینان انجام بده.
                        </p>

                    </div>


                    <a
                        href="{{ route('shop.index') }}"
                        class="inline-flex shrink-0 items-center justify-center gap-2 rounded-2xl bg-[var(--color-accent-600)] px-7 py-4 text-sm font-black text-white shadow-lg shadow-[var(--color-accent-600)]/15 transition hover:-translate-y-0.5 hover:bg-[var(--color-accent-700)]"
                    >
                        مشاهده فروشگاه

                        <svg
                            class="h-4 w-4"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="m9 18 6-6-6-6"/>
                        </svg>
                    </a>

                </div>

            </div>

        </div>

    </section>

@endsection
