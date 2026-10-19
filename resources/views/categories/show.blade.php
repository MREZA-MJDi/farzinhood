@extends('layouts.storefront')

@section('title', $category->meta_title ?: $category->name . ' | فرزین')
@section('meta_description', $category->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($category->description ?: 'مشاهده محصولات دسته‌بندی ' . $category->name . ' در فروشگاه فرزین.'), 155))

@push('head')
    <link rel="canonical" href="{{ $category->canonical_url ?: route('categories.show', $category->slug) }}">
    @if ($category->noindex)
        <meta name="robots" content="noindex, follow">
    @endif
@endpush

@section('content')
    <div class="fz-container">
        <nav class="fz-breadcrumb" aria-label="مسیر صفحه">
            <a href="{{ route('home') }}">خانه</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('shop.index') }}">فروشگاه</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page">{{ $category->name }}</span>
        </nav>

        <header class="fz-catalog-head">
            <span class="fz-kicker">دسته‌بندی محصولات</span>
            <h1 class="fz-h2">{{ $category->name }}</h1>
            @if ($category->description)
                <p class="fz-desc">{{ $category->description }}</p>
            @endif
            <p class="fz-desc">{{ number_format($products->total()) }} محصول</p>
        </header>

        @if ($products->isNotEmpty())
            <div class="fz-products">
                @foreach ($products as $product)
                    @include('storefront.partials.product-card', ['product' => $product])
                @endforeach
            </div>
            <div class="fz-pagination">{{ $products->links() }}</div>
        @else
            <div class="fz-empty" role="status">در این دسته‌بندی فعلاً محصول فعالی وجود ندارد.</div>
        @endif
    </div>
@endsection
