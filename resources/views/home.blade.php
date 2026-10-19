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
                            <h3 class="farzin-blog-card__title"><a href="{{ route('blog.show', $post) }}">{{ $post->title }}</a></h3>
                            @if($post->excerpt)
                                <p class="farzin-blog-card__excerpt">{{ \Illuminate\Support\Str::limit($post->excerpt, 150) }}</p>
                            @endif
                            <a class="farzin-blog-card__link" href="{{ route('blog.show', $post) }}">ادامه‌ی مطلب <span aria-hidden="true">↗</span></a>
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
