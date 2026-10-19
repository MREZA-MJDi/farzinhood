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
<x-product.product-card :id="$product->id" :name="$product->name" :image="$product->primaryImage?->image" :href="route('products.show',$product)" :price="$product->price" :old-price="$product->old_price" :discount="$product->discount" :brand="$product->brand" :meta="$product->short_description" :rating="$product->rating" :review-count="$product->review_count" :alt="$product->primaryImage?->alt" />
@endforeach</div>@else<p class="farzin-empty">هنوز محصول ویژه‌ای برای نمایش انتخاب نشده است.</p>@endif
</div></section>
<section class="farzin-section" aria-labelledby="home-latest-title"><div class="farzin-container">
<header class="farzin-section__head"><div><p class="farzin-kicker">03 / تازه‌ها</p><h2 id="home-latest-title" class="farzin-section__title">تازه‌واردهای <em>فروشگاه</em></h2></div><a class="farzin-text-link" href="{{ route('shop.index',['sort'=>'latest']) }}">کشف تازه‌ها <span aria-hidden="true">↗</span></a></header>
@if($latestProducts->isNotEmpty())<div class="farzin-product-grid">@foreach($latestProducts as $product)
<x-product.product-card :id="$product->id" :name="$product->name" :image="$product->primaryImage?->image" :href="route('products.show',$product)" :price="$product->price" :old-price="$product->old_price" :discount="$product->discount" :brand="$product->brand" :meta="$product->short_description" :rating="$product->rating" :review-count="$product->review_count" :alt="$product->primaryImage?->alt" />
@endforeach</div>@else<p class="farzin-empty">محصولات جدید به‌زودی در فروشگاه قرار می‌گیرند.</p>@endif
</div></section>
<section class="farzin-manifesto" aria-labelledby="farzin-manifesto-title"><div class="farzin-container farzin-manifesto__inner"><p class="farzin-kicker">FARZIN / PHILOSOPHY</p><h2 id="farzin-manifesto-title">کمتر، دقیق‌تر،<br><em>ماندگارتر.</em></h2><p>در فرزین، انتخاب خوب از جزئیات شروع می‌شود؛ محصولاتی برای استفاده‌ی واقعی و تجربه‌ای ساده از خرید تا دریافت سفارش.</p><a href="{{ route('shop.index') }}" class="farzin-hero__button farzin-hero__button--light">شروع کشف <span aria-hidden="true">↗</span></a></div></section>
</div>
@endsection
