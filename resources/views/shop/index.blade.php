@extends('layouts.app')

@section('title', 'فروشگاه | فرزین')

@section('meta_description', 'کاتالوگ فرزین؛ جستجو، فیلتر و مرتب‌سازی محصولات فعال فروشگاه.')

@section('content')
<div class="farzin-livora storefront-content overflow-hidden">
    <section class="farzin-shop-header">
        <div class="farzin-livora__container">
            <nav class="farzin-breadcrumb" aria-label="مسیر صفحه">
                <a href="{{ route('home') }}">خانه</a>
                <span aria-hidden="true">/</span>
                <span aria-current="page">فروشگاه</span>
            </nav>

            <div class="farzin-shop-header__row">
                <div>
                    <span>FARZIN COLLECTION</span>
                    <h1>فروشگاه</h1>
                    <p>محصول مناسب را با جستجو، دسته‌بندی و مرتب‌سازی پیدا کن.</p>
                </div>

                <div class="farzin-shop-header__count">
                    <b>{{ number_format($products->total()) }}</b>
                    <span>محصول</span>
                </div>
            </div>

            <div class="shop-category-rail" aria-label="دسته‌بندی‌های فروشگاه">
                <a
                    href="{{ route('shop.index') }}"
                    class="shop-category-chip @unless(request('category')) is-active @endunless"
                >همه محصولات</a>

                @foreach($categories as $category)
                    <a
                        href="{{ route('shop.index', array_filter([
                            'category' => $category->slug,
                            'search' => request('search'),
                        ])) }}"
                        class="shop-category-chip {{ request('category') === $category->slug ? 'is-active' : '' }}"
                    >{{ $category->name }}</a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="farzin-shop-body">
        <div class="farzin-livora__container">
            <form action="{{ route('shop.index') }}" method="GET" class="farzin-shop-search" role="search">
                <label class="sr-only" for="shop-search">جستجوی محصولات</label>
                <input
                    id="shop-search"
                    type="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="نام محصول، برند یا SKU..."
                    autocomplete="off"
                >

                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif

                <button type="submit">
                    جستجو
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <circle cx="11" cy="11" r="6.5"/>
                        <path d="m16 16 4.5 4.5"/>
                    </svg>
                </button>
            </form>

            <div class="farzin-shop-layout">
                <aside class="farzin-shop-filters">
                    <div class="farzin-filter-card">
                        <div class="farzin-filter-card__head">
                            <div>
                                <span>DISCOVER</span>
                                <h2>فیلترها</h2>
                            </div>
                            <a href="{{ route('shop.index') }}">پاک کردن</a>
                        </div>

                        <form action="{{ route('shop.index') }}" method="GET" class="farzin-filter-form">
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif

                            <label for="filter-category">دسته‌بندی</label>
                            <select id="filter-category" name="category">
                                <option value="">همه دسته‌ها</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                            <label for="filter-min-price">حداقل قیمت</label>
                            <input
                                id="filter-min-price"
                                type="number"
                                name="min_price"
                                min="0"
                                value="{{ request('min_price') }}"
                                placeholder="{{ number_format($priceMin) }}"
                                inputmode="numeric"
                            >

                            <label for="filter-max-price">حداکثر قیمت</label>
                            <input
                                id="filter-max-price"
                                type="number"
                                name="max_price"
                                min="0"
                                value="{{ request('max_price') }}"
                                placeholder="{{ number_format($priceMax) }}"
                                inputmode="numeric"
                            >

                            <label for="filter-sort">مرتب‌سازی</label>
                            <select id="filter-sort" name="sort">
                                @foreach([
                                    'latest' => 'جدیدترین',
                                    'price_asc' => 'ارزان‌ترین',
                                    'price_desc' => 'گران‌ترین',
                                    'popular' => 'محبوب‌ترین',
                                    'rating' => 'بالاترین امتیاز',
                                ] as $value => $label)
                                    <option value="{{ $value }}" @selected(request('sort', 'latest') === $value)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="submit">اعمال فیلتر</button>
                        </form>
                    </div>

                    <div class="farzin-filter-note">
                        <span>FARZIN / NOTE</span>
                        <strong>قیمت و موجودی نهایی دوباره کنترل می‌شوند.</strong>
                        <p>فیلترها برای کشف سریع هستند؛ کنترل نهایی در مسیر خرید از منبع اصلی انجام می‌شود.</p>
                    </div>
                </aside>

                <details class="farzin-mobile-filter">
                    <summary>
                        <span>فیلتر و مرتب‌سازی</span>
                        <span aria-hidden="true">⌄</span>
                    </summary>

                    <form action="{{ route('shop.index') }}" method="GET" class="farzin-filter-form">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        <label for="mobile-filter-category">دسته‌بندی</label>
                        <select id="mobile-filter-category" name="category">
                            <option value="">همه دسته‌ها</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        <div class="farzin-filter-form__prices">
                            <div>
                                <label for="mobile-filter-min">حداقل قیمت</label>
                                <input id="mobile-filter-min" type="number" name="min_price" min="0" value="{{ request('min_price') }}" inputmode="numeric">
                            </div>

                            <div>
                                <label for="mobile-filter-max">حداکثر قیمت</label>
                                <input id="mobile-filter-max" type="number" name="max_price" min="0" value="{{ request('max_price') }}" inputmode="numeric">
                            </div>
                        </div>

                        <label for="mobile-filter-sort">مرتب‌سازی</label>
                        <select id="mobile-filter-sort" name="sort">
                            @foreach([
                                'latest' => 'جدیدترین',
                                'price_asc' => 'ارزان‌ترین',
                                'price_desc' => 'گران‌ترین',
                                'popular' => 'محبوب‌ترین',
                                'rating' => 'بالاترین امتیاز',
                            ] as $value => $label)
                                <option value="{{ $value }}" @selected(request('sort', 'latest') === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit">اعمال</button>
                    </form>
                </details>

                <div class="farzin-shop-products">
                    <div class="farzin-results-toolbar" aria-label="ابزارهای کاتالوگ">
                        <div>
                            <span>CURATED PRODUCTS</span>
                            <strong>{{ number_format($products->total()) }} محصول</strong>

                            @if(request('search'))
                                <small>برای «{{ request('search') }}»</small>
                            @endif
                        </div>

                        <form action="{{ route('shop.index') }}" method="GET">
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif

                            @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif

                            @if(request('min_price') !== null)
                                <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                            @endif

                            @if(request('max_price') !== null)
                                <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                            @endif

                            <label class="sr-only" for="shop-sort">مرتب‌سازی</label>
                            <select id="shop-sort" name="sort" onchange="this.form.submit()">
                                @foreach([
                                    'latest' => 'جدیدترین',
                                    'price_asc' => 'ارزان‌ترین',
                                    'price_desc' => 'گران‌ترین',
                                    'popular' => 'محبوب‌ترین',
                                    'rating' => 'بالاترین امتیاز',
                                ] as $value => $label)
                                    <option value="{{ $value }}" @selected(request('sort', 'latest') === $value)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    @if(request()->filled('category'))
                        @php($activeCategory = $categories->firstWhere('slug', request('category')))

                        <div class="farzin-active-filter">
                            <span>فیلتر فعال:</span>
                            <b>{{ $activeCategory?->name ?? request('category') }}</b>
                            <a href="{{ route('shop.index') }}">حذف</a>
                        </div>
                    @endif

                    @if($products->isNotEmpty())
                        <div class="farzin-product-grid--shop">
                            @foreach($products as $product)
                                @include('partials.product_card', ['product' => $product])
                            @endforeach
                        </div>

                        @if($products->hasPages())
                            <nav class="farzin-pagination" aria-label="صفحه‌بندی محصولات">
                                {{ $products->withQueryString()->links() }}
                            </nav>
                        @endif
                    @else
                        <div class="farzin-empty-state farzin-empty-state--large">
                            <div class="farzin-empty-state__icon" aria-hidden="true">—</div>
                            <h2>محصولی پیدا نشد</h2>
                            <p>عبارت جستجو یا فیلترها را کمی تغییر بده و دوباره امتحان کن.</p>
                            <a href="{{ route('shop.index') }}">مشاهده همه محصولات</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
