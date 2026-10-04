@extends('layouts.app')

@section('title', ($product->meta_title ?: $product->name) . ' | فرزین')
@section('meta_description', $product->meta_description ?: ($product->short_description ?: $product->name))
@section('canonical_url', $product->canonical_url ?: url()->current())
@section('og_type', 'product')
@section('og_image', $product->primaryImage ? asset('storage/' . $product->primaryImage->image) : asset('images/brand/logo.png'))
@section('meta_robots', 'index, follow')

@push('head')
    @php
        $productImage = $product->primaryImage
            ? asset('storage/' . $product->primaryImage->image)
            : asset('images/brand/logo.png');

        $productSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => $product->meta_description ?: ($product->short_description ?: $product->name),
            'sku' => $product->sku,
            'image' => $productImage,
            'brand' => $product->brand ? ['@type' => 'Brand', 'name' => $product->brand] : null,
            'offers' => [
                '@type' => 'Offer',
                'url' => url()->current(),
                'priceCurrency' => 'IRR',
                'price' => (string) ($product->price * 10),
                'availability' => $product->stock > 0
                    ? 'https://schema.org/InStock'
                    : 'https://schema.org/OutOfStock',
            ],
        ];

        if ($reviewCount > 0) {
            $productSchema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => (string) $reviewAverage,
                'reviewCount' => $reviewCount,
            ];
        }
    @endphp

    <script type="application/ld+json">
        @json($productSchema)
    </script>
@endpush

@section('content')
<div class="farzin-product storefront-content product-v2">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <nav class="flex flex-wrap items-center gap-2 text-[10px] font-bold text-[var(--color-text-muted)]" aria-label="مسیر صفحه">
                <a class="transition hover:text-[var(--color-accent-600)]" href="{{ route('home') }}">خانه</a>
                <span aria-hidden="true">/</span>
                <a class="transition hover:text-[var(--color-accent-600)]" href="{{ route('shop.index') }}">فروشگاه</a>
                @if($product->category)
                    <span aria-hidden="true">/</span>
                    <a class="transition hover:text-[var(--color-accent-600)]" href="{{ route('categories.show', $product->category) }}">
                        {{ $product->category->name }}
                    </a>
                @endif
                <span aria-hidden="true">/</span>
                <span class="max-w-[220px] truncate text-[var(--color-text-primary)]" aria-current="page">{{ $product->name }}</span>
            </nav>

            @include('partials.back-link', [
                'href' => route('shop.index'),
                'label' => 'بازگشت به فروشگاه',
            ])
        </div>

        <section class="product-v2__layout mt-4">
            <section class="product-v2__visual" aria-label="گالری محصول" data-product-gallery>
                <div class="product-gallery-v2__stage">
                    <div class="product-gallery-v2__meta">
                        <span>FARZIN / VISUAL</span>
                        <span><b data-gallery-current>01</b> / {{ str_pad((string) max(1, $product->images->count()), 2, '0', STR_PAD_LEFT) }}</span>
                    </div>

                    <button
                        type="button"
                        class="product-gallery-v2__main-button"
                        data-gallery-open
                        aria-label="بزرگ‌نمایی تصویر محصول"
                        @if(!$product->primaryImage && $product->images->isEmpty()) disabled @endif
                    >
                        @if($product->primaryImage)
                            <img
                                src="{{ asset('storage/' . $product->primaryImage->image) }}"
                                alt="{{ $product->primaryImage->alt ?: $product->name }}"
                                data-gallery-main
                                loading="eager"
                                fetchpriority="high"
                                decoding="async"
                            >
                        @elseif($product->images->first())
                            <img
                                src="{{ asset('storage/' . $product->images->first()->image) }}"
                                alt="{{ $product->images->first()->alt ?: $product->name }}"
                                data-gallery-main
                                loading="eager"
                                fetchpriority="high"
                                decoding="async"
                            >
                        @else
                            <div class="absolute inset-0 grid place-items-center text-[var(--color-text-soft)]">
                                تصویر محصول ثبت نشده است
                            </div>
                        @endif
                    </button>

                    @if($product->discount > 0)
                        <span class="product-gallery-v2__sale">{{ $product->discount }}٪ تخفیف</span>
                    @elseif($product->is_featured)
                        <span class="product-gallery-v2__sale">انتخاب ویژه</span>
                    @endif
                </div>

                @if($product->images->count() > 1)
                    <div class="product-gallery-v2__thumbs" role="list" aria-label="تصاویر محصول">
                        @foreach($product->images as $image)
                            <button
                                type="button"
                                class="product-gallery-v2__thumb {{ ($product->primaryImage?->id !== null ? $product->primaryImage->id === $image->id : $loop->first) ? 'is-active' : '' }}"
                                data-gallery-thumb
                                data-gallery-src="{{ asset('storage/' . $image->image) }}"
                                data-gallery-alt="{{ $image->alt ?: $product->name }}"
                                data-gallery-index="{{ $loop->iteration }}"
                                aria-label="تصویر {{ $loop->iteration }}"
                                aria-pressed="{{ $loop->first || ($product->primaryImage?->id === $image->id) ? 'true' : 'false' }}"
                            >
                                <img
                                    src="{{ asset('storage/' . $image->image) }}"
                                    alt=""
                                    loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                                    decoding="async"
                                >
                            </button>
                        @endforeach
                    </div>
                @endif
            </section>

            <aside id="product-purchase" class="product-v2__purchase" aria-labelledby="product-title">
                <div class="product-v2__purchase-inner">
                    <div class="product-v2__identity">
                        <span class="product-v2__eyebrow">FARZIN / PRODUCT</span>
                        @if($product->brand)
                            <span>{{ $product->brand }}</span>
                        @endif
                        @if($product->category)
                            <a href="{{ route('categories.show', $product->category) }}">{{ $product->category->name }}</a>
                        @endif
                        @if($product->sku)
                            <span dir="ltr">SKU / {{ $product->sku }}</span>
                        @endif
                    </div>

                    <div class="product-v2__title">
                        <h1 id="product-title">{{ $product->name }}</h1>
                        <p>{{ $product->short_description ?: 'جزئیات محصول را بررسی کن و انتخابت را با خیال راحت کامل کن.' }}</p>
                    </div>

                    @if($reviewCount > 0)
                        <div class="product-v2__rating" aria-label="امتیاز {{ number_format($reviewAverage, 1) }} از ۵">
                            <strong>★ {{ number_format($reviewAverage, 1) }}</strong>
                            <span>{{ number_format($reviewCount) }} نظر تاییدشده</span>
                        </div>
                    @endif

                    <div class="product-v2__pricebox">
                        <div class="product-v2__price-row">
                            <div class="product-v2__price">
                                <strong>{{ number_format($product->price) }}</strong>
                                <span>تومان</span>
                                @if($product->old_price && $product->old_price > $product->price)
                                    <span class="product-v2__old">{{ number_format($product->old_price) }} تومان</span>
                                @endif
                            </div>

                            <span class="product-v2__stock {{ $product->stock < 1 ? 'is-out' : '' }}">
                                <i aria-hidden="true"></i>
                                {{ $product->stock > 0 ? 'موجود و آماده سفارش' : 'فعلاً ناموجود' }}
                            </span>
                        </div>
                    </div>

                    @if($product->stock > 0 && $product->is_active)
                        @if(auth()->check() && auth()->user()->isCustomer())
                            <form action="{{ route('customer.cart.add') }}" method="POST" class="mt-3">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <div class="product-v2__buy">
                                    <div class="product-v2__quantity" data-quantity-control data-max="{{ min($product->stock, 99) }}">
                                        <button type="button" data-quantity-action="decrease" aria-label="کاهش تعداد">−</button>
                                        <input
                                            type="number"
                                            name="quantity"
                                            value="1"
                                            min="1"
                                            max="{{ min($product->stock, 99) }}"
                                            inputmode="numeric"
                                            aria-label="تعداد"
                                            data-quantity-input
                                        >
                                        <button type="button" data-quantity-action="increase" aria-label="افزایش تعداد">+</button>
                                    </div>

                                    <button class="product-v2__add" type="submit">
                                        افزودن به سبد خرید
                                    </button>
                                </div>
                                <p class="product-v2__buy-note">قیمت نهایی در زمان ثبت سفارش دوباره از محصول خوانده می‌شود.</p>
                            </form>
                        @else
                            <a
                                class="product-v2__add flex items-center justify-center"
                                href="{{ route('login') }}"
                            >
                                ورود برای خرید
                            </a>
                            <p class="product-v2__buy-note">بعد از ورود، به همین مسیر برمی‌گردی و خرید را ادامه می‌دهی.</p>
                        @endif
                    @else
                        <div class="mt-3 rounded-xl border border-[#eed0c8] bg-[var(--color-danger-surface)] p-3 text-xs font-bold text-[var(--color-danger-ink)]">
                            این محصول در حال حاضر قابل سفارش نیست.
                        </div>
                    @endif

                    @if(auth()->check() && auth()->user()->isCustomer())
                        <form action="{{ route('customer.wishlist.toggle', $product) }}" method="POST">
                            @csrf
                            <button type="submit" class="product-v2__wish">
                                {{ $isWishlisted ? '♥ حذف از علاقه‌مندی‌ها' : '♡ ذخیره در علاقه‌مندی‌ها' }}
                            </button>
                        </form>
                    @endif

                    <div class="product-v2__benefits">
                        <div class="product-v2__benefit">
                            <strong>قیمت شفاف</strong>
                            <span>قیمت فعلی محصول مبنای خرید است.</span>
                        </div>
                        <div class="product-v2__benefit">
                            <strong>موجودی واقعی</strong>
                            <span>موجودی هنگام ثبت سفارش دوباره بررسی می‌شود.</span>
                        </div>
                        <div class="product-v2__benefit">
                            <strong>پشتیبانی</strong>
                            <span>برای انتخاب بهتر می‌توانی با ما تماس بگیری.</span>
                        </div>
                    </div>
                </div>
            </aside>
        </section>

        <section class="product-v2__information">
            <div class="product-v2__information-head">
                <span class="product-v2__eyebrow">FARZIN / DETAILS</span>
                <h2>قبل از خرید، محصول را کامل بشناس.</h2>
            </div>

            <div class="product-v2__details">
                @if($product->description)
                    <details open>
                        <summary>
                            <span>توضیحات محصول</span>
                            <span aria-hidden="true">+</span>
                        </summary>
                        <div class="product-v2__details-content">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </details>
                @endif

                <details {{ !$product->description ? 'open' : '' }}>
                    <summary>
                        <span>مشخصات محصول</span>
                        <span aria-hidden="true">+</span>
                    </summary>
                    <div class="product-v2__details-content">
                        <dl class="grid gap-2 sm:grid-cols-2">
                            @if($product->brand)
                                <div><dt class="text-[10px] text-[var(--color-text-muted)]">برند</dt><dd class="font-bold">{{ $product->brand }}</dd></div>
                            @endif
                            @if($product->category)
                                <div><dt class="text-[10px] text-[var(--color-text-muted)]">دسته</dt><dd class="font-bold">{{ $product->category->name }}</dd></div>
                            @endif
                            @if($product->sku)
                                <div><dt class="text-[10px] text-[var(--color-text-muted)]">کد کالا</dt><dd class="font-mono font-bold" dir="ltr">{{ $product->sku }}</dd></div>
                            @endif
                            <div><dt class="text-[10px] text-[var(--color-text-muted)]">وضعیت</dt><dd class="font-bold">{{ $product->stock > 0 ? 'موجود' : 'ناموجود' }}</dd></div>
                        </dl>
                    </div>
                </details>

                <details>
                    <summary>
                        <span>راهنمای سفارش</span>
                        <span aria-hidden="true">+</span>
                    </summary>
                    <div class="product-v2__details-content">
                        محصول را بررسی کن، تعداد را انتخاب کن و به سبد خرید اضافه کن. موجودی و قیمت در مرحله ثبت سفارش دوباره کنترل می‌شوند.
                    </div>
                </details>
            </div>
        </section>

        <section class="product-v2__reviews">
            <div class="product-v2__reviews-head">
                <div>
                    <span class="product-v2__eyebrow">FARZIN / REVIEWS</span>
                    <h2 class="mt-1 text-xl font-black text-[var(--color-text-primary)] sm:text-2xl">تجربه خریداران</h2>
                </div>
                @if($reviewCount > 0)
                    <span class="rounded-full bg-[var(--color-warning-surface)] px-3 py-2 text-xs font-black text-[var(--color-warning-ink)]">
                        {{ number_format($reviewAverage, 1) }} / ۵
                    </span>
                @endif
            </div>

            @if($canReview)
                <div class="product-v2__review-form">
                    <strong class="text-sm font-black text-[var(--color-text-primary)]">نظر خودت را ثبت کن</strong>
                    <form action="{{ route('customer.reviews.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <label>
                            <span class="sr-only">امتیاز</span>
                            <select name="rating" required aria-label="امتیاز">
                                <option value="">امتیاز</option>
                                @for($rating = 5; $rating >= 1; $rating--)
                                    <option value="{{ $rating }}">{{ $rating }} از ۵</option>
                                @endfor
                            </select>
                        </label>
                        <label>
                            <span class="sr-only">عنوان</span>
                            <input type="text" name="title" maxlength="255" placeholder="عنوان نظر (اختیاری)">
                        </label>
                        <label>
                            <span class="sr-only">متن نظر</span>
                            <textarea name="body" rows="4" maxlength="5000" minlength="5" required placeholder="تجربه‌ات از محصول..."></textarea>
                        </label>
                        <button type="submit" class="product-v2__review-submit">ارسال برای بررسی</button>
                    </form>
                </div>
            @elseif(auth()->check() && auth()->user()->isCustomer())
                <p class="mt-3 text-xs text-[var(--color-text-muted)]">برای ثبت نظر، باید این محصول را در یک سفارش پرداخت‌شده خریداری کرده باشی.</p>
            @endif

            <div class="product-v2__reviews-grid">
                @forelse($product->reviews as $review)
                    <article class="product-v2__review">
                        <div class="product-v2__review-meta">
                            <div>
                                <div class="product-v2__review-user">{{ $review->user?->name ?: 'خریدار' }}</div>
                                <span class="product-v2__review-date">{{ $review->created_at?->format('Y/m/d') }}</span>
                            </div>
                            <span class="product-v2__review-rating">★ {{ $review->rating }}</span>
                        </div>
                        @if($review->title)
                            <h3>{{ $review->title }}</h3>
                        @endif
                        <p>{{ $review->body }}</p>
                    </article>
                @empty
                    <div class="store-empty md:col-span-2">
                        <div class="store-empty__icon">◎</div>
                        <h2>هنوز نظری ثبت نشده است.</h2>
                        <p>اولین خریدارانی که تجربه‌شان را ثبت کنند، به انتخاب بهتر بقیه کمک می‌کنند.</p>
                    </div>
                @endforelse
            </div>
        </section>

        @if($relatedProducts->isNotEmpty())
            <section class="product-v2__related">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <span class="product-v2__eyebrow">FARZIN / RELATED</span>
                        <h2 class="mt-1 text-xl font-black text-[var(--color-text-primary)] sm:text-2xl">انتخاب‌های نزدیک</h2>
                    </div>
                    @if($product->category)
                        <a class="store-page-back" href="{{ route('categories.show', $product->category) }}">مشاهده دسته‌بندی ←</a>
                    @endif
                </div>

                <div class="product-v2__related-grid">
                    @foreach($relatedProducts as $relatedProduct)
                        @include('partials.product_card', ['product' => $relatedProduct])
                    @endforeach
                </div>
            </section>
        @endif
    </div>

    <div class="farzin-product-mobile-cta" aria-label="خرید سریع محصول">
        <div class="farzin-livora__container">
            <div>
                <span>{{ $product->name }}</span>
                <strong>
                    {{ number_format($product->price) }}
                    <small>تومان</small>
                </strong>
            </div>

            @if($product->is_active && $product->stock > 0)
                <a href="#product-purchase">خرید</a>
            @else
                <span class="is-disabled">ناموجود</span>
            @endif
        </div>
    </div>
</div>

<dialog class="store-lightbox" data-gallery-dialog aria-label="نمایش بزرگ تصویر محصول">
    <div class="store-lightbox__panel">
        <button type="button" class="store-lightbox__close" data-gallery-close aria-label="بستن">×</button>
        <button type="button" class="store-lightbox__prev" data-gallery-prev aria-label="تصویر قبلی">‹</button>
        <img src="" alt="" data-gallery-lightbox-image>
        <button type="button" class="store-lightbox__next" data-gallery-next aria-label="تصویر بعدی">›</button>
        <span class="store-lightbox__counter" data-gallery-counter></span>
    </div>
</dialog>
@endsection
