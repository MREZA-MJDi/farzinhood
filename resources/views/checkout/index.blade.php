@extends('layouts.storefront')

@section('title', 'تکمیل سفارش | فرزین')
@section('meta_description', 'ثبت نهایی سفارش، انتخاب نشانی ارسال و بازبینی هزینه‌ها در فرزین.')

@section('content')
    <div class="fz-container fz-transaction-page">
        <nav class="fz-breadcrumb" aria-label="مسیر صفحه">
            <a href="{{ route('home') }}">خانه</a><span aria-hidden="true">/</span>
            <a href="{{ route('customer.cart.index') }}">سبد خرید</a><span aria-hidden="true">/</span>
            <span aria-current="page">تکمیل سفارش</span>
        </nav>

        <header class="fz-page-heading">
            <span class="fz-kicker">FARZIN / CHECKOUT</span>
            <h1 class="fz-h2">تکمیل سفارش</h1>
            <p class="fz-desc">نشانی ارسال و خلاصه قیمت را بررسی کن؛ موجودی و قیمت نهایی در سمت سرور دوباره کنترل می‌شوند.</p>
        </header>

        <form action="{{ route('customer.checkout.store') }}" method="POST" class="fz-transaction-grid">
            @csrf

            <div class="fz-checkout-main">
                <section class="fz-panel">
                    <div class="fz-panel-heading">
                        <div><span class="fz-kicker">مرحله ۱</span><h2>نشانی ارسال</h2></div>
                        <a href="{{ route('customer.addresses.index') }}">مدیریت آدرس‌ها ←</a>
                    </div>

                    @if ($addresses->isEmpty())
                        <div class="fz-empty">
                            <h3>هنوز آدرسی ذخیره نکرده‌ای.</h3>
                            <p class="fz-desc">برای ادامه پرداخت، ابتدا یک نشانی ارسال بساز.</p>
                            <a class="fz-btn" href="{{ route('customer.addresses.index') }}">افزودن آدرس</a>
                        </div>
                    @else
                        <div class="fz-checkout-addresses">
                            @foreach ($addresses as $address)
                                <label class="fz-checkout-address">
                                    <input type="radio" name="address_id" value="{{ $address->id }}"
                                           @checked(old('address_id', $address->is_default ? $address->id : null) == $address->id) required>
                                    <span>
                                        <strong>{{ $address->title ?: 'آدرس ارسال' }} @if ($address->is_default)<small>· پیش‌فرض</small>@endif</strong>
                                        <span>{{ $address->full_name }} · <bdi>{{ $address->phone }}</bdi></span>
                                        <span>{{ $address->province }}، {{ $address->city }} — {{ $address->address }}</span>
                                        @if ($address->postal_code)<span>کد پستی: {{ $address->postal_code }}</span>@endif
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    @endif
                    @error('address_id')<p class="fz-field-error mt-3">{{ $message }}</p>@enderror
                </section>

                <section class="fz-panel">
                    <div class="fz-panel-heading"><div><span class="fz-kicker">مرحله ۲</span><h2>روش پرداخت</h2></div></div>
                    <label class="fz-checkout-address">
                        <input type="radio" name="payment_method" value="gateway" @checked(old('payment_method', 'gateway') === 'gateway') required>
                        <span><strong>پرداخت آنلاین</strong><span>پس از ثبت سفارش، به مسیر پرداخت هدایت می‌شوی.</span></span>
                    </label>
                    @error('payment_method')<p class="fz-field-error mt-3">{{ $message }}</p>@enderror
                </section>

                <section class="fz-panel">
                    <div class="fz-panel-heading"><div><span class="fz-kicker">مرحله ۳</span><h2>یادداشت سفارش</h2></div><span class="fz-form-help">اختیاری</span></div>
                    <label class="fz-form-field" for="checkout-notes">
                        <span>نکته‌ای برای آماده‌سازی یا تحویل؟</span>
                        <textarea class="fz-textarea" id="checkout-notes" name="notes" rows="4" maxlength="2000" placeholder="اطلاعات تکمیلی درباره تحویل...">{{ old('notes') }}</textarea>
                    </label>
                    @error('notes')<p class="fz-field-error mt-3">{{ $message }}</p>@enderror
                </section>
            </div>

            <aside class="fz-order-summary">
                <span class="fz-kicker">خلاصه سفارش</span>
                <h2>یک بررسی نهایی</h2>

                <div class="fz-checkout-items">
                    @foreach ($summary['items'] as $row)
                        @php
                            $product = $row['product'];
                            $cartItem = $row['cart_item'];
                            $image = $product->primaryImage?->image;
                        @endphp
                        <div class="fz-checkout-item">
                            <div class="fz-checkout-item__image">
                                @if ($image)
                                    <img src="{{ asset('storage/' . $image) }}" alt="" loading="lazy" decoding="async">
                                @else
                                    <span aria-hidden="true">FARZIN</span>
                                @endif
                            </div>
                            <div class="fz-checkout-item__copy">
                                <strong>{{ $product->name }}</strong>
                                <span>{{ number_format($row['quantity']) }} عدد × {{ number_format($row['unit_price']) }} تومان</span>
                            </div>
                            <strong class="fz-checkout-item__total">{{ number_format($row['total']) }}</strong>
                        </div>
                    @endforeach
                </div>

                <div class="fz-summary-row"><span>تعداد کالا</span><strong>{{ number_format($summary['item_count']) }}</strong></div>
                <div class="fz-summary-row"><span>جمع کالاها</span><strong>{{ number_format($summary['subtotal']) }} تومان</strong></div>
                <div class="fz-summary-row"><span>تخفیف</span><strong>{{ $summary['discount'] > 0 ? number_format($summary['discount']) . ' تومان' : '—' }}</strong></div>
                <div class="fz-summary-row"><span>هزینه ارسال</span><strong>{{ $summary['shipping'] > 0 ? number_format($summary['shipping']) . ' تومان' : 'رایگان' }}</strong></div>
                <div class="fz-summary-row fz-summary-row--total"><span>مبلغ نهایی</span><strong>{{ number_format($summary['total']) }} تومان</strong></div>

                <button class="fz-btn fz-summary-checkout" type="submit" @disabled($addresses->isEmpty())>
                    ثبت سفارش و ادامه پرداخت <span aria-hidden="true">←</span>
                </button>
                @if ($addresses->isEmpty())
                    <p class="fz-field-error mt-2">برای ادامه، ابتدا یک آدرس اضافه کن.</p>
                @endif
                <p class="fz-form-help mt-3">قیمت محصولات و موجودی هنگام ثبت سفارش در backend دوباره بررسی می‌شود.</p>
                <a class="fz-checkout-back" href="{{ route('customer.cart.index') }}">بازگشت به سبد خرید</a>
            </aside>
        </form>
    </div>
@endsection
