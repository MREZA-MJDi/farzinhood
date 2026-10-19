@extends('layouts.app')

@section('title', 'سبد خرید | فرزین')

@section('content')
<section class="fh-page">
    <header class="fh-page__hero">
        <p class="fh-eyebrow fh-eyebrow--dark">FARZIN / CART</p>
        <h1>سبد خرید شما</h1>
        <p>پیش از ادامه، تعداد و مشخصات محصولات را بررسی کنید.</p>
    </header>
    <div class="fh-page__inner fh-cart-layout">
        <div class="fh-cart-items">
            @forelse ($items->items as $item)
                <article class="fh-cart-item">
                    <a class="fh-cart-item__image" href="{{ route('products.show', ['product' => $item->product->slug]) }}">
                        @if ($item->product?->primaryImage?->image)
                            <img src="{{ asset('storage/' . ltrim($item->product->primaryImage->image, '/')) }}" alt="{{ $item->product->name }}">
                        @else
                            <span aria-hidden="true">F</span>
                        @endif
                    </a>
                    <div class="fh-cart-item__body">
                        <p class="fh-eyebrow fh-eyebrow--dark">{{ $item->product?->category?->name }}</p>
                        <h2><a href="{{ route('products.show', ['product' => $item->product->slug]) }}">{{ $item->product->name }}</a></h2>
                        <p>{{ number_format((float) $item->unit_price) }} تومان</p>
                        <div class="fh-cart-item__actions">
                            <form action="{{ route('customer.cart.update', ['product' => $item->product_id]) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <label for="quantity-{{ $item->id }}">تعداد</label>
                                <input id="quantity-{{ $item->id }}" type="number" name="quantity" min="1" max="{{ max(1, $item->product->stock) }}" value="{{ $item->quantity }}" required>
                                <button type="submit" class="fh-text-link">به‌روزرسانی</button>
                            </form>
                            <form action="{{ route('customer.cart.remove', ['product' => $item->product_id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="fh-text-link" type="submit">حذف محصول</button>
                            </form>
                        </div>
                    </div>
                    <strong class="fh-cart-item__total">{{ number_format((float) $item->unit_price * $item->quantity) }} تومان</strong>
                </article>
            @empty
                <div class="fh-empty"><strong>سبد خرید شما خالی است.</strong><p>از فروشگاه محصول مناسب خود را انتخاب کنید.</p><a href="{{ route('shop.index') }}">رفتن به فروشگاه ↗</a></div>
            @endforelse
        </div>
        <aside class="fh-cart-summary">
            <p class="fh-eyebrow">ORDER SUMMARY</p>
            <h2>خلاصه سفارش</h2>
            <div><span>تعداد اقلام</span><strong>{{ $itemCount }}</strong></div>
            <div><span>جمع جزء</span><strong>{{ number_format((float) $subtotal) }} تومان</strong></div>
            <p class="fh-cart-summary__note">هزینه ارسال و جزئیات نهایی در مرحله ثبت سفارش محاسبه می‌شود.</p>
            @if ($itemCount > 0)
                <a class="fh-button fh-button--dark" href="{{ route('customer.checkout.index') }}">ادامه و ثبت سفارش <span aria-hidden="true">↗</span></a>
                <form action="{{ route('customer.cart.clear') }}" method="POST" class="fh-cart-summary__clear">@csrf @method('DELETE')<button type="submit">خالی‌کردن سبد</button></form>
            @else
                <a class="fh-button fh-button--dark" href="{{ route('shop.index') }}">مشاهده محصولات</a>
            @endif
        </aside>
    </div>
</section>
@endsection
