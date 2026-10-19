@extends('layouts.app')

@section('title', 'علاقه‌مندی‌ها | فرزین')

@section('content')
<section class="fh-page">
    <header class="fh-page__hero">
        <p class="fh-eyebrow fh-eyebrow--dark">FARZIN / WISHLIST</p>
        <h1>علاقه‌مندی‌های من</h1>
        <p>محصولاتی را که برای بررسی بعدی ذخیره کرده‌اید اینجا ببینید.</p>
    </header>
    <div class="fh-page__inner">
        @if ($wishlist->isNotEmpty())
            <div class="fh-product-grid">
                @foreach ($wishlist as $entry)
                    @php($product = $entry->product)
                    <article class="fh-product-card fh-product-card--light">
                        <a href="{{ route('products.show', ['product' => $product->slug]) }}">
                            <div class="fh-product-card__media">
                                @if ($product->primaryImage?->image)
                                    <img src="{{ asset('storage/' . ltrim($product->primaryImage->image, '/')) }}" alt="{{ $product->primaryImage->alt ?: $product->name }}" loading="lazy">
                                @else
                                    <span class="fh-product-card__placeholder">F</span>
                                @endif
                            </div>
                            <div class="fh-product-card__meta"><span>{{ $product->category?->name }}</span><span>{{ $product->sku }}</span></div>
                            <h2>{{ $product->name }}</h2>
                            <p class="fh-product-card__price">{{ number_format((float) $product->price) }} <small>تومان</small></p>
                        </a>
                        <form action="{{ route('customer.wishlist.toggle', ['product' => $product->id]) }}" method="POST">@csrf<button class="fh-text-link" type="submit">حذف از علاقه‌مندی‌ها</button></form>
                    </article>
                @endforeach
            </div>
        @else
            <div class="fh-empty"><strong>هنوز محصولی ذخیره نکرده‌اید.</strong><p>در صفحه هر محصول می‌توانید آن را به علاقه‌مندی‌ها اضافه کنید.</p><a href="{{ route('shop.index') }}">مشاهده فروشگاه ↗</a></div>
        @endif
    </div>
</section>
@endsection
