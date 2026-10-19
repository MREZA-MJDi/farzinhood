@extends('layouts.app')

@section('title', $product->meta_title ?: $product->name . ' | فرزین')
@section('meta_description', $product->meta_description ?: ($product->short_description ?: $product->name . ' در فروشگاه فرزین.'))

@push('head')
    @unless($product->noindex)
        <meta name="robots" content="index,follow">
    @else
        <meta name="robots" content="noindex,nofollow">
    @endunless
    <script type="application/ld+json">@json([
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product->name,
        'sku' => $product->sku,
        'description' => $product->short_description ?: $product->description,
        'image' => $product->primaryImage?->image ? asset('storage/' . ltrim($product->primaryImage->image, '/')) : null,
        'offers' => [
            '@type' => 'Offer',
            'priceCurrency' => 'IRR',
            'price' => $product->price,
            'availability' => $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'url' => route('products.show', ['product' => $product->slug]),
        ],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)</script>
@endpush

@section('content')
<section class="fh-page fh-product-detail">
    <div class="fh-page__inner">
        <nav class="fh-breadcrumb" aria-label="مسیر صفحه">
            <a href="{{ route('home') }}">خانه</a><span>/</span>
            <a href="{{ route('categories.show', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a><span>/</span>
            <span>{{ $product->name }}</span>
        </nav>
        <div class="fh-product-detail__grid">
            <div class="fh-product-detail__gallery">
                <div class="fh-product-detail__image">
                    @if ($product->primaryImage?->image)
                        <img src="{{ asset('storage/' . ltrim($product->primaryImage->image, '/')) }}" alt="{{ $product->primaryImage->alt ?: $product->name }}">
                    @else
                        <span class="fh-product-card__placeholder" aria-hidden="true">F</span>
                    @endif
                </div>
                @if ($product->images->count() > 1)
                    <div class="fh-product-detail__thumbs">
                        @foreach ($product->images as $image)
                            <img src="{{ asset('storage/' . ltrim($image->image, '/')) }}" alt="{{ $image->alt ?: $product->name }}" loading="lazy">
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="fh-product-detail__info">
                <p class="fh-eyebrow fh-eyebrow--dark">FARZIN / PRODUCT</p>
                <p class="fh-product-detail__category">{{ $product->category->name }} @if($product->brand) · {{ $product->brand }} @endif</p>
                <h1>{{ $product->name }}</h1>
                <p class="fh-product-detail__sku">کد محصول: {{ $product->sku }}</p>
                <div class="fh-product-detail__price">{{ number_format((float) $product->price) }} <small>تومان</small></div>
                @if ($product->old_price && $product->old_price > $product->price)
                    <p class="fh-product-detail__old-price">{{ number_format((float) $product->old_price) }} تومان</p>
                @endif
                <p class="fh-product-detail__stock">{{ $product->stock > 0 ? 'موجود در انبار' : 'در حال حاضر ناموجود' }}</p>
                @if ($product->short_description)<p class="fh-product-detail__summary">{{ $product->short_description }}</p>@endif
                @if (auth()->check() && auth()->user()->isCustomer())
                    <form class="fh-buy-form" action="{{ route('customer.cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <label for="quantity">تعداد</label>
                        <input id="quantity" type="number" name="quantity" min="1" max="{{ max(1, $product->stock) }}" value="1" @disabled($product->stock < 1)>
                        <button class="fh-button fh-button--dark" type="submit" @disabled($product->stock < 1)>افزودن به سبد خرید <span aria-hidden="true">↗</span></button>
                    </form>
                    <form action="{{ route('customer.wishlist.toggle', ['product' => $product->id]) }}" method="POST" class="fh-wishlist-form">
                        @csrf
                        <button type="submit" class="fh-text-link">{{ $isWishlisted ? 'حذف از علاقه‌مندی‌ها' : 'افزودن به علاقه‌مندی‌ها' }}</button>
                    </form>
                @else
                    <a class="fh-button fh-button--dark" href="{{ route('login') }}">برای خرید وارد شوید <span aria-hidden="true">↗</span></a>
                @endif
                @error('quantity')<p class="fh-form-error">{{ $message }}</p>@enderror
                <div class="fh-product-detail__description"><h2>درباره محصول</h2><p>{{ $product->description ?: ($product->short_description ?: 'اطلاعات تکمیلی این محصول به‌زودی درج می‌شود.') }}</p></div>
                <div class="fh-product-detail__reviews">
                    <h2>دیدگاه مشتریان</h2>
                    <p>امتیاز میانگین: {{ $reviewAverage ?: '—' }} از ۵ · {{ $reviewCount }} دیدگاه تاییدشده</p>
                    @forelse ($product->reviews as $review)
                        <article><strong>{{ $review->user?->name ?: 'مشتری فرزین' }}</strong><span> · {{ $review->rating }}/۵</span><p>{{ $review->body }}</p></article>
                    @empty
                        <p>هنوز دیدگاه تاییدشده‌ای برای این محصول ثبت نشده است.</p>
                    @endforelse
                    @if ($canReview)
                        <form action="{{ route('customer.reviews.store') }}" method="POST" class="fh-review-form">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <label>امتیاز <select name="rating" required><option value="5">۵</option><option value="4">۴</option><option value="3">۳</option><option value="2">۲</option><option value="1">۱</option></select></label>
                            <label>دیدگاه <textarea name="body" rows="4" required>{{ old('body') }}</textarea></label>
                            <button class="fh-button fh-button--dark" type="submit">ثبت دیدگاه</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        @if ($relatedProducts->isNotEmpty())
            <section class="fh-related-products"><div class="fh-section__heading"><div><p class="fh-eyebrow fh-eyebrow--dark">MORE TO EXPLORE</p><h2>محصولات مرتبط</h2></div><a class="fh-text-link" href="{{ route('shop.index') }}">فروشگاه <span aria-hidden="true">↗</span></a></div>
                <div class="fh-product-grid">
                    @foreach ($relatedProducts as $related)
                        <a class="fh-product-card fh-product-card--light" href="{{ route('products.show', ['product' => $related->slug]) }}">
                            <div class="fh-product-card__media">@if($related->primaryImage?->image)<img src="{{ asset('storage/' . ltrim($related->primaryImage->image, '/')) }}" alt="{{ $related->primaryImage->alt ?: $related->name }}" loading="lazy">@else<span class="fh-product-card__placeholder">F</span>@endif<span class="fh-product-card__arrow">↗</span></div>
                            <h3>{{ $related->name }}</h3><p class="fh-product-card__price">{{ number_format((float) $related->price) }} <small>تومان</small></p>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</section>
@endsection
