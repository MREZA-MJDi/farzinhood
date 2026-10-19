@extends('layouts.app')

@section('title', $category->name . ' | فروشگاه فرزین')
@section('meta_description', $category->description ?: 'مشاهده محصولات دسته‌بندی ' . $category->name . ' در فروشگاه فرزین.')

@section('content')
<section class="fh-page">
    <header class="fh-page__hero">
        <p class="fh-eyebrow fh-eyebrow--dark">FARZIN / CATEGORY</p>
        <h1>{{ $category->name }}</h1>
        @if ($category->description)<p>{{ $category->description }}</p>@endif
        <a class="fh-text-link" href="{{ route('shop.index', ['category' => $category->slug]) }}">مشاهده در فروشگاه <span aria-hidden="true">↗</span></a>
    </header>
    <div class="fh-page__inner">
        <div class="fh-listing-heading"><p>{{ $products->total() }} محصول</p><span>محصولات فعال این دسته‌بندی</span></div>
        @if ($products->count())
            <div class="fh-product-grid">
                @foreach ($products as $product)
                    <a class="fh-product-card fh-product-card--light" href="{{ route('products.show', ['product' => $product->slug]) }}">
                        <div class="fh-product-card__media">
                            @if ($product->primaryImage?->image)
                                <img src="{{ asset('storage/' . ltrim($product->primaryImage->image, '/')) }}" alt="{{ $product->primaryImage->alt ?: $product->name }}" loading="lazy">
                            @else
                                <span class="fh-product-card__placeholder" aria-hidden="true">F</span>
                            @endif
                            <span class="fh-product-card__arrow" aria-hidden="true">↗</span>
                        </div>
                        <div class="fh-product-card__meta"><span>{{ $product->category?->name }}</span><span>{{ $product->sku }}</span></div>
                        <h2>{{ $product->name }}</h2>
                        <p class="fh-product-card__price">{{ number_format((float) $product->price) }} <small>تومان</small></p>
                    </a>
                @endforeach
            </div>
            <div class="fh-pagination">{{ $products->links() }}</div>
        @else
            <div class="fh-empty">در این دسته‌بندی هنوز محصولی برای نمایش وجود ندارد.</div>
        @endif
    </div>
</section>
@endsection
