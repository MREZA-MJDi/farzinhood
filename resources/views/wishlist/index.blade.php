@extends('layouts.app')
@section('title', 'علاقه‌مندی‌های من | فرزین')
@section('content')
<section class="farzin-catalog">
    <div class="farzin-container">
        <header class="farzin-page-head">
            <p class="farzin-kicker">FARZIN / WISHLIST</p>
            <h1>علاقه‌مندی‌های <em>من</em></h1>
            <p>محصولاتی که ذخیره کرده‌ای تا بعداً به‌راحتی به آن‌ها برگردی.</p>
        </header>
        @include('partials.account-nav')
        @if($wishlist->isNotEmpty())
            <div class="farzin-product-grid mt-8">
                @foreach($wishlist as $entry)
                    @php($product = $entry->product)
                    @if($product)
                        <x-product.product-card
                            :id="$product->id"
                            :name="$product->name"
                            :image="$product->primaryImage?->image"
                            :href="route('products.show', $product)"
                            :price="$product->price"
                            :old-price="$product->old_price"
                            :discount="$product->discount"
                            :brand="$product->brand"
                            :meta="$product->short_description"
                            :rating="$product->rating"
                            :review-count="$product->review_count"
                            :alt="$product->primaryImage?->alt"
                            :is-wishlisted="true"
                        />
                    @endif
                @endforeach
            </div>
            @if($wishlist->hasPages())
                <nav class="farzin-pagination mt-8" aria-label="صفحه‌بندی علاقه‌مندی‌ها">{{ $wishlist->links() }}</nav>
            @endif
        @else
            <div class="farzin-empty farzin-empty--large mt-8">
                <span class="farzin-empty__mark">♡</span>
                <h2>هنوز محصولی ذخیره نکرده‌ای</h2>
                <p>در صفحه محصولات، علاقه‌مندی‌هایت را به فهرست اضافه کن.</p>
                <a href="{{ route('shop.index') }}" class="farzin-button">کشف محصولات <span aria-hidden="true">↗</span></a>
            </div>
        @endif
    </div>
</section>
@endsection
