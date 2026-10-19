@extends('layouts.storefront')

@section('title', 'علاقه‌مندی‌های من | فرزین')

@section('content')
    <section class="fz-section">
        <div class="fz-container">
            <nav class="fz-breadcrumb" aria-label="مسیر صفحه">
                <a href="{{ route('home') }}">خانه</a><span aria-hidden="true">/</span>
                <a href="{{ route('customer.dashboard') }}">حساب من</a><span aria-hidden="true">/</span>
                <span aria-current="page">علاقه‌مندی‌ها</span>
            </nav>

            <header class="fz-page-heading">
                <span class="fz-kicker">FARZIN / WISHLIST</span>
                <h1 class="fz-h2">علاقه‌مندی‌های من</h1>
                <p class="fz-desc">محصول‌هایی که برای بررسی دوباره ذخیره کرده‌ای.</p>
            </header>

            @if ($wishlist->isEmpty())
                <div class="fz-empty" role="status">
                    <h2>هنوز محصولی ذخیره نکرده‌ای.</h2>
                    <p class="fz-desc">در صفحه هر محصول، آن را به علاقه‌مندی‌ها اضافه کن تا بعداً راحت‌تر پیدایش کنی.</p>
                    <a class="fz-btn" href="{{ route('shop.index') }}">مشاهده فروشگاه</a>
                </div>
            @else
                <div class="fz-products">
                    @foreach ($wishlist as $entry)
                        @if ($entry->product)
                            <div class="fz-wishlist-card">
                                @include('storefront.partials.product-card', ['product' => $entry->product])
                                <form method="POST" action="{{ route('customer.wishlist.toggle', $entry->product_id) }}" class="fz-wishlist-remove">
                                    @csrf
                                    <button class="fz-text-button" type="submit">حذف از علاقه‌مندی‌ها</button>
                                </form>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
