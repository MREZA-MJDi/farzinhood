@extends('layouts.app')
@section('title', ($category->meta_title ?: $category->name) . ' | فرزین')
@section('meta_description', $category->meta_description ?: ($category->description ?: 'محصولات دسته‌بندی ' . $category->name . ' در فروشگاه فرزین.'))
@section('canonical', $category->canonical_url ?: url()->current())
@push('meta')
    @if($category->noindex)<meta name="robots" content="noindex, follow">@endif
@endpush
@section('content')
<section class="farzin-catalog"><div class="farzin-container">
<nav class="farzin-breadcrumb" aria-label="مسیر صفحه"><a href="{{ route('home') }}">خانه</a><span>/</span><a href="{{ route('shop.index') }}">فروشگاه</a><span>/</span><span aria-current="page">{{ $category->name }}</span></nav>
<header class="farzin-page-head farzin-page-head--compact"><p class="farzin-kicker">FARZIN / CATEGORY</p><h1>{{ $category->name }}</h1>@if($category->description)<p>{{ $category->description }}</p>@endif</header>
<div class="farzin-catalog__result"><span>{{ number_format($products->total()) }} محصول</span><a class="farzin-text-link" href="{{ route('shop.index') }}">همه‌ی محصولات <span>↗</span></a></div>
@if($products->isNotEmpty())<div class="farzin-product-grid">@foreach($products as $product)
<x-product.product-card :id="$product->id" :name="$product->name" :image="$product->primaryImage?->image" :href="route('products.show',$product)" :price="$product->price" :old-price="$product->old_price" :discount="$product->discount" :brand="$product->brand" :meta="$product->short_description" :rating="$product->rating" :review-count="$product->review_count" :alt="$product->primaryImage?->alt" :is-wishlisted="$product->is_wishlisted ?? false" />
@endforeach</div><nav class="farzin-pagination" aria-label="صفحه‌بندی">{{ $products->links() }}</nav>
@else<div class="farzin-empty farzin-empty--large"><span class="farzin-empty__mark">F.</span><h2>فعلاً محصولی در این دسته نیست</h2><p>دسته‌های دیگر فروشگاه را ببین.</p><a class="farzin-button" href="{{ route('shop.index') }}">رفتن به فروشگاه</a></div>@endif
</div></section>
@endsection
