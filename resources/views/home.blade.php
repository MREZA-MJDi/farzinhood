@extends('layouts.app')

@section('title', 'فرزین | هود و سینک با انتخابی دقیق')
@section('meta_description', 'مجموعه‌ای منتخب از هود و سینک آشپزخانه با طراحی مدرن، مشخصات شفاف و تجربه خرید ساده از فرزین.')

@section('content')
<section class="fh-hero" data-home-hero aria-label="محصولات منتخب فرزین">
    <div class="fh-hero__grid">
        <div class="fh-hero__copy">
            <p class="fh-eyebrow"><span></span> FARZIN / KITCHEN COLLECTION</p>
            <h1>فرمِ درست.<br><em>کارکردِ ماندگار.</em></h1>
            <p class="fh-hero__intro">برای آشپزخانه‌ای که هر جزئیاتش حساب‌شده است؛ هود و سینک را با نگاهی دقیق‌تر انتخاب کنید.</p>

            <div class="fh-hero__slides" aria-live="polite">
                @forelse ($heroProducts as $product)
                    <article class="fh-hero__slide {{ $loop->first ? 'is-active' : '' }}" data-hero-slide aria-hidden="{{ $loop->first ? 'false' : 'true' }}">
                        <span class="fh-hero__product-index">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }} / {{ str_pad((string) $heroProducts->count(), 2, '0', STR_PAD_LEFT) }}</span>
                        <h2>{{ $product->name }}</h2>
                        <p>{{ $product->short_description ?: 'انتخابی کاربردی با توجه به فرم، متریال و فضای آشپزخانه شما.' }}</p>
                        <a class="fh-button fh-button--light" href="{{ route('products.show', ['product' => $product->slug]) }}">مشاهده جزئیات <span aria-hidden="true">↗</span></a>
                    </article>
                @empty
                    <article class="fh-hero__slide is-active" data-hero-slide aria-hidden="false">
                        <span class="fh-hero__product-index">01 / 01</span>
                        <h2>انتخابی تازه برای آشپزخانه</h2>
                        <p>محصولات به‌زودی در این بخش نمایش داده می‌شوند.</p>
                        <a class="fh-button fh-button--light" href="{{ route('shop.index') }}">مشاهده فروشگاه <span aria-hidden="true">↗</span></a>
                    </article>
                @endforelse
            </div>

            <div class="fh-hero__controls" aria-label="کنترل اسلایدها">
                <button type="button" class="fh-round-control" data-hero-prev aria-label="محصول قبلی">←</button>
                <button type="button" class="fh-round-control" data-hero-next aria-label="محصول بعدی">→</button>
                <span class="fh-hero__counter"><span data-hero-current>01</span><i></i>{{ str_pad((string) max(1, $heroProducts->count()), 2, '0', STR_PAD_LEFT) }}</span>
                <div class="farzin-home-hero__progress" aria-hidden="true"><span></span></div>
            </div>

            <div class="fh-hero__dots" role="tablist" aria-label="انتخاب محصول">
                @foreach ($heroProducts as $product)
                    <button type="button" data-hero-dot role="tab" aria-label="نمایش {{ $product->name }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}" class="{{ $loop->first ? 'is-active' : '' }}"></button>
                @endforeach
            </div>
        </div>

        <div class="fh-hero__visual" aria-hidden="true">
            <div class="fh-hero__frame fh-hero__frame--outer"></div>
            <div class="fh-hero__frame fh-hero__frame--inner"></div>
            @forelse ($heroProducts as $product)
                <div class="fh-hero__image {{ $loop->first ? 'is-active' : '' }}" data-hero-image>
                    @if ($product->primaryImage?->image)
                        <img src="{{ asset('storage/' . ltrim($product->primaryImage->image, '/')) }}" alt="" width="900" height="1000" @if (!$loop->first) loading="lazy" @else fetchpriority="high" @endif>
                    @else
                        <div class="fh-hero__image-placeholder"><span>F</span></div>
                    @endif
                    <span class="fh-hero__image-label">{{ $product->category?->name ?? 'FARZIN SELECT' }}</span>
                </div>
            @empty
                <div class="fh-hero__image is-active"><div class="fh-hero__image-placeholder"><span>F</span></div></div>
            @endforelse
            <div class="fh-hero__vertical-label">FUNCTION MEETS FORM · FARZIN</div>
        </div>
    </div>
    <div class="fh-hero__bottomline"><span>طراحی برای زندگی روزمره</span><span>کیفیت، بدون شلوغی</span><span>01 — 04 / COLLECTION</span></div>
</section>

<section class="fh-trust-strip" data-home-trust-strip aria-label="مزایای خرید از فرزین">
    <div><span>01</span><strong>انتخاب سنجیده</strong><small>مشخصات روشن و کاربردی</small></div>
    <div><span>02</span><strong>قیمت شفاف</strong><small>اطلاعات قابل بررسی</small></div>
    <div><span>03</span><strong>خرید امن</strong><small>فرآیند ثبت سفارش مطمئن</small></div>
    <div><span>04</span><strong>پشتیبانی فرزین</strong><small>همراه شما در مسیر خرید</small></div>
</section>

<section class="fh-section" aria-labelledby="fh-categories-title">
    <div class="fh-section__heading">
        <div><p class="fh-eyebrow fh-eyebrow--dark">EXPLORE BY CATEGORY</p><h2 id="fh-categories-title">شروع از دسته‌بندی</h2></div>
        <a class="fh-text-link" href="{{ route('shop.index') }}">تمام محصولات <span aria-hidden="true">↗</span></a>
    </div>
    @if ($categories->isNotEmpty())
        <div class="fh-category-grid">
            @foreach ($categories as $category)
                <a class="fh-category-card" href="{{ route('categories.show', ['category' => $category->slug]) }}">
                    <span class="fh-category-card__number">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="fh-category-card__name">{{ $category->name }}</span>
                    <span class="fh-category-card__arrow" aria-hidden="true">↗</span>
                    @if ($category->image)
                        <img src="{{ asset('storage/' . ltrim($category->image, '/')) }}" alt="" loading="lazy">
                    @else
                        <span class="fh-category-card__shape" aria-hidden="true"></span>
                    @endif
                </a>
            @endforeach
        </div>
    @else
        <p class="fh-empty">دسته‌بندی‌ها پس از ثبت در پنل مدیریت در این بخش نمایش داده می‌شوند.</p>
    @endif
</section>

<section class="fh-featured-section" aria-labelledby="fh-featured-title">
    <div class="fh-section fh-section--compact">
        <div class="fh-section__heading">
            <div><p class="fh-eyebrow">CURATED FOR YOU</p><h2 id="fh-featured-title">انتخاب‌های منتخب</h2></div>
            <a class="fh-text-link fh-text-link--light" href="{{ route('shop.index') }}">مشاهده فروشگاه <span aria-hidden="true">↗</span></a>
        </div>
        @if ($featuredProducts->isNotEmpty())
            <div class="fh-product-grid">
                @foreach ($featuredProducts as $product)
                    <a class="fh-product-card" href="{{ route('products.show', ['product' => $product->slug]) }}">
                        <div class="fh-product-card__media">
                            @if ($product->primaryImage?->image)
                                <img src="{{ asset('storage/' . ltrim($product->primaryImage->image, '/')) }}" alt="{{ $product->primaryImage->alt ?: $product->name }}" loading="lazy">
                            @else
                                <span class="fh-product-card__placeholder" aria-hidden="true">F</span>
                            @endif
                            <span class="fh-product-card__arrow" aria-hidden="true">↗</span>
                        </div>
                        <div class="fh-product-card__meta"><span>{{ $product->category?->name ?? 'FARZIN SELECT' }}</span><span>{{ $product->sku }}</span></div>
                        <h3>{{ $product->name }}</h3>
                        <p class="fh-product-card__price">{{ number_format((float) $product->price) }} <small>تومان</small></p>
                    </a>
                @endforeach
            </div>
        @else
            <p class="fh-empty fh-empty--dark">محصول منتخب فعلاً ثبت نشده است.</p>
        @endif
    </div>
</section>

<section class="fh-editorial">
    <div class="fh-editorial__mark" aria-hidden="true">F.</div>
    <div class="fh-editorial__copy"><p class="fh-eyebrow fh-eyebrow--dark">A CONSIDERED KITCHEN</p><h2>زیبایی در جزئیات است.<br><em>سادگی، انتخاب ماست.</em></h2><p>با مقایسه مشخصات و انتخاب متناسب با فضای خود، تصمیمی بگیرید که هر روز از آن راضی باشید.</p><a class="fh-button fh-button--dark" href="{{ route('shop.index') }}">کشف مجموعه <span aria-hidden="true">↗</span></a></div>
</section>

@if ($latestProducts->isNotEmpty())
    <section class="fh-section" aria-labelledby="fh-latest-title">
        <div class="fh-section__heading"><div><p class="fh-eyebrow fh-eyebrow--dark">JUST ARRIVED</p><h2 id="fh-latest-title">تازه‌های فروشگاه</h2></div><a class="fh-text-link" href="{{ route('shop.index') }}">همه محصولات <span aria-hidden="true">↗</span></a></div>
        <div class="fh-product-grid">
            @foreach ($latestProducts as $product)
                <a class="fh-product-card fh-product-card--light" href="{{ route('products.show', ['product' => $product->slug]) }}">
                    <div class="fh-product-card__media">
                        @if ($product->primaryImage?->image)
                            <img src="{{ asset('storage/' . ltrim($product->primaryImage->image, '/')) }}" alt="{{ $product->primaryImage->alt ?: $product->name }}" loading="lazy">
                        @else
                            <span class="fh-product-card__placeholder" aria-hidden="true">F</span>
                        @endif
                        <span class="fh-product-card__arrow" aria-hidden="true">↗</span>
                    </div>
                    <div class="fh-product-card__meta"><span>{{ $product->category?->name ?? 'FARZIN SELECT' }}</span><span>{{ $product->sku }}</span></div>
                    <h3>{{ $product->name }}</h3>
                    <p class="fh-product-card__price">{{ number_format((float) $product->price) }} <small>تومان</small></p>
                </a>
            @endforeach
        </div>
    </section>
@endif
@endsection
