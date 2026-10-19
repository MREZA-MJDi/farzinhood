@extends('layouts.app')
@section('title', ($product->meta_title ?: $product->name) . ' | فرزین')
@section('meta_description', $product->meta_description ?: ($product->short_description ?: 'جزئیات و قیمت ' . $product->name . ' در فروشگاه فرزین.'))
@section('canonical', $product->canonical_url ?: url()->current())

@push('meta')
@if($product->noindex)<meta name="robots" content="noindex, follow">@endif
@php
    $productSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product->name,
        'description' => $product->short_description ?: $product->description,
        'sku' => $product->sku,
        'offers' => [
            '@type' => 'Offer',
            'priceCurrency' => 'IRR',
            'price' => (string) ($product->price * 10),
            'availability' => $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'url' => url()->current(),
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($productSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
@endpush
@section('content')
<section class="farzin-product-page"><div class="farzin-container">
<nav class="farzin-breadcrumb" aria-label="مسیر صفحه"><a href="{{ route('home') }}">خانه</a><span>/</span><a href="{{ route('shop.index') }}">فروشگاه</a><span>/</span>@if($product->category)<a href="{{ route('categories.show',$product->category->slug) }}">{{ $product->category->name }}</a><span>/</span>@endif<span aria-current="page">{{ $product->name }}</span></nav>
<div class="farzin-product-detail"><div class="farzin-product-detail__media">
@php $images=$product->images; if($images->isEmpty() && $product->primaryImage) $images=collect([$product->primaryImage]); @endphp
@if($images->isNotEmpty())<div class="farzin-product-detail__gallery">@foreach($images as $image)@php $src=$image->image; $src=str_starts_with($src,'http://') || str_starts_with($src,'https://') || str_starts_with($src,'/') ? $src : asset('storage/'.ltrim($src,'/')); @endphp<figure class="farzin-product-detail__image {{ $loop->first ? 'is-active' : '' }}"><img src="{{ $src }}" alt="{{ $image->alt ?: $product->name }}" width="1000" height="1000" @if($loop->first) fetchpriority="high" @else loading="lazy" @endif decoding="async"></figure>@endforeach</div>@else<div class="farzin-product-detail__empty"><span>F.</span><p>تصویر این محصول ثبت نشده است.</p></div>@endif
@if($product->discount)<span class="farzin-product-detail__badge">{{ $product->discount }}٪ تخفیف</span>@endif
</div><div class="farzin-product-detail__content"><p class="farzin-kicker">{{ $product->brand ?: 'FARZIN / PRODUCT' }}</p>@if($product->category)<a class="farzin-product-detail__category" href="{{ route('categories.show',$product->category->slug) }}">{{ $product->category->name }}</a>@endif
<h1>{{ $product->name }}</h1>@if($product->short_description)<p class="farzin-product-detail__lead">{{ $product->short_description }}</p>@endif
@if($reviewCount>0)<div class="farzin-product-detail__rating" aria-label="امتیاز {{ $reviewAverage }} از ۵"><span>★</span>{{ $reviewAverage }} <small>({{ $reviewCount }} دیدگاه تأییدشده)</small></div>@endif
<div class="farzin-product-detail__price"><x-product.price :price="$product->price" :old-price="$product->old_price" :discount="$product->discount" /></div>
@if($product->stock>0)<p class="farzin-stock"><span></span> موجود برای سفارش</p>@else<p class="farzin-stock farzin-stock--out">در حال حاضر ناموجود</p>@endif
@if($product->stock>0)
@if(auth()->check() && auth()->user()->isCustomer())<form action="{{ route('customer.cart.add') }}" method="POST" class="farzin-product-detail__buy">@csrf<input type="hidden" name="product_id" value="{{ $product->id }}"><label class="farzin-quantity"><span>تعداد</span><input type="number" name="quantity" value="1" min="1" max="{{ min(99,$product->stock) }}" inputmode="numeric" required></label><button class="farzin-button" type="submit">افزودن به سبد خرید <span>↗</span></button></form><form action="{{ route('customer.wishlist.toggle',$product) }}" method="POST" class="farzin-product-detail__wish">@csrf<button type="submit">{{ $isWishlisted ? 'حذف از علاقه‌مندی‌ها' : 'افزودن به علاقه‌مندی‌ها' }}</button></form>
@else<a class="farzin-button farzin-button--wide" href="{{ route('login') }}">برای خرید وارد حساب شوید <span>↗</span></a>@endif
@endif
<div class="farzin-product-detail__notes"><p><span>01</span> خرید از مسیر امن حساب کاربری</p><p><span>02</span> پیگیری سفارش از پنل کاربری</p><p><span>03</span> اطلاعات روشن پیش از خرید</p></div>
@if($product->description)<div class="farzin-product-detail__description"><h2>درباره محصول</h2><p>{{ $product->description }}</p></div>@endif
</div></div></div></section>
@if($relatedProducts->isNotEmpty())<section class="farzin-section farzin-section--pearl"><div class="farzin-container"><header class="farzin-section__head"><div><p class="farzin-kicker">MORE FROM FARZIN</p><h2 class="farzin-section__title">انتخاب‌های <em>مرتبط</em></h2></div><a class="farzin-text-link" href="{{ route('shop.index') }}">همه محصولات <span>↗</span></a></header><div class="farzin-product-grid">@foreach($relatedProducts as $related)
<x-product.product-card :id="$related->id" :name="$related->name" :image="$related->primaryImage?->image" :href="route('products.show',$related)" :price="$related->price" :old-price="$related->old_price" :discount="$related->discount" :brand="$related->brand" :meta="$related->short_description" :rating="$related->rating" :review-count="$related->review_count" :alt="$related->primaryImage?->alt" :is-wishlisted="$related->is_wishlisted ?? false" />
@endforeach</div></div></section>@endif
@endsection
