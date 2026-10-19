@extends('layouts.app')

@section('title', 'فروشگاه فرزین | هود و سینک')
@section('meta_description', 'محصولات فعال فروشگاه فرزین را بر اساس دسته‌بندی، بازه قیمت و نام محصول جست‌وجو کنید.')

@section('content')
<section class="fh-page">
    <header class="fh-page__hero">
        <p class="fh-eyebrow fh-eyebrow--dark">FARZIN / SHOP</p>
        <h1>فروشگاه</h1>
        <p>مجموعه محصولات را بررسی و با توجه به نیاز آشپزخانه خود مقایسه کنید.</p>
    </header>

    <div class="fh-page__inner">
        <form class="fh-filter-panel" action="{{ route('shop.index') }}" method="GET">
            <label>جست‌وجو
                <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="نام محصول، برند یا کد کالا">
            </label>
            <label>دسته‌بندی
                <select name="category">
                    <option value="">تمام دسته‌بندی‌ها</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->slug }}" @selected(($filters['category'] ?? '') === $category->slug)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </label>
            <label>حداقل قیمت
                <input type="number" name="min_price" min="0" value="{{ $filters['min_price'] ?? '' }}" placeholder="{{ $priceMin ?: 'از قیمت' }}">
            </label>
            <label>حداکثر قیمت
                <input type="number" name="max_price" min="0" value="{{ $filters['max_price'] ?? '' }}" placeholder="{{ $priceMax ?: 'تا قیمت' }}">
            </label>
            <label>مرتب‌سازی
                <select name="sort">
                    @foreach (['latest' => 'جدیدترین', 'price_asc' => 'ارزان‌ترین', 'price_desc' => 'گران‌ترین', 'popular' => 'محبوب‌ترین', 'rating' => 'بالاترین امتیاز'] as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['sort'] ?? 'latest') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <label>تعداد در صفحه
                <select name="per_page">
                    @foreach ([12, 24, 36] as $size)
                        <option value="{{ $size }}" @selected((int) ($filters['per_page'] ?? 12) === $size)>{{ $size }}</option>
                    @endforeach
                </select>
            </label>
            <button class="fh-button fh-button--dark" type="submit">اعمال فیلتر <span aria-hidden="true">↗</span></button>
            <a class="fh-filter-panel__reset" href="{{ route('shop.index') }}">پاک‌کردن فیلترها</a>
        </form>

        <div class="fh-listing-heading">
            <p>{{ $products->total() }} محصول</p>
            <span>قیمت‌ها به تومان نمایش داده می‌شوند.</span>
        </div>

        @if ($products->count())
            <div class="fh-product-grid">
                @foreach ($products as $product)
                    <a class="fh-product-card fh-product-card--light" href="{{ route('products.show', ['product' => $product->slug]) }}">
                        <div class="fh-product-card__media">
                            @if ($product->primaryImage?->image)
                                <img src="{{ asset('storage/' . ltrim($product->primaryImage->image, '/')) }}" alt="{{ $product->primaryImage->alt ?: $product->name }}" loading="lazy">
                            @else
                                <span class="fh-product-card__placeholder" aria-hidden="true">F</span>
                            @endif
                            <span class="fh-product-card__arrow" aria-hidden="true">↗</span>
                        </div>
                        <div class="fh-product-card__meta"><span>{{ $product->category?->name }}</span><span>{{ $product->sku }}</span></div>
                        <h2>{{ $product->name }}</h2>
                        <p class="fh-product-card__price">{{ number_format((float) $product->price) }} <small>تومان</small></p>
                    </a>
                @endforeach
            </div>
            <div class="fh-pagination">{{ $products->links() }}</div>
        @else
            <div class="fh-empty"><strong>محصولی پیدا نشد</strong><p>فیلترها را تغییر دهید یا همه محصولات را مشاهده کنید.</p><a href="{{ route('shop.index') }}">بازگشت به فروشگاه ↗</a></div>
        @endif
    </div>
</section>
@endsection
