@extends('layouts.app')

@section('title', 'فرزین | خرید مطمئن، انتخاب حرفه‌ای')

@section('meta_description', 'فرزین؛ فروشگاه آنلاین برای انتخاب محصولات باکیفیت، خرید مطمئن و تجربه‌ای حرفه‌ای و ساده.')

@section('content')

    {{-- =========================================================
        HERO
        Only the latest 10 active products with a primary image are
        loaded by HomeController. The browser never receives the catalog.
    ========================================================== --}}

    @php($heroSlides = ($heroProducts ?? collect())->values())

    <section
        class="relative isolate overflow-hidden bg-[var(--color-brand-950)] text-white"
        data-home-hero
        aria-label="محصولات تازه فرزین"
    >
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute -right-40 -top-40 h-[30rem] w-[30rem] rounded-full bg-[var(--color-accent-600)]/15 blur-3xl"></div>
            <div class="absolute -bottom-48 -left-32 h-[32rem] w-[32rem] rounded-full bg-[var(--color-brand-700)]/25 blur-3xl"></div>
            <div class="absolute inset-0 opacity-[0.035]" style="background-image:linear-gradient(rgba(255,255,255,.7) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.7) 1px,transparent 1px);background-size:56px 56px;"></div>
        </div>

        <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 md:py-16 lg:px-8 lg:py-20">
            <div class="grid items-center gap-10 lg:grid-cols-[.9fr_1.1fr] lg:gap-16">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/[.06] px-4 py-2 text-xs font-bold text-white/75 backdrop-blur">
                        <span class="h-2 w-2 rounded-full bg-[var(--color-accent-500)]"></span>
                        جدیدترین محصولات فرزین
                    </span>

                    <h1 class="mt-6 text-4xl font-black leading-[1.12] tracking-tight sm:text-5xl lg:text-6xl xl:text-7xl">
                        تازه‌ترین انتخاب‌ها،
                        <span class="text-[var(--color-accent-400)]">همین حالا.</span>
                    </h1>

                    <p class="mt-6 max-w-xl text-base leading-8 text-white/65 sm:text-lg">
                        فقط ۱۰ محصول تازه و فعال فروشگاه در این بخش نمایش داده می‌شود؛
                        سریع، سبک و مستقیم برای کشف محصول بعدی.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('shop.index', ['sort' => 'latest']) }}"
                           class="inline-flex min-h-12 items-center justify-center gap-3 rounded-2xl bg-[var(--color-accent-600)] px-6 py-3 text-sm font-black text-white shadow-xl shadow-[var(--color-accent-600)]/20 transition hover:-translate-y-0.5 hover:bg-[var(--color-accent-700)]">
                            مشاهده تازه‌ها
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m9 18 6-6-6-6"/></svg>
                        </a>
                        <a href="{{ route('shop.index') }}"
                           class="inline-flex min-h-12 items-center justify-center gap-2 rounded-2xl border border-white/10 bg-white/[.05] px-6 py-3 text-sm font-black text-white transition hover:bg-white/10">
                            ورود به فروشگاه
                        </a>
                    </div>

                    <div class="mt-8 flex flex-wrap gap-2 text-xs text-white/55">
                        <span class="rounded-full border border-white/10 bg-white/[.04] px-3 py-2">تازه‌ترین محصولات</span>
                        <span class="rounded-full border border-white/10 bg-white/[.04] px-3 py-2">اطلاعات شفاف</span>
                        <span class="rounded-full border border-white/10 bg-white/[.04] px-3 py-2">خرید مطمئن</span>
                    </div>
                </div>

                <div class="relative min-w-0">
                    <div class="relative mx-auto max-w-[620px]">
                        <div class="absolute inset-8 rounded-[3rem] bg-[var(--color-accent-600)]/10 blur-3xl"></div>

                        <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/[.055] p-3 shadow-2xl backdrop-blur-xl sm:p-4">
                            <div class="relative aspect-[4/4.5] overflow-hidden rounded-[1.5rem] bg-white/[.045]">
                                @forelse($heroSlides as $index => $product)
                                    <article
                                        class="absolute inset-0 transition duration-500 ease-out {{ $index === 0 ? 'opacity-100 translate-x-0' : 'pointer-events-none opacity-0 translate-x-3' }}"
                                        data-hero-slide="{{ $index }}"
                                        aria-hidden="{{ $index === 0 ? 'false' : 'true' }}"
                                    >
                                        <a href="{{ route('products.show', $product) }}" class="block h-full">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/5 to-transparent"></div>
                                            <img
                                                src="{{ asset('storage/' . $product->primaryImage->image) }}"
                                                alt="{{ $product->primaryImage->alt ?: $product->name }}"
                                                class="h-full w-full object-cover"
                                                {{ $index === 0 ? 'loading=eager' : 'loading=lazy' }}
                                                decoding="async"
                                            >
                                            <div class="absolute inset-x-0 bottom-0 p-5 sm:p-7">
                                                <div class="flex items-center justify-between gap-3">
                                                    <span class="rounded-full bg-white/10 px-3 py-1.5 text-[10px] font-bold text-white/75 backdrop-blur">
                                                        {{ $product->category?->name ?? 'محصول جدید' }}
                                                    </span>
                                                    <span class="text-xs font-bold text-white/70">{{ $index + 1 }} / {{ $heroSlides->count() }}</span>
                                                </div>
                                                <h2 class="mt-3 line-clamp-2 text-xl font-black leading-8 sm:text-2xl">{{ $product->name }}</h2>
                                                <div class="mt-2 text-sm font-bold text-white/75">{{ number_format($product->price) }} تومان</div>
                                            </div>
                                        </a>
                                    </article>
                                @empty
                                    <div class="flex h-full items-center justify-center p-8 text-center text-sm text-white/55">
                                        هنوز محصول فعالی برای نمایش در این بخش ثبت نشده است.
                                    </div>
                                @endforelse
                            </div>

                            @if($heroSlides->count() > 1)
                                <div class="mt-3 flex items-center gap-3">
                                    <button type="button" data-hero-prev aria-label="محصول قبلی"
                                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/[.05] text-white transition hover:bg-white/10 focus-visible:outline-white">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m14 18-6-6 6-6"/></svg>
                                    </button>
                                    <div class="flex min-w-0 flex-1 gap-1.5" role="tablist" aria-label="اسلایدهای محصولات">
                                        @foreach($heroSlides as $index => $product)
                                            <button type="button" data-hero-dot="{{ $index }}" aria-label="نمایش محصول {{ $index + 1 }}" aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                                                    class="h-1.5 flex-1 rounded-full bg-white/15 transition {{ $index === 0 ? 'bg-white' : '' }}"></button>
                                        @endforeach
                                    </div>
                                    <button type="button" data-hero-next aria-label="محصول بعدی"
                                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/[.05] text-white transition hover:bg-white/10 focus-visible:outline-white">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m10 18 6-6-6-6"/></svg>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =========================================================
        TRUST STRIP
    ========================================================== --}}

    <section class="border-b border-[var(--color-border)] bg-white">

        <div class="mx-auto grid max-w-7xl grid-cols-2 gap-px bg-[var(--color-neutral-100)] px-4 sm:px-6 lg:grid-cols-4 lg:px-8">

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

    <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">

        <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">

            <div>

                <div class="text-[11px] font-black uppercase tracking-[0.25em] text-[var(--color-accent-600)]">
                    Explore
                </div>

                <h2 class="mt-3 text-3xl font-black tracking-tight text-[var(--color-text-primary)] sm:text-4xl">
                    دسته‌بندی‌ها
                </h2>

                <p class="mt-3 max-w-2xl text-sm leading-7 text-[var(--color-text-secondary)]">
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


        <div class="mt-10 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">

            @forelse($categories ?? [] as $category)

                <a
                    href="{{ route('categories.show', $category) }}"
                    class="group relative overflow-hidden rounded-[1.75rem] border border-[var(--color-border)] bg-white p-3 shadow-[var(--shadow-xs)] transition duration-300 hover:-translate-y-1 hover:border-[var(--color-brand-300)] hover:shadow-[var(--shadow-md)]"
                >

                    <div
                        class="relative aspect-[1.05] overflow-hidden rounded-[1.4rem] bg-gradient-to-br from-[#eef0f3] to-[#d9dde3]"
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

    </section>


    {{-- =========================================================
        FEATURED PRODUCTS
    ========================================================== --}}

    <section class="bg-[var(--color-neutral-50)] py-20">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">

                <div>

                    <div class="text-[11px] font-black uppercase tracking-[0.25em] text-[var(--color-accent-600)]">
                        Farzin Selection
                    </div>

                    <h2 class="mt-3 text-3xl font-black tracking-tight text-[var(--color-text-primary)] sm:text-4xl">
                        انتخاب‌های ویژه
                    </h2>

                    <p class="mt-3 max-w-2xl text-sm leading-7 text-[var(--color-text-secondary)]">
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


            <div class="mt-10 grid grid-cols-2 gap-x-4 gap-y-8 md:grid-cols-3 lg:grid-cols-4">

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

    <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">

        <div
            class="overflow-hidden rounded-[2.5rem] bg-[var(--color-brand-900)] text-white"
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

    </section>


    {{-- =========================================================
        LATEST PRODUCTS
    ========================================================== --}}

    <section class="pb-20">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">

                <div>

                    <div class="text-[11px] font-black uppercase tracking-[0.25em] text-[var(--color-accent-600)]">
                        New In
                    </div>

                    <h2 class="mt-3 text-3xl font-black tracking-tight text-[var(--color-text-primary)] sm:text-4xl">
                        تازه‌های فرزین
                    </h2>

                    <p class="mt-3 text-sm leading-7 text-[var(--color-text-secondary)]">
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


            <div class="mt-10 grid grid-cols-2 gap-x-4 gap-y-8 md:grid-cols-3 lg:grid-cols-4">

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

    <section class="border-t border-[var(--color-border)] bg-white">

        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">

            <div
                class="relative overflow-hidden rounded-[2rem] bg-[var(--color-neutral-100)] p-7 sm:p-10 lg:p-12"
            >

                <div
                    class="pointer-events-none absolute -right-20 -top-20 h-56 w-56 rounded-full bg-[var(--color-accent-600)]/10 blur-3xl"
                ></div>

                <div class="relative flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">

                    <div class="max-w-2xl">

                        <span class="text-[11px] font-black uppercase tracking-[0.2em] text-[var(--color-accent-600)]">
                            FARZIN
                        </span>

                        <h2 class="mt-3 text-3xl font-black text-[var(--color-text-primary)] sm:text-4xl">
                            آماده‌ای انتخاب بهتری داشته باشی؟
                        </h2>

                        <p class="mt-4 text-sm leading-7 text-[var(--color-text-secondary)]">
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
