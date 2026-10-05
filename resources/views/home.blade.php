@extends('layouts.app')

@section('title', 'فرزین | خرید مطمئن، انتخاب حرفه‌ای')

@section('meta_description', 'فرزین؛ فروشگاه آنلاین برای انتخاب محصولات باکیفیت، خرید مطمئن و تجربه‌ای حرفه‌ای و ساده.')

@section('content')

    {{-- =========================================================
        HERO
        Premium product rail. Backend intentionally supplies only the
        latest 6 image-ready active products.
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
                        ۶ محصول تازه
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

            <div class="farzin-home-trust__item bg-white px-5 py-7" data-home-reveal>
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

            <div class="farzin-home-trust__item bg-white px-5 py-7" data-home-reveal>
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

            <div class="farzin-home-trust__item bg-white px-5 py-7" data-home-reveal>
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

            <div class="farzin-home-trust__item bg-white px-5 py-7" data-home-reveal>
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
        JOURNAL / KNOWLEDGE HUB
    ========================================================== --}}

    <section
        class="farzin-section farzin-home-section farzin-home-section--editorial"
        data-home-section
    >
        <div class="farzin-container">
            <div class="farzin-journal" data-home-reveal>
                <div class="farzin-journal__main">
                    <div class="farzin-journal__eyebrow">
                        <span class="farzin-journal__eyebrow-line"></span>
                        FARZIN / JOURNAL
                    </div>

                    <div class="farzin-journal__heading">
                        <span class="farzin-journal__index">03 / KNOWLEDGE</span>

                        <h2>
                            قبل از خرید،
                            <strong>هوشمندانه‌تر انتخاب کن.</strong>
                        </h2>

                        <p>
                            راهنما، مقایسه و نکات تخصصی برای اینکه قبل از تصمیم نهایی،
                            محصول را بهتر بشناسی و انتخابت دقیق‌تر باشد.
                        </p>
                    </div>

                    <a
                        href="{{ route('blog.index') }}"
                        class="farzin-journal__cta"
                    >
                        <span>ورود به مجله فرزین</span>

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            aria-hidden="true"
                        >
                            <path d="m9 18 6-6-6-6"/>
                        </svg>
                    </a>
                </div>

                <div class="farzin-journal__side">
                    <div class="farzin-journal__signal">
                        <div class="farzin-journal__signal-head">
                            <span>KNOWLEDGE SYSTEM</span>
                            <span class="farzin-journal__status">
                                <i aria-hidden="true"></i>
                                ACTIVE
                            </span>
                        </div>

                        <div class="farzin-journal__signal-core">
                            <span class="farzin-journal__signal-number">03</span>
                            <span class="farzin-journal__signal-label">WAYS TO CHOOSE BETTER</span>
                        </div>

                        <div class="farzin-journal__scan" aria-hidden="true">
                            <span></span>
                        </div>
                    </div>

                    <div class="farzin-journal__tracks">
                        <a href="{{ route('blog.index') }}" class="farzin-journal__track">
                            <span class="farzin-journal__track-number">01</span>

                            <span class="farzin-journal__track-copy">
                                <strong>راهنمای خرید</strong>
                                <small>از مشخصات تا انتخاب نهایی</small>
                            </span>

                            <span class="farzin-journal__track-arrow" aria-hidden="true">↗</span>
                        </a>

                        <a href="{{ route('blog.index') }}" class="farzin-journal__track">
                            <span class="farzin-journal__track-number">02</span>

                            <span class="farzin-journal__track-copy">
                                <strong>مقایسه محصولات</strong>
                                <small>تفاوت‌ها را سریع‌تر ببین</small>
                            </span>

                            <span class="farzin-journal__track-arrow" aria-hidden="true">↗</span>
                        </a>

                        <a href="{{ route('blog.index') }}" class="farzin-journal__track">
                            <span class="farzin-journal__track-number">03</span>

                            <span class="farzin-journal__track-copy">
                                <strong>نکات تخصصی</strong>
                                <small>برای تصمیمی مطمئن‌تر</small>
                            </span>

                            <span class="farzin-journal__track-arrow" aria-hidden="true">↗</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- =========================================================
        LATEST PRODUCTS
    ========================================================== --}}

    <section
        class="farzin-section farzin-section--compact farzin-home-section farzin-home-section--latest farzin-latest"
        data-home-section
    >
        <div class="farzin-container">
            <div class="farzin-latest__head" data-home-reveal>
                <div class="farzin-latest__heading">
                    <span class="farzin-eyebrow">FARZIN / NEW ARRIVALS</span>

                    <div class="farzin-latest__title-row">
                        <div>
                            <span class="farzin-latest__index">04</span>

                            <h2 class="farzin-section-title">
                                تازه‌های فرزین
                            </h2>
                        </div>

                        <span class="farzin-latest__signal" aria-hidden="true">
                            <i></i>
                            NEW
                        </span>
                    </div>

                    <p class="farzin-section-description">
                        آخرین محصولاتی که وارد فروشگاه شده‌اند؛
                        برای وقتی که می‌خواهی سریع از تازه‌ترین انتخاب‌ها باخبر شوی.
                    </p>
                </div>

                <a
                    href="{{ route('shop.index', ['sort' => 'latest']) }}"
                    class="farzin-latest__link"
                >
                    <span>مشاهده همه تازه‌ها</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                </a>
            </div>

            <div
                class="farzin-latest__grid farzin-home-grid grid grid-cols-2 gap-x-3 gap-y-8 sm:gap-x-4 md:grid-cols-3 lg:grid-cols-4"
                data-home-reveal
            >
                @forelse($latestProducts ?? [] as $product)
                    @include('partials.product_card', [
                        'product' => $product
                    ])
                @empty
                    <div class="farzin-latest__empty col-span-full">
                        <span aria-hidden="true">◎</span>
                        <div>
                            <strong>هنوز محصول تازه‌ای ثبت نشده است.</strong>
                            <p>محصولات جدید که اضافه شوند، اینجا نمایش داده می‌شوند.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>


    {{-- =========================================================
        FINAL CTA
    ========================================================== --}}

    <section
        class="farzin-section farzin-section--compact farzin-section--bordered farzin-home-section farzin-home-section--cta farzin-final-cta"
        data-home-section
    >
        <div class="farzin-container">
            <div class="farzin-final-cta__shell" data-home-reveal>
                <div class="farzin-final-cta__glow" aria-hidden="true"></div>

                <div class="farzin-final-cta__main">
                    <div class="farzin-final-cta__eyebrow">
                        <span class="farzin-final-cta__line"></span>
                        FARZIN / NEXT STEP
                    </div>

                    <span class="farzin-final-cta__index">05</span>

                    <h2>
                        انتخابت را
                        <strong>کامل کن.</strong>
                    </h2>

                    <p>
                        محصول مناسب را پیدا کردی؟ وارد فروشگاه شو، جزئیات را بررسی کن
                        و مسیر خرید را از همین‌جا ادامه بده.
                    </p>

                    <div class="farzin-final-cta__actions">
                        <a
                            href="{{ route('shop.index') }}"
                            class="farzin-final-cta__primary"
                        >
                            <span>رفتن به فروشگاه</span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="m9 18 6-6-6-6"/>
                            </svg>
                        </a>

                        <a
                            href="{{ route('blog.index') }}"
                            class="farzin-final-cta__secondary"
                        >
                            دانش خرید
                            <span aria-hidden="true">↗</span>
                        </a>
                    </div>
                </div>

                <div class="farzin-final-cta__system" aria-hidden="true">
                    <div class="farzin-final-cta__system-top">
                        <span>SHOP / JOURNAL</span>
                        <span>05—02</span>
                    </div>

                    <div class="farzin-final-cta__rings">
                        <span></span>
                        <span></span>
                        <span></span>
                        <i></i>
                    </div>

                    <div class="farzin-final-cta__system-bottom">
                        <span>DISCOVER</span>
                        <span>CHOOSE</span>
                        <span>BUY</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
