@extends('layouts.app')
@section('title', 'فرزین | هود و سینک با نگاه معماری')
@section('meta_description', 'مجموعه هود و سینک فرزین؛ طراحی ماندگار، جزئیات دقیق و انتخابی هماهنگ با آشپزخانه امروز.')
@section('content')
@php($heroSlides = ($heroProducts ?? collect())->values())
<section class="fz-home-hero" data-home-hero aria-labelledby="fz-home-title">
 <div class="fz-home-hero__image" aria-hidden="true"><img src="{{ asset('images/home/hero.webp') }}" alt="" fetchpriority="high" decoding="async"></div><div class="fz-home-hero__shade" aria-hidden="true"></div>
 <div class="fz-home-hero__content"><p class="fz-eyebrow"><span></span> FARZIN / KITCHEN COLLECTION</p><h1 id="fz-home-title">آشپزخانه،<br><em>با امضای فرزین.</em></h1><p class="fz-home-hero__lead">هود و سینک، جایی که طراحی دقیق با زندگی روزمره همراه می‌شود.</p><div class="fz-home-hero__actions"><a class="fz-button fz-button--light" href="{{ route('shop.index') }}">دیدن مجموعه <span aria-hidden="true">←</span></a><a class="fz-text-link" href="#fz-categories">کشف دسته‌بندی‌ها <span aria-hidden="true">↓</span></a></div><div class="fz-home-hero__note"><span class="fz-home-hero__note-line"></span><span>طراحی برای خانه‌ای که در آن زندگی جریان دارد</span></div></div><div class="fz-home-hero__products" aria-label="محصولات تازه فرزین">
 @foreach($heroSlides as $index => $product)
  <a class="fz-hero-product" href="{{ route('products.show', $product) }}" aria-label="مشاهده {{ $product->name }}">
   <span class="fz-hero-product__number">{{ str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
   <span class="fz-hero-product__name">{{ $product->name }}</span>
   <span class="fz-hero-product__price">{{ number_format($product->price) }} <small>تومان</small></span>
  </a>
 @endforeach
</div>
<div class="fz-home-hero__counter" aria-hidden="true"><span>01</span><i></i><span>03</span></div>
</section>
<section class="fz-trust-strip" data-home-trust-strip aria-label="ویژگی‌های فرزین"><div><span>01</span><p><strong>طراحی سنجیده</strong><small>جزئیات هماهنگ با فضای آشپزخانه</small></p></div><div><span>02</span><p><strong>انتخاب کاربردی</strong><small>تمرکز بر هود و سینک</small></p></div><div><span>03</span><p><strong>خرید آسان</strong><small>مسیر روشن از انتخاب تا سفارش</small></p></div></section>
<section id="fz-categories" class="fz-section"><header class="fz-section__head"><div><p class="fz-eyebrow fz-eyebrow--dark">THE COLLECTION</p><h2>برای فضای تو انتخاب شده</h2><p>از گروه محصول موردنظرت شروع کن.</p></div><a class="fz-text-link fz-text-link--dark" href="{{ route('shop.index') }}">همه محصولات <span aria-hidden="true">←</span></a></header><div class="fz-category-grid">
@forelse($categories ?? collect() as $category)<a class="fz-category-card" href="{{ route('categories.show', $category) }}"><div class="fz-category-card__image">@if($category->image)<img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" loading="lazy" decoding="async">@else<span aria-hidden="true">F.</span>@endif<span class="fz-category-card__arrow" aria-hidden="true">↖</span></div><div class="fz-category-card__meta"><h3>{{ $category->name }}</h3><span>مشاهده مجموعه</span></div></a>
@empty<p class="fz-empty">دسته‌بندی‌ها پس از ثبت در پنل مدیریت اینجا نمایش داده می‌شوند.</p>@endforelse
</div></section>
<section class="fz-featured"><div class="fz-section"><header class="fz-section__head"><div><p class="fz-eyebrow fz-eyebrow--dark">FARZIN SELECTED</p><h2>محصولاتی برای ماندن</h2><p>گزیده‌ای از محصولات منتخب فرزین.</p></div><a class="fz-text-link fz-text-link--dark" href="{{ route('shop.index') }}">مشاهده فروشگاه <span aria-hidden="true">←</span></a></header><div class="fz-product-grid">
@forelse($featuredProducts ?? collect() as $product)@include('partials.product_card', ['product' => $product])@empty<p class="fz-empty">محصولات منتخب پس از ثبت در پنل مدیریت نمایش داده می‌شوند.</p>@endforelse
</div></div></section>
<section class="fz-editorial"><div class="fz-editorial__image" role="img" aria-label="جزئیات آشپزخانه مدرن"></div><div class="fz-editorial__content"><p class="fz-eyebrow">A THOUGHTFUL KITCHEN</p><h2>زیبایی در جزئیاتی‌ست<br>که هر روز لمس می‌کنی.</h2><p>از انتخاب هود تا جزئیات سینک، هر تصمیم کوچک می‌تواند تجربه آشپزخانه را بهتر کند.</p><a class="fz-button fz-button--outline-light" href="{{ route('blog.index') }}">خواندن راهنماها <span aria-hidden="true">←</span></a></div></section>
<section class="fz-section fz-latest"><header class="fz-section__head"><div><p class="fz-eyebrow fz-eyebrow--dark">JUST ARRIVED</p><h2>تازه رسیده‌ها</h2><p>محصولات جدیدی که به مجموعه اضافه شده‌اند.</p></div><a class="fz-text-link fz-text-link--dark" href="{{ route('shop.index', ['sort' => 'latest']) }}">همه تازه‌ها <span aria-hidden="true">←</span></a></header><div class="fz-product-grid">
@forelse($latestProducts ?? collect() as $product)@include('partials.product_card', ['product' => $product])@empty<p class="fz-empty">هنوز محصولی برای نمایش ثبت نشده است.</p>@endforelse
</div></section>
<section class="fz-final-cta"><div><p class="fz-eyebrow">FARZIN / MADE FOR DAILY LIVING</p><h2>جای یک انتخاب خوب<br>در آشپزخانه‌ات خالی‌ست.</h2></div><a class="fz-button fz-button--light" href="{{ route('shop.index') }}">شروع انتخاب <span aria-hidden="true">←</span></a></section>
@endsection