@extends('layouts.storefront')
@section('title', 'فروشگاه فرزین')
@section('content')
<section class="catalog-hero"><span class="farzin-eyebrow">THE FARZIN STORE</span><h1>فروشگاه</h1><p>انتخاب‌ها را مرور کن؛ محصول مناسب تو همین‌جاست.</p></section>
<section class="store-section catalog-layout">
    <aside class="catalog-filters">
        <form action="{{ route('shop.index') }}" method="GET">
            <label class="catalog-field"><span>جست‌وجو</span><input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="نام، برند یا کد محصول"></label>
            <label class="catalog-field"><span>دسته‌بندی</span><select name="category"><option value="">همه دسته‌بندی‌ها</option>@foreach ($categories as $category)<option value="{{ $category->slug }}" @selected(($filters['category'] ?? '') === $category->slug)>{{ $category->name }}</option>@endforeach</select></label>
            <div class="catalog-field"><span>محدوده قیمت (تومان)</span><div class="catalog-price-fields"><input type="number" min="0" name="min_price" value="{{ $filters['min_price'] ?? '' }}" placeholder="{{ $priceMin }}"><input type="number" min="0" name="max_price" value="{{ $filters['max_price'] ?? '' }}" placeholder="{{ $priceMax }}"></div></div>
            <label class="catalog-field"><span>مرتب‌سازی</span><select name="sort"><option value="latest" @selected(($filters['sort'] ?? 'latest') === 'latest')>جدیدترین</option><option value="price_asc" @selected(($filters['sort'] ?? '') === 'price_asc')>ارزان‌ترین</option><option value="price_desc" @selected(($filters['sort'] ?? '') === 'price_desc')>گران‌ترین</option><option value="popular" @selected(($filters['sort'] ?? '') === 'popular')>محبوب‌ترین</option><option value="rating" @selected(($filters['sort'] ?? '') === 'rating')>بالاترین امتیاز</option></select></label>
            <button class="farzin-button farzin-button--dark catalog-submit" type="submit">اعمال فیلترها</button>
            <a class="catalog-reset" href="{{ route('shop.index') }}">پاک‌کردن فیلترها</a>
        </form>
    </aside>
    <div class="catalog-results">
        <div class="catalog-results__heading"><div><h2>همه محصولات</h2><p>{{ number_format($products->total()) }} محصول پیدا شد</p></div><span>{{ $products->currentPage() }} / {{ max(1, $products->lastPage()) }}</span></div>
        <div class="store-product-grid">
            @forelse ($products as $product)
                <x-storefront.product-card :product="$product" />
            @empty
                <div class="store-empty"><strong>محصولی با این فیلترها پیدا نشد.</strong><p>فیلترها را تغییر بده یا دوباره همه محصولات را ببین.</p><a href="{{ route('shop.index') }}">پاک‌کردن فیلترها</a></div>
            @endforelse
        </div>
        <div class="catalog-pagination">{{ $products->links() }}</div>
    </div>
</section>
@endsection