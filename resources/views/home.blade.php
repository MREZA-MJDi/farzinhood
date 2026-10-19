@extends('layouts.app')
@section('title', 'فرزین | خانه‌ای با انتخاب‌های دقیق')
@section('meta_description', 'محصولات منتخب فرزین را کشف کنید؛ طراحی سنجیده، کیفیت قابل اتکا و تجربه‌ی خریدی ساده و مطمئن.')
@section('content')
<div class="farzin-home">
@include('components.home.hero', ['heroProducts' => $heroProducts])
<section class="farzin-section" id="home-categories" aria-labelledby="home-categories-title"><div class="farzin-container">
<header class="farzin-section__head"><div><p class="farzin-kicker">01 / دسته‌بندی‌ها</p><h2 id="home-categories-title" class="farzin-section__title">برای خانه‌ی <em>شما</em></h2></div><a class="farzin-text-link" href="{{ route('shop.index') }}">دیدن همه محصولات <span aria-hidden="true">↗</span></a></header>
@if($categories->isNotEmpty())<div class="farzin-category-grid">
@foreach($categories as $category)
@php $categoryImage = $category->image; $categoryImageUrl = filled($categoryImage) ? (str_starts_with($categoryImage,'http://') || str_starts_with($categoryImage,'https://') || str_starts_with($categoryImage,'/') ? $categoryImage : asset('storage/'.ltrim($categoryImage,'/'))) : null; @endphp
<a class="farzin-category" href="{{ route('categories.show', $category->slug) }}"><span class="farzin-category__number">{{ str_pad((string)$loop->iteration,2,'0',STR_PAD_LEFT) }}</span><span class="farzin-category__media">@if($categoryImageUrl)<img src="{{ $categoryImageUrl }}" alt="{{ $category->name }}" width="640" height="720" loading="lazy" decoding="async">@else<span class="farzin-category__placeholder" aria-hidden="true">F.</span>@endif</span><span class="farzin-category__meta"><strong>{{ $category->name }}</strong><span>{{ $category->active_products_count }} محصول <b aria-hidden="true">↗</b></span></span></a>
@endforeach
</div>@else<p class="farzin-empty">دسته‌بندی‌های محصولات به‌زودی نمایش داده می‌شوند.</p>@endif
</div></section>
<section class="farzin-section farzin-section--pearl" aria-labelledby="home-featured-title"><div class="farzin-container">
<header class="farzin-section__head"><div><p class="farzin-kicker">02 / منتخب فرزین</p><h2 id="home-featured-title" class="farzin-section__title">انتخاب‌های <em>ویژه</em></h2><p class="farzin-section__description">محصولاتی برای استفاده‌ی واقعی و سلیقه‌ی دقیق.</p></div><a class="farzin-text-link" href="{{ route('shop.index') }}">همه محصولات <span aria-hidden="true">↗</span></a></header>
@if($featuredProducts->isNotEmpty())<div class="farzin-product-grid">@foreach($featuredProducts as $product)
<x-product.product-card :id="$product->id" :name="$product->name" :image="$product->primaryImage?->image" :href="route('products.show',$product)" :price="$product->price" :old-price="$product->old_price" :discount="$product->discount" :brand="$product->brand" :meta="$product->short_description" :rating="$product->rating" :review-count="$product->review_count" :alt="$product->primaryImage?->alt" :is-wishlisted="$product->is_wishlisted ?? false" />
@endforeach</div>@else<p class="farzin-empty">هنوز محصول ویژه‌ای برای نمایش انتخاب نشده است.</p>@endif
</div></section>
<section class="farzin-section" aria-labelledby="home-latest-title"><div class="farzin-container">
<header class="farzin-section__head"><div><p class="farzin-kicker">03 / تازه‌ها</p><h2 id="home-latest-title" class="farzin-section__title">تازه‌واردهای <em>فروشگاه</em></h2></div><a class="farzin-text-link" href="{{ route('shop.index',['sort'=>'latest']) }}">کشف تازه‌ها <span aria-hidden="true">↗</span></a></header>
@if($latestProducts->isNotEmpty())<div class="farzin-product-grid">@foreach($latestProducts as $product)
<x-product.product-card :id="$product->id" :name="$product->name" :image="$product->primaryImage?->image" :href="route('products.show',$product)" :price="$product->price" :old-price="$product->old_price" :discount="$product->discount" :brand="$product->brand" :meta="$product->short_description" :rating="$product->rating" :review-count="$product->review_count" :alt="$product->primaryImage?->alt" :is-wishlisted="$product->is_wishlisted ?? false" />
@endforeach</div>@else<p class="farzin-empty">محصولات جدید به‌زودی در فروشگاه قرار می‌گیرند.</p>@endif
</div></section>

@foreach ([
    'hood' => [
        'eyebrow' => 'FARZIN / HOOD · 360°',
        'title' => 'تور ۳۶۰ درجه هودهای فرزین',
        'description' => 'طراحی، خطوط بدنه و جزئیات محصول را از چند زاویه بررسی کن.',
    ],
    'sink' => [
        'eyebrow' => 'FARZIN / SINK · 360°',
        'title' => 'تور ۳۶۰ درجه سینک‌های فرزین',
        'description' => 'با اسکرول، درگ یا کنترل‌های تصویر زاویه‌ی محصول را تغییر بده.',
    ],
] as $scene => $copy)
    @php $sceneFrames = $tourFrames->get($scene, collect()); @endphp
    @if ($sceneFrames->isNotEmpty())
        @php $firstFrame = $sceneFrames->first(); @endphp
        <section class="farzin-tour" data-home-motion-section data-product-tour data-tour-scene="{{ $scene }}"
                 data-tour-default-description="{{ $copy['description'] }}"
                 style="--tour-scroll-span: {{ max(0, ($sceneFrames->count() - 1) * 8) }}svh">
            <div class="farzin-container farzin-tour__sticky">
                <div class="farzin-tour__copy">
                    <p class="farzin-tour__eyebrow">{{ $copy['eyebrow'] }}</p>
                    <h2 class="farzin-tour__title">{{ $copy['title'] }}</h2>
                    <p class="farzin-tour__lead">{{ $copy['description'] }}</p>
                    <div class="farzin-tour__frame-copy" aria-live="polite" aria-atomic="true">
                        <strong class="farzin-tour__frame-title" data-tour-active-title>{{ $firstFrame->title ?: 'نمای محصول' }}</strong>
                        <p class="farzin-tour__frame-description" data-tour-active-description>{{ $firstFrame->description ?: $copy['description'] }}</p>
                    </div>
                    <p class="farzin-tour__footnote">اسکرول برای مشاهده‌ی زاویه‌های بعدی · قابل کنترل با کیبورد</p>
                    <a class="farzin-text-link" href="{{ route('shop.index') }}">مشاهده‌ی محصولات <span aria-hidden="true">↗</span></a>
                </div>
                <div class="farzin-tour__stage" data-tour-stage tabindex="0" role="group"
                     aria-label="{{ $copy['title'] }}. برای تغییر زاویه از اسکرول، درگ ماوس، لمس یا کلیدهای جهت استفاده کن.">
                    <div class="farzin-tour__frames">
                        @foreach ($sceneFrames as $frameIndex => $frame)
                            <div class="farzin-tour__frame {{ $loop->first ? 'is-active' : '' }}"
                                 data-tour-frame data-frame-index="{{ $frameIndex }}"
                                 data-frame-title="{{ $frame->title }}"
                                 data-frame-description="{{ $frame->description }}"
                                 aria-hidden="{{ $loop->first ? 'false' : 'true' }}"
                                 style="--tour-scale: {{ number_format($frame->scale, 2, '.', '') }}; --tour-position-x: {{ $frame->object_position_x }}%; --tour-position-y: {{ $frame->object_position_y }}%">
                                <img src="{{ asset('storage/' . $frame->image) }}"
                                     alt="{{ $frame->alt ?: ($frame->title ?: $copy['title']) }}"
                                     width="1600" height="1200" draggable="false"
                                     loading="{{ $loop->first ? 'eager' : 'lazy' }}" decoding="async">
                            </div>
                        @endforeach
                    </div>
                    <span class="farzin-tour__stage-label">FARZIN / OBJECT STUDY</span>
                    <div class="farzin-tour__stage-bottom">
                        <span>حرکت کن و جزئیات را ببین</span>
                        <strong class="farzin-tour__counter" data-tour-counter>01 / {{ str_pad((string) $sceneFrames->count(), 2, '0', STR_PAD_LEFT) }}</strong>
                    </div>
                    <span class="farzin-tour__drag-hint">DRAG / SCROLL / TOUCH</span>
                    <div class="farzin-tour__controls" aria-label="کنترل زاویه‌ی محصول">
                        <button type="button" data-tour-prev aria-label="زاویه‌ی قبلی">←</button>
                        <div class="farzin-tour__progress" aria-hidden="true"><span data-tour-progress></span></div>
                        <button type="button" data-tour-next aria-label="زاویه‌ی بعدی">→</button>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endforeach

<section class="farzin-section farzin-features-section" aria-labelledby="home-features-title">
    <div class="farzin-container">
        <header class="farzin-section__head">
            <div>
                <p class="farzin-kicker">04 / تجربه‌ی فرزین</p>
                <h2 id="home-features-title" class="farzin-section__title">خریدی ساده، <em>انتخابی مطمئن.</em></h2>
                <p class="farzin-section__description">جزئیات روشن از انتخاب محصول تا تحویل سفارش.</p>
            </div>
        </header>
        <div class="farzin-features-grid">
            <article class="farzin-feature">
                <span class="farzin-feature__index">01 / CLARITY</span>
                <div><h3 class="farzin-feature__title">اطلاعات شفاف</h3><p class="farzin-feature__description">مشخصات و قیمت را روشن ببین و با اطمینان مقایسه کن.</p></div>
            </article>
            <article class="farzin-feature">
                <span class="farzin-feature__index">02 / QUALITY</span>
                <div><h3 class="farzin-feature__title">انتخاب سنجیده</h3><p class="farzin-feature__description">محصولات کاربردی برای استفاده‌ی روزمره‌ی خانه.</p></div>
            </article>
            <article class="farzin-feature">
                <span class="farzin-feature__index">03 / DELIVERY</span>
                <div><h3 class="farzin-feature__title">پیگیری سفارش</h3><p class="farzin-feature__description">جزئیات سفارش و وضعیت آن را در حساب خود دنبال کن.</p></div>
            </article>
            <article class="farzin-feature">
                <span class="farzin-feature__index">04 / SUPPORT</span>
                <div><h3 class="farzin-feature__title">پشتیبانی انسانی</h3><p class="farzin-feature__description">برای انتخاب مناسب‌تر، راه ارتباط همیشه روشن است.</p></div>
            </article>
        </div>
    </div>
</section>
<section class="farzin-manifesto" aria-labelledby="farzin-manifesto-title" data-home-section="manifesto" aria-labelledby="farzin-manifesto-title"><div class="farzin-container farzin-manifesto__inner"><p class="farzin-kicker">FARZIN / PHILOSOPHY</p><h2 id="farzin-manifesto-title">کمتر، دقیق‌تر،<br><em>ماندگارتر.</em></h2><p>در فرزین، انتخاب خوب از جزئیات شروع می‌شود؛ محصولاتی برای استفاده‌ی واقعی و تجربه‌ای ساده از خرید تا دریافت سفارش.</p><a href="{{ route('shop.index') }}" class="farzin-hero__button farzin-hero__button--light">شروع کشف <span aria-hidden="true">↗</span></a></div></section>
<section class="farzin-section farzin-section--pearl" aria-labelledby="home-blog-title">
    <div class="farzin-container">
        <header class="farzin-section__head">
            <div>
                <p class="farzin-kicker">05 / مجله فرزین</p>
                <h2 id="home-blog-title" class="farzin-section__title">راهنمای <em>انتخاب بهتر</em></h2>
                <p class="farzin-section__description">نکته‌هایی کاربردی برای انتخاب آگاهانه‌تر محصولات خانه.</p>
            </div>
            <a class="farzin-text-link" href="{{ route('blog.index') }}">همه‌ی مقاله‌ها <span aria-hidden="true">↗</span></a>
        </header>
        @if($latestPosts->isNotEmpty())
            <div class="farzin-blog-grid">
                @foreach($latestPosts as $post)
                    @php
                        $postImage = $post->featured_image;
                        $postImageUrl = filled($postImage)
                            ? (str_starts_with($postImage, 'http://') || str_starts_with($postImage, 'https://') || str_starts_with($postImage, '/')
                                ? $postImage
                                : asset('storage/' . ltrim($postImage, '/')))
                            : null;
                    @endphp
                    <article class="farzin-blog-card">
                        <a class="farzin-blog-card__media" href="{{ route('blog.show', ['post' => $post->slug]) }}" aria-label="خواندن مقاله {{ $post->title }}">
                            @if($postImageUrl)
                                <img src="{{ $postImageUrl }}" alt="{{ $post->title }}" width="900" height="560" loading="lazy" decoding="async">
                            @else
                                <span class="farzin-blog-card__placeholder" aria-hidden="true">F.</span>
                            @endif
                        </a>
                        <div class="farzin-blog-card__body">
                            <span class="farzin-blog-card__category">{{ $post->category?->name ?? 'مجله فرزین' }} <span aria-hidden="true">/</span> {{ (\App\Support\JalaliCalendar::format($post->published_at, 'day-month')) }}</span>
                            <h3 class="farzin-blog-card__title"><a href="{{ route('blog.show', ['post' => $post->slug]) }}">{{ $post->title }}</a></h3>
                            @if($post->excerpt)
                                <p class="farzin-blog-card__excerpt">{{ \Illuminate\Support\Str::limit($post->excerpt, 150) }}</p>
                            @endif
                            <a class="farzin-blog-card__link" href="{{ route('blog.show', ['post' => $post->slug]) }}">ادامه‌ی مطلب <span aria-hidden="true">↗</span></a>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <p class="farzin-empty">مقاله‌های تازه به‌زودی در مجله فرزین منتشر می‌شوند.</p>
        @endif
    </div>
</section>
<section class="farzin-newsletter" aria-labelledby="home-newsletter-title">
    <div class="farzin-container farzin-newsletter__inner">
        <div>
            <p class="farzin-kicker">FARZIN / NOTES</p>
            <h2 id="home-newsletter-title" class="farzin-newsletter__title">انتخاب بعدی‌ات را از دست نده.</h2>
            <p class="farzin-newsletter__description">خبر محصولات تازه و راهنمای خرید را مستقیم دریافت کن؛ فقط وقتی حرف مفیدی برای گفتن داریم.</p>
        </div>
        <div>
            <form class="farzin-newsletter__form" action="{{ route('newsletter.store') }}" method="POST">
                @csrf
                <label class="sr-only" for="home-newsletter-email">ایمیل</label>
                <input class="farzin-newsletter__input" id="home-newsletter-email" type="email" name="email" value="{{ old('email') }}" placeholder="ایمیل شما" autocomplete="email" inputmode="email" dir="ltr" required>
                <button class="farzin-newsletter__submit" type="submit">عضویت <span aria-hidden="true">↗</span></button>
            </form>
            @error('email')
                <p class="farzin-newsletter__note" role="alert">{{ $message }}</p>
            @enderror
            @if(session('success'))
                <p class="farzin-newsletter__note" role="status">{{ session('success') }}</p>
            @else
                <p class="farzin-newsletter__note">هر زمان خواستی، می‌توانی عضویتت را لغو کنی.</p>
            @endif
        </div>
    </div>
</section>
</div>
@endsection
