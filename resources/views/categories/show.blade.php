@extends('layouts.storefront')
@section('title', $category->name . ' | فروشگاه فرزین')
@section('meta_description', $category->meta_description ?: $category->description ?: 'محصولات دسته‌بندی ' . $category->name . ' در فروشگاه فرزین.')
@section('content')
<section class="catalog-hero catalog-hero--category">
    <a class="store-breadcrumb" href="{{ route('shop.index') }}">فروشگاه <span>←</span> دسته‌بندی‌ها</a>
    <span class="farzin-eyebrow">SHOP BY CATEGORY</span><h1>{{ $category->name }}</h1>
    @if ($category->description)<p>{{ $category->description }}</p>@else<p>محصولات منتخب دسته‌بندی {{ $category->name }} را بررسی کنید.</p>@endif
</section>
<section class="store-section">
    <div class="catalog-results__heading"><div><h2>محصولات {{ $category->name }}</h2><p>{{ number_format($products->total()) }} محصول</p></div><a class="store-text-link" href="{{ route('shop.index', ['category' => $category->slug]) }}">فیلتر و مرتب‌سازی <span aria-hidden="true">←</span></a></div>
    <div class="store-product-grid">
        @forelse ($products as $product)
            <x-storefront.product-card :product="$product" />
        @empty
            <div class="store-empty"><strong>در این دسته‌بندی هنوز محصولی وجود ندارد.</strong><a href="{{ route('shop.index') }}">مشاهده همه محصولات</a></div>
        @endforelse
    </div>
    <div class="catalog-pagination">{{ $products->links() }}</div>
</section>
@endsection