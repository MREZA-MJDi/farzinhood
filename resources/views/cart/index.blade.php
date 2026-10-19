@extends('layouts.storefront')

@section('title', 'سبد خرید | فرزین')
@section('meta_description', 'بازبینی تعداد، قیمت و موجودی محصول‌ها پیش از تکمیل سفارش در فرزین.')

@section('content')
    <div class="fz-container fz-transaction-page">
        <nav class="fz-breadcrumb" aria-label="مسیر صفحه">
            <a href="{{ route('home') }}">خانه</a><span aria-hidden="true">/</span>
            <a href="{{ route('shop.index') }}">فروشگاه</a><span aria-hidden="true">/</span>
            <span aria-current="page">سبد خرید</span>
        </nav>

        <header class="fz-page-heading">
            <span class="fz-kicker">FARZIN / CART</span>
            <h1 class="fz-h2">سبد خرید شما</h1>
            <p class="fz-desc">{{ number_format($itemCount) }} عدد کالا در سبد خرید ثبت شده است.</p>
        </header>

        @if ($items->items->isEmpty())
            <section class="fz-empty fz-cart-empty" role="status">
                <h2>سبد خرید خالی است.</h2>
                <p class="fz-desc">محصولات را بررسی کن و هر کالایی را که نیاز داری به سبد اضافه کن.</p>
                <a class="fz-btn" href="{{ route('shop.index') }}">رفتن به فروشگاه</a>
            </section>
        @else
            <div class="fz-transaction-grid">
                <section class="fz-cart-items" aria-label="محصولات سبد خرید">
                    @foreach ($items->items as $item)
                        @php
                            $product = $item->product;
                            $image = $product?->primaryImage;
                            $available = $product && $product->is_active && $product->category?->is_active;
                        @endphp
                        @if ($product)
                            <article class="fz-cart-item" data-cart-row>
                                <a class="fz-cart-item__image" href="{{ route('products.show', $product->slug) }}">
                                    @if ($image)
                                        <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $image->alt ?: $product->name }}" loading="lazy" decoding="async">
                                    @else
                                        <span aria-hidden="true">FARZIN</span>
                                    @endif
                                </a>
                                <div class="fz-cart-item__content">
                                    <div class="fz-cart-item__title-row">
                                        <div>
                                            <span class="fz-brand">{{ $product->category?->name ?: 'محصول فرزین' }}</span>
                                            <h2><a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a></h2>
                                        </div>
                                        <strong class="fz-cart-item__total">{{ number_format($item->unit_price * $item->quantity) }} <small>تومان</small></strong>
                                    </div>

                                    <p class="fz-cart-item__unit">{{ number_format($item->unit_price) }} تومان برای هر عدد</p>

                                    @if (! $available)
                                        <p class="fz-product-note">این محصول دیگر برای خرید فعال نیست. آن را از سبد حذف کن.</p>
                                    @elseif ($item->quantity > $product->stock)
                                        <p class="fz-product-note">موجودی فعلی {{ number_format($product->stock) }} عدد است؛ تعداد را اصلاح کن.</p>
                                    @endif

                                    <div class="fz-cart-item__actions">
                                        @if ($available && $product->stock > 0)
                                            <form method="POST" action="{{ route('customer.cart.update', $product) }}" class="fz-cart-quantity" data-cart-update>
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <label class="fz-visually-hidden" for="cart-quantity-{{ $product->id }}">تعداد {{ $product->name }}</label>
                                                <input id="cart-quantity-{{ $product->id }}" name="quantity" type="number" min="1" max="{{ min(99, $product->stock) }}" value="{{ min($item->quantity, max(1, $product->stock)) }}" required>
                                                <button class="fz-btn light" type="submit">به‌روزرسانی</button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('customer.cart.remove', $product) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="fz-text-button" type="submit">حذف از سبد</button>
                                        </form>
                                    </div>
                                </div>
                            </article>
                        @endif
                    @endforeach

                    <div class="fz-cart-bottom-links">
                        <a href="{{ route('shop.index') }}">← ادامه خرید</a>
                        <form method="POST" action="{{ route('customer.cart.clear') }}" onsubmit="return confirm('سبد خرید خالی شود؟')">
                            @csrf
                            @method('DELETE')
                            <button class="fz-text-button" type="submit">خالی‌کردن سبد</button>
                        </form>
                    </div>
                </section>

                <aside class="fz-order-summary">
                    <span class="fz-kicker">خلاصه سفارش</span>
                    <h2>جمع‌بندی</h2>
                    <div class="fz-summary-row"><span>تعداد کالا</span><strong>{{ number_format($itemCount) }}</strong></div>
                    <div class="fz-summary-row"><span>جمع محصولات</span><strong>{{ number_format($subtotal) }} تومان</strong></div>
                    <p class="fz-form-help">هزینه ارسال و مبلغ نهایی در مرحله تکمیل سفارش مشخص می‌شود.</p>
                    <a class="fz-btn fz-summary-checkout" href="{{ route('customer.checkout.index') }}">ادامه و انتخاب آدرس <span aria-hidden="true">←</span></a>
                </aside>
            </div>
        @endif
    </div>
@endsection
