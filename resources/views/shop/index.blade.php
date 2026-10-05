@extends('layouts.app')

@section('title', 'فروشگاه | فرزین')
@section('meta_description', 'خرید هود و سینک فرزین با مشاهده قیمت، موجودی، دسته‌بندی و مشخصات محصولات.')

@section('content')

    {{-- SHOP HERO — intentionally preserved --}}
    <section class="shop-page__hero-shell farzin-container pt-3 lg:pt-6">
        <div class="shop-hero">
            <div class="shop-hero__frame">
                <div class="shop-hero__media" aria-hidden="true">
                    @if($shopHeroProduct?->primaryImage)
                        <img
                            src="{{ $shopHeroProduct->primaryImage->url }}"
                            alt=""
                            loading="eager"
                            fetchpriority="high"
                            decoding="async"
                        >
                    @else
                        <div class="shop-hero__fallback"></div>
                    @endif
                </div>

                <div class="shop-hero__veil"></div>

                <div class="shop-hero__content">
                    <div class="shop-hero__eyebrow">
                        <span>FARZIN / SHOP</span>
                        <span class="shop-hero__line"></span>
                        <span>COLLECTION</span>
                    </div>

                    <div class="shop-hero__copy">
                        <p>FARZIN KITCHEN / HOOD & SINK</p>

                        <h1>
                            هود و سینک،
                            <br>
                            <span>با انتخابی دقیق‌تر.</span>
                        </h1>

                        <div class="shop-hero__description">
                            <span>
                                {{ $shopHeroProduct?->short_description ?: 'مجموعه‌ای از هود و سینک‌های منتخب، با طراحی تمیز و انتخابی مطمئن برای آشپزخانه.' }}
                            </span>
                        </div>

                        <a href="#shop-products" class="shop-hero__cta">
                            <span>مشاهده محصولات</span>
                            <span aria-hidden="true">←</span>
                        </a>
                    </div>
                </div>

                <div class="shop-hero__meta" aria-label="اطلاعات فروشگاه">
                    <div>
                        <strong>{{ number_format($products->total()) }}</strong>
                        <span>محصول</span>
                    </div>

                    <div>
                        <strong>{{ number_format($categories->count()) }}</strong>
                        <span>دسته فعال</span>
                    </div>

                    <div>
                        <strong>24/7</strong>
                        <span>دسترسی آنلاین</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="farzin-container pt-5 lg:pt-7">
        <x-layout.breadcrumb :items="[['label' => 'فروشگاه']]" />
    </div>

    <section id="shop-products" class="farzin-section farzin-section--compact">
        <div class="farzin-container">

            @php
                $hasShopFilters = filled(request('search'))
                    || filled(request('category'))
                    || filled(request('min_price'))
                    || filled(request('max_price'))
                    || request('sort', 'latest') !== 'latest';

                $activeCategory = $categories->firstWhere('slug', request('category'));

                $sortLabels = [
                    'latest' => 'جدیدترین',
                    'price_asc' => 'ارزان‌ترین',
                    'price_desc' => 'گران‌ترین',
                    'popular' => 'محبوب‌ترین',
                    'rating' => 'بالاترین امتیاز',
                ];
            @endphp

            <header class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <span class="farzin-eyebrow">FARZIN / CATALOG</span>
                    <h2 class="farzin-page-title mt-3">فروشگاه</h2>
                    <p class="farzin-section-description mt-3">
                        بین هود و سینک‌های فعال فرزین جستجو کن، دسته‌بندی را محدود کن و مناسب‌ترین گزینه را انتخاب کن.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="rounded-2xl border border-[var(--color-border)] bg-[var(--color-earth-50)] px-4 py-3 text-xs">
                        <span class="text-[var(--color-text-muted)]">نتیجه</span>
                        <strong class="mr-1 font-black text-[var(--color-brand-950)]">{{ number_format($products->total()) }}</strong>
                    </div>

                    <div class="rounded-2xl border border-[var(--color-earth-200)] bg-white px-4 py-3 text-xs">
                        <span class="text-[var(--color-text-muted)]">مرتب‌سازی</span>
                        <strong class="mr-1 font-black text-[var(--color-earth-800)]">{{ $sortLabels[request('sort', 'latest')] ?? 'جدیدترین' }}</strong>
                    </div>
                </div>
            </header>

            <nav class="mt-8 flex gap-2 overflow-x-auto pb-1" aria-label="دسته‌بندی فروشگاه">
                <a
                    href="{{ route('shop.index') }}"
                    class="shrink-0 rounded-full border px-4 py-2.5 text-xs font-black transition {{ !request('category') ? 'border-[var(--color-brand-900)] bg-[var(--color-brand-900)] text-white' : 'border-[var(--color-border)] bg-white text-[var(--color-text-secondary)] hover:border-[var(--color-earth-300)] hover:bg-[var(--color-earth-50)]' }}"
                >
                    همه محصولات
                </a>

                @foreach($categories as $category)
                    <a
                        href="{{ route('shop.index', ['category' => $category->slug]) }}"
                        class="shrink-0 rounded-full border px-4 py-2.5 text-xs font-black transition {{ request('category') === $category->slug ? 'border-[var(--color-earth-700)] bg-[var(--color-earth-700)] text-white' : 'border-[var(--color-border)] bg-white text-[var(--color-text-secondary)] hover:border-[var(--color-earth-300)] hover:bg-[var(--color-earth-50)]' }}"
                    >
                        {{ $category->name }}
                    </a>
                @endforeach
            </nav>

            @if($hasShopFilters)
                <div class="mt-5 flex flex-wrap items-center gap-2 rounded-2xl border border-[var(--color-earth-200)] bg-[var(--color-earth-50)] p-3">
                    <span class="px-2 text-[10px] font-black text-[var(--color-earth-800)]">فیلترهای فعال</span>

                    @if(filled(request('search')))
                        <span class="rounded-full bg-white px-3 py-1.5 text-[10px] font-bold text-[var(--color-text-secondary)]">
                            جستجو: {{ request('search') }}
                        </span>
                    @endif

                    @if($activeCategory)
                        <span class="rounded-full bg-white px-3 py-1.5 text-[10px] font-bold text-[var(--color-text-secondary)]">
                            دسته: {{ $activeCategory->name }}
                        </span>
                    @endif

                    @if(filled(request('min_price')))
                        <span class="rounded-full bg-white px-3 py-1.5 text-[10px] font-bold text-[var(--color-text-secondary)]">
                            از {{ number_format((int) request('min_price')) }} تومان
                        </span>
                    @endif

                    @if(filled(request('max_price')))
                        <span class="rounded-full bg-white px-3 py-1.5 text-[10px] font-bold text-[var(--color-text-secondary)]">
                            تا {{ number_format((int) request('max_price')) }} تومان
                        </span>
                    @endif

                    <a href="{{ route('shop.index') }}" class="mr-auto rounded-full px-3 py-1.5 text-[10px] font-black text-[var(--color-accent-700)] transition hover:bg-white">
                        پاک کردن
                    </a>
                </div>
            @endif

            <form action="{{ route('shop.index') }}" method="GET" class="mt-6 rounded-[1.75rem] border border-[var(--color-border)] bg-white p-3 shadow-[var(--shadow-xs)]">
                <div class="grid gap-3 lg:grid-cols-[minmax(0,1fr)_180px_auto]">
                    <label class="relative block">
                        <span class="sr-only">جستجو</span>
                        <input
                            type="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="نام محصول، برند یا SKU را جستجو کن..."
                            class="w-full rounded-2xl border border-transparent bg-[var(--color-earth-50)] px-4 py-3.5 text-sm outline-none transition placeholder:text-[var(--color-text-soft)] focus:border-[var(--color-earth-300)] focus:bg-white focus:ring-4 focus:ring-[var(--color-earth-200)]/40"
                        >
                    </label>

                    <select
                        name="sort"
                        class="rounded-2xl border border-[var(--color-border)] bg-white px-4 py-3.5 text-sm font-bold outline-none transition focus:border-[var(--color-earth-400)]"
                    >
                        @foreach($sortLabels as $value => $label)
                            <option value="{{ $value }}" @selected(request('sort', 'latest') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="rounded-2xl bg-[var(--color-brand-900)] px-6 py-3.5 text-sm font-black text-white transition hover:bg-[var(--color-brand-950)]">
                        جستجو و مرتب‌سازی
                    </button>
                </div>
            </form>

            <div class="mt-6 grid gap-6 lg:grid-cols-[250px_minmax(0,1fr)]">

                <aside x-data="{ open: false }" class="lg:sticky lg:top-28 lg:self-start">
                    <button
                        type="button"
                        class="flex w-full items-center justify-between rounded-2xl border border-[var(--color-border)] bg-white px-4 py-3.5 text-sm font-black text-[var(--color-text-primary)] lg:hidden"
                        @click="open = !open"
                        :aria-expanded="open.toString()"
                    >
                        <span>فیلترهای بیشتر</span>
                        <span x-text="open ? '−' : '+'"></span>
                    </button>

                    <div x-show="open" x-cloak class="mt-3 lg:mt-0 lg:block">
                        <form action="{{ route('shop.index') }}" method="GET" class="rounded-[1.75rem] border border-[var(--color-border)] bg-[var(--color-earth-50)] p-5">
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif

                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <span class="text-[9px] font-black uppercase tracking-[0.18em] text-[var(--color-earth-700)]">FILTERS</span>
                                    <h3 class="mt-1 text-base font-black">فیلتر فروشگاه</h3>
                                </div>

                                <a href="{{ route('shop.index') }}" class="text-[10px] font-black text-[var(--color-text-muted)] hover:text-[var(--color-accent-600)]">
                                    پاک‌سازی
                                </a>
                            </div>

                            <div class="mt-6">
                                <label for="shop-category" class="text-xs font-black text-[var(--color-text-secondary)]">دسته‌بندی</label>
                                <select id="shop-category" name="category" class="mt-2 w-full rounded-xl border border-[var(--color-border)] bg-white px-3.5 py-3 text-sm outline-none focus:border-[var(--color-earth-400)]">
                                    <option value="">همه دسته‌ها</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mt-5">
                                <label for="filter-sort" class="text-xs font-black text-[var(--color-text-secondary)]">مرتب‌سازی</label>
                                <select id="filter-sort" name="sort" class="mt-2 w-full rounded-xl border border-[var(--color-border)] bg-white px-3.5 py-3 text-sm outline-none focus:border-[var(--color-earth-400)]">
                                    @foreach($sortLabels as $value => $label)
                                        <option value="{{ $value }}" @selected(request('sort', 'latest') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mt-5">
                                <label for="per-page" class="text-xs font-black text-[var(--color-text-secondary)]">تعداد در صفحه</label>
                                <select id="per-page" name="per_page" class="mt-2 w-full rounded-xl border border-[var(--color-border)] bg-white px-3.5 py-3 text-sm outline-none focus:border-[var(--color-earth-400)]">
                                    <option value="12" @selected((int) request('per_page', 12) === 12)>۱۲ محصول</option>
                                    <option value="24" @selected((int) request('per_page', 12) === 24)>۲۴ محصول</option>
                                    <option value="48" @selected((int) request('per_page', 12) === 48)>۴۸ محصول</option>
                                </select>
                            </div>

                            <div class="mt-5">
                                <span class="text-xs font-black text-[var(--color-text-secondary)]">بازه قیمت</span>
                                <div class="mt-2 grid grid-cols-2 gap-2">
                                    <input type="number" min="0" name="min_price" value="{{ request('min_price') }}" placeholder="{{ number_format($priceMin) }}" class="min-w-0 rounded-xl border border-[var(--color-border)] bg-white px-3 py-3 text-xs outline-none focus:border-[var(--color-earth-400)]">
                                    <input type="number" min="0" name="max_price" value="{{ request('max_price') }}" placeholder="{{ number_format($priceMax) }}" class="min-w-0 rounded-xl border border-[var(--color-border)] bg-white px-3 py-3 text-xs outline-none focus:border-[var(--color-earth-400)]">
                                </div>
                            </div>

                            <button type="submit" class="mt-6 w-full rounded-xl bg-[var(--color-brand-900)] px-4 py-3.5 text-xs font-black text-white transition hover:bg-[var(--color-brand-950)]">
                                اعمال فیلتر
                            </button>
                        </form>
                    </div>
                </aside>

                <div class="min-w-0">
                    <div class="mb-5 flex flex-col gap-3 rounded-[1.5rem] border border-[var(--color-border)] bg-white px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-5">
                        <div>
                            <span class="text-[9px] font-black uppercase tracking-[0.18em] text-[var(--color-earth-700)]">RESULTS</span>
                            <p class="mt-1 text-xs text-[var(--color-text-secondary)]">
                                {{ number_format($products->firstItem() ?? 0) }} تا {{ number_format($products->lastItem() ?? 0) }} از {{ number_format($products->total()) }} محصول
                            </p>
                        </div>

                        <a href="{{ route('shop.index') }}" class="text-[10px] font-black text-[var(--color-accent-700)] hover:text-[var(--color-accent-600)]">
                            بازنشانی فهرست
                        </a>
                    </div>

                    @if($products->isNotEmpty())
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-2 sm:gap-4 xl:grid-cols-3">
                            @foreach($products as $product)
                                @include('partials.product_card', [
                                    'product' => $product,
                                    'wishlistedProductIds' => $wishlistedProductIds ?? collect(),
                                ])
                            @endforeach
                        </div>

                        <div class="mt-10">
                            @include('shop.pagination', ['paginator' => $products])
                        </div>
                    @else
                        <div class="rounded-[2rem] border border-dashed border-[var(--color-border-strong)] bg-[var(--color-earth-50)] px-6 py-20 text-center">
                            <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-[var(--color-brand-900)] text-white">
                                <span class="text-xl">⌕</span>
                            </div>

                            <h2 class="mt-5 text-xl font-black">محصولی پیدا نشد</h2>
                            <p class="mx-auto mt-2 max-w-md text-sm leading-7 text-[var(--color-text-secondary)]">
                                عبارت جستجو یا فیلترها را کمی تغییر بده تا گزینه‌های بیشتری ببینی.
                            </p>

                            <a href="{{ route('shop.index') }}" class="mt-6 inline-flex rounded-2xl bg-[var(--color-brand-900)] px-6 py-3.5 text-sm font-black text-white transition hover:bg-[var(--color-brand-950)]">
                                نمایش همه محصولات
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
