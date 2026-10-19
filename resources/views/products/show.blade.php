@extends('layouts.storefront')

@section('title', $product->meta_title ?: $product->name . ' | فرزین')
@section('meta_description', $product->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($product->short_description ?: $product->description ?: 'خرید ' . $product->name . ' از فروشگاه فرزین.'), 155))

@php
    $productImages = $product->images
        ->map(fn ($image) => asset('storage/' . $image->image))
        ->values()
        ->all();

    if (empty($productImages) && $product->primaryImage) {
        $productImages = [asset('storage/' . $product->primaryImage->image)];
    }

    // Prices are stored and displayed by this storefront in toman. Product structured data requires an ISO currency,
    // so the Offer value is converted to Iranian rial (10 toman = 1 rial? No: 1 toman = 10 rial).
    $productSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        '@id' => route('products.show', $product->slug) . '#product',
        'name' => $product->name,
        'description' => \Illuminate\Support\Str::limit(strip_tags($product->short_description ?: $product->description ?: $product->name), 500),
        'sku' => $product->sku ?: null,
        'image' => $productImages,
        'brand' => $product->brand ? ['@type' => 'Brand', 'name' => $product->brand] : null,
        'offers' => [
            '@type' => 'Offer',
            'url' => route('products.show', $product->slug),
            'priceCurrency' => 'IRR',
            'price' => (string) ((int) $product->price * 10),
            'availability' => $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'itemCondition' => 'https://schema.org/NewCondition',
        ],
        'aggregateRating' => $reviewCount > 0 ? [
            '@type' => 'AggregateRating',
            'ratingValue' => (string) $reviewAverage,
            'reviewCount' => (string) $reviewCount,
        ] : null,
    ];

    $productSchema = array_filter($productSchema, fn ($value) => $value !== null);
@endphp

@push('head')
    <link rel="canonical" href="{{ $product->canonical_url ?: route('products.show', $product->slug) }}">
    <meta property="og:type" content="product">
    <meta property="og:title" content="{{ $product->meta_title ?: $product->name }}">
    <meta property="og:description" content="{{ $product->meta_description ?: $product->short_description }}">
    @if ($product->noindex)
        <meta name="robots" content="noindex,follow">
    @endif
    <script type="application/ld+json">@json($productSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
@endpush

@section('content')
    <div class="fz-container">
        <nav class="fz-breadcrumb" aria-label="مسیر صفحه">
            <a href="{{ route('home') }}">خانه</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('shop.index') }}">فروشگاه</a>
            @if ($product->category)
                <span aria-hidden="true">/</span>
                <a href="{{ route('categories.show', $product->category->slug) }}">{{ $product->category->name }}</a>
            @endif
            <span aria-hidden="true">/</span>
            <span aria-current="page">{{ $product->name }}</span>
        </nav>

        <section class="fz-detail" aria-labelledby="product-title">
            <div class="fz-detail-gallery">
                <div class="fz-detail-img">
                    @php
                        $mainImage = $product->primaryImage ?: $product->images->first();
                    @endphp
                    @if ($mainImage)
                        <img id="fz-main-product-image"
                             src="{{ asset('storage/' . $mainImage->image) }}"
                             alt="{{ $mainImage->alt ?: $product->name }}"
                             fetchpriority="high"
                             decoding="async"
                             data-product-image>
                    @else
                        <div class="fz-product-placeholder" role="img" aria-label="تصویر محصول موجود نیست">FARZIN</div>
                    @endif
                </div>

                @if ($product->images->count() > 1)
                    <div class="fz-product-thumbs" aria-label="انتخاب تصویر محصول">
                        @foreach ($product->images as $image)
                            @php $isMainImage = $mainImage && $image->id === $mainImage->id; @endphp
                            <button type="button"
                                    class="fz-product-thumb {{ $isMainImage ? 'is-active' : '' }}"
                                    data-product-thumb
                                    data-image="{{ asset('storage/' . $image->image) }}"
                                    data-alt="{{ $image->alt ?: $product->name }}"
                                    aria-label="نمایش تصویر {{ $loop->iteration }}"
                                    aria-pressed="{{ $isMainImage ? 'true' : 'false' }}">
                                <img src="{{ asset('storage/' . $image->image) }}"
                                     alt=""
                                     loading="lazy"
                                     decoding="async">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="fz-detail-copy">
                <span class="fz-kicker">FARZIN / PRODUCT</span>
                <p class="fz-brand">{{ $product->brand ?: ($product->category?->name ?: 'محصول فرزین') }}</p>
                <h1 id="product-title">{{ $product->name }}</h1>

                @if ($product->sku)
                    <p class="fz-product-sku">کد محصول: <span dir="ltr">{{ $product->sku }}</span></p>
                @endif

                @if ($product->short_description)
                    <p class="fz-detail-lead">{{ $product->short_description }}</p>
                @endif

                <div class="fz-product-rating fz-product-rating--detail" aria-label="امتیاز محصول">
                    <span aria-hidden="true">★</span>
                    <strong>{{ number_format((float) $reviewAverage, 1) }}</strong>
                    <span>{{ number_format($reviewCount) }} دیدگاه تأییدشده</span>
                </div>

                <div class="fz-detail-price">
                    <strong>{{ number_format($product->price) }} <small>تومان</small></strong>
                    @if ($product->old_price && $product->old_price > $product->price)
                        <span class="fz-old">{{ number_format($product->old_price) }} تومان</span>
                    @endif
                </div>

                <p class="fz-stock {{ $product->stock < 1 ? 'is-out' : '' }}">
                    <span aria-hidden="true">{{ $product->stock > 0 ? '●' : '○' }}</span>
                    {{ $product->stock > 0 ? 'موجود در انبار' : 'در حال حاضر ناموجود' }}
                    @if ($product->stock > 0 && $product->stock <= 5)
                        <span> · موجودی محدود</span>
                    @endif
                </p>

                @if ($product->stock > 0)
                    @auth
                        @if (auth()->user()->isCustomer())
                            <form method="POST"
                                  action="{{ route('customer.cart.add') }}"
                                  class="fz-detail-buy"
                                  data-cart-add>
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <label class="fz-form-field" for="quantity">
                                    <span>تعداد</span>
                                    <input id="quantity" class="fz-input" type="number" name="quantity"
                                           value="{{ old('quantity', 1) }}" min="1" max="{{ min(99, $product->stock) }}" required>
                                </label>
                                <button class="fz-btn" type="submit" data-cart-add-button>افزودن به سبد خرید</button>
                            </form>
                        @elseif (auth()->user()->isAdmin())
                            <p class="fz-product-note">برای ثبت خرید، با یک حساب مشتری وارد شو.</p>
                        @endif
                    @else
                        <a class="fz-btn" href="{{ route('login') }}">ورود برای خرید</a>
                        <p class="fz-product-note">برای ثبت سفارش، ابتدا وارد حساب مشتری شو.</p>
                    @endauth
                @else
                    <button class="fz-btn" type="button" disabled>ناموجود</button>
                @endif

                @auth
                    @if (auth()->user()->isCustomer())
                        <form method="POST" action="{{ route('customer.wishlist.toggle', $product->id) }}" class="fz-wishlist-form">
                            @csrf
                            <button type="submit" class="fz-btn light">
                                {{ $isWishlisted ? 'حذف از علاقه‌مندی‌ها' : 'افزودن به علاقه‌مندی‌ها' }}
                                <span aria-hidden="true">{{ $isWishlisted ? '♥' : '♡' }}</span>
                            </button>
                        </form>
                    @endif
                @else
                    <p class="fz-product-note"><a href="{{ route('login') }}">ورود</a> برای ذخیره محصول در علاقه‌مندی‌ها.</p>
                @endauth

                @if ($product->description)
                    <section class="fz-product-description" aria-labelledby="product-description-title">
                        <h2 id="product-description-title">درباره محصول</h2>
                        <div>{!! nl2br(e($product->description)) !!}</div>
                    </section>
                @endif
            </div>
        </section>

        <section class="fz-section fz-reviews" aria-labelledby="reviews-title">
            <div class="fz-head">
                <div>
                    <span class="fz-kicker">تجربه خریداران</span>
                    <h2 class="fz-h2" id="reviews-title">دیدگاه‌ها و امتیازها</h2>
                    <p class="fz-desc">فقط دیدگاه‌های تأییدشده در این بخش نمایش داده می‌شوند.</p>
                </div>
                <span class="fz-review-count">{{ number_format($reviewCount) }} دیدگاه</span>
            </div>

            @if ($product->reviews->isNotEmpty())
                <div class="fz-review-grid">
                    @foreach ($product->reviews as $review)
                        <article class="fz-review-card" data-reveal-item>
                            <div class="fz-review-card__head">
                                <strong>{{ $review->user?->name ?: 'خریدار فرزین' }}</strong>
                                <span class="fz-product-rating" aria-label="امتیاز {{ $review->rating }} از ۵">
                                    <span aria-hidden="true">★</span>
                                    {{ (int) $review->rating }}/۵
                                </span>
                            </div>
                            @if ($review->title)
                                <h3>{{ $review->title }}</h3>
                            @endif
                            <p>{{ $review->body }}</p>
                        </article>
                    @endforeach
                </div>
            @else
                <p class="fz-empty">هنوز دیدگاه تأییدشده‌ای برای این محصول ثبت نشده است.</p>
            @endif

            @if ($canReview)
                <div class="fz-panel fz-review-form-panel">
                    <h3>تجربه‌ات را با خریداران دیگر به اشتراک بگذار</h3>
                    <p class="fz-desc">دیدگاه پس از بررسی مدیر در سایت نمایش داده می‌شود.</p>
                    <form method="POST" action="{{ route('customer.reviews.store') }}" class="fz-review-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <label class="fz-form-field" for="review-rating">
                            <span>امتیاز از ۱ تا ۵</span>
                            <select id="review-rating" class="fz-select" name="rating" required>
                                @foreach ([5 => '۵ - عالی', 4 => '۴ - خوب', 3 => '۳ - متوسط', 2 => '۲ - ضعیف', 1 => '۱ - خیلی ضعیف'] as $rating => $label)
                                    <option value="{{ $rating }}" @selected(old('rating', 5) == $rating)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="fz-form-field" for="review-title">
                            <span>عنوان (اختیاری)</span>
                            <input id="review-title" class="fz-input" name="title" value="{{ old('title') }}" maxlength="255">
                        </label>

                        <label class="fz-form-field fz-form-field--full" for="review-body">
                            <span>متن دیدگاه</span>
                            <textarea id="review-body" class="fz-textarea" name="body" rows="4" minlength="5" maxlength="5000" required>{{ old('body') }}</textarea>
                        </label>

                        <button class="fz-btn" type="submit">ارسال دیدگاه برای بررسی</button>
                    </form>
                </div>
            @endif
        </section>

        @if ($relatedProducts->isNotEmpty())
            <section class="fz-section" aria-labelledby="related-products-title">
                <div class="fz-head">
                    <div>
                        <span class="fz-kicker">برای بررسی بیشتر</span>
                        <h2 class="fz-h2" id="related-products-title">محصولات مرتبط</h2>
                    </div>
                    <a href="{{ route('shop.index') }}">همه محصولات ←</a>
                </div>
                <div class="fz-products">
                    @foreach ($relatedProducts as $relatedProduct)
                        @include('storefront.partials.product-card', ['product' => $relatedProduct])
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
