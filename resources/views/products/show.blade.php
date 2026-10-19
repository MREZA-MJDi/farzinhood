@extends('layouts.storefront')
@section('title', ($product->meta_title ?: $product->name) . ' | فرزین')
@section('meta_description', $product->meta_description ?: $product->short_description ?: $product->description)
@push('head')
    <meta property="og:type" content="product">
    <meta property="og:title" content="{{ $product->meta_title ?: $product->name }}">
    <meta property="og:description" content="{{ $product->meta_description ?: $product->short_description ?: $product->description }}">
    <script type="application/ld+json">
        @json(['@context' => 'https://schema.org', '@type' => 'Product', 'name' => $product->name, 'description' => $product->short_description ?: $product->description, 'sku' => $product->sku, 'image' => $product->primaryImage?->image ? asset('storage/' . $product->primaryImage->image) : null, 'offers' => ['@type' => 'Offer', 'priceCurrency' => 'IRR', 'price' => (string) $product->price, 'availability' => $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock', 'url' => route('products.show', ['product' => $product->slug])]])
    </script>
@endpush
@section('content')
<section class="store-section product-page">
    <nav class="store-breadcrumb" aria-label="مسیر صفحه"><a href="{{ route('home') }}">خانه</a><span>←</span><a href="{{ route('shop.index') }}">فروشگاه</a><span>←</span><a href="{{ route('categories.show', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a></nav>
    <div class="product-v2__layout">
        <div class="product-v2__visual">
            <div class="product-gallery-v2__stage">
                @if ($product->primaryImage?->image)
                    <img src="{{ asset('storage/' . $product->primaryImage->image) }}" alt="{{ $product->primaryImage->alt ?: $product->name }}">
                @else
                    <div class="farzin-product-card__placeholder"><span>FARZIN</span></div>
                @endif
            </div>
            @if ($product->images->count() > 1)
                <div class="product-gallery-v2__thumbs">
                    @foreach ($product->images as $image)
                        <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $image->alt ?: $product->name }}" loading="lazy">
                    @endforeach
                </div>
            @endif
        </div>
        <div class="product-v2__purchase">
            <div class="product-v2__purchase-inner">
                <span class="farzin-eyebrow">{{ 'FARZIN / PRODUCT' }}</span>
                <p class="farzin-product-card__category"><a href="{{ route('categories.show', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a></p>
                <h1 class="product-v2__title">{{ $product->name }}</h1>
                @if ($product->rating)<p class="product-rating">★ {{ number_format((float) $product->rating, 1) }} <span>({{ number_format($reviewCount) }} دیدگاه تأییدشده)</span></p>@endif
                @if ($product->short_description)<p class="product-short-description">{{ $product->short_description }}</p>@endif
                <div class="product-detail-price">
                    @if ($product->old_price && $product->old_price > $product->price)<del>{{ number_format($product->old_price) }} تومان</del>@endif
                    <strong>{{ number_format($product->price) }} <small>تومان</small></strong>
                </div>
                <p class="product-stock {{ $product->stock < 1 ? 'is-out' : '' }}">{{ $product->stock > 0 ? 'موجود در انبار' : 'در حال حاضر ناموجود' }}</p>
                @if ($product->stock > 0)
                    @auth
                        @if (auth()->user()->isCustomer())
                            <form action="{{ route('customer.cart.add') }}" method="POST" class="product-add-form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <label for="product-quantity">تعداد</label>
                                <input id="product-quantity" type="number" name="quantity" min="1" max="{{ min(10, $product->stock) }}" value="1" required>
                                <button class="farzin-button farzin-button--dark" type="submit">افزودن به سبد خرید <span aria-hidden="true">←</span></button>
                            </form>
                        @else
                            <a class="farzin-button farzin-button--dark product-login-cta" href="{{ route('login') }}">برای خرید وارد شوید</a>
                        @endif
                    @else
                        <a class="farzin-button farzin-button--dark product-login-cta" href="{{ route('login') }}">ورود برای خرید</a>
                    @endauth
                @endif
                <div class="product-v2__benefits"><div><strong>انتخاب دقیق</strong><span>محصولات منتخب فرزین</span></div><div><strong>خرید مطمئن</strong><span>پیگیری از حساب کاربری</span></div></div>
            </div>
        </div>
    </div>
    <div class="product-description-block"><span class="farzin-eyebrow">PRODUCT DETAILS</span><h2>درباره محصول</h2><div>{{ $product->description ?: $product->short_description ?: 'توضیحات این محصول به‌زودی تکمیل می‌شود.' }}</div></div>
</section>
@if ($relatedProducts->isNotEmpty())
    <section class="store-section"><div class="store-section__heading"><div><span class="farzin-eyebrow">YOU MAY ALSO LIKE</span><h2>محصولات مرتبط</h2></div><a class="store-text-link" href="{{ route('shop.index') }}">همه محصولات <span aria-hidden="true">←</span></a></div><div class="store-product-grid">@foreach ($relatedProducts as $related)<x-storefront.product-card :product="$related" />@endforeach</div></section>
@endif
@endsection