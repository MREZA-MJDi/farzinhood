@extends('layouts.app')

@section('title', 'تکمیل سفارش | فرزین')
@section('meta_description', 'ثبت نهایی سفارش و انتخاب آدرس ارسال در فرزین')

@section('content')
<div class="store-transaction">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="store-transaction__head">
            <div>
                <span class="product-v2__eyebrow">FARZIN / CHECKOUT</span>
                <h1>تکمیل سفارش</h1>
                <p>اطلاعات ارسال را تایید کن؛ بعد از ثبت سفارش، وارد مرحله پرداخت می‌شوی.</p>
            </div>

            @include('partials.back-link', ['href' => route('customer.cart.index'), 'label' => 'بازگشت به سبد'])
        </div>

        <form action="{{ route('customer.checkout.store') }}" method="POST" class="store-transaction__layout">
            @csrf

            <div class="space-y-3">
                <section class="store-panel">
                    <div class="store-panel__head">
                        <strong>۱. آدرس ارسال</strong>
                        <a class="text-[10px] font-black text-[var(--color-accent-600)]" href="{{ route('customer.addresses.index') }}">مدیریت آدرس‌ها</a>
                    </div>

                    <div class="store-panel__body">
                        @if($addresses->isEmpty())
                            <div class="store-empty !p-8">
                                <div class="store-empty__icon">⌖</div>
                                <h2>هنوز آدرسی نداری</h2>
                                <p>قبل از ثبت سفارش یک آدرس ارسال ذخیره کن.</p>
                                <a href="{{ route('customer.addresses.index') }}">افزودن آدرس ←</a>
                            </div>
                        @else
                            <div class="store-checkout__address-grid">
                                @foreach($addresses as $address)
                                    <label class="store-address-option">
                                        <input
                                            type="radio"
                                            name="address_id"
                                            value="{{ $address->id }}"
                                            @checked(old('address_id', $address->is_default ? $address->id : null) == $address->id)
                                            required
                                        >
                                        <strong>{{ $address->title ?: 'آدرس ارسال' }} @if($address->is_default) · پیش‌فرض @endif</strong>
                                        <span>{{ $address->full_name }} · {{ $address->phone }}</span>
                                        <span>{{ $address->province }}، {{ $address->city }} · {{ $address->address }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @endif

                        @error('address_id')
                            <p class="mt-2 text-xs font-bold text-[var(--color-danger-ink)]">{{ $message }}</p>
                        @enderror
                    </div>
                </section>

                <section class="store-panel">
                    <div class="store-panel__head">
                        <strong>۲. روش پرداخت</strong>
                        <span class="text-[10px] text-[var(--color-text-muted)]">امن و آنلاین</span>
                    </div>
                    <div class="store-panel__body">
                        <div class="store-checkout__pay">
                            <label>
                                <input type="radio" name="payment_method" value="gateway" @checked(old('payment_method', 'gateway') === 'gateway') required>
                                <span class="flex-1">
                                    <strong>پرداخت آنلاین</strong>
                                    <small class="mt-1 block text-[10px] text-[var(--color-text-muted)]">پس از ایجاد سفارش به درگاه پرداخت هدایت می‌شوی.</small>
                                </span>
                            </label>
                        </div>
                        @error('payment_method')
                            <p class="mt-2 text-xs font-bold text-[var(--color-danger-ink)]">{{ $message }}</p>
                        @enderror
                    </div>
                </section>

                <section class="store-panel">
                    <div class="store-panel__head">
                        <strong>۳. توضیحات سفارش</strong>
                        <span class="text-[10px] text-[var(--color-text-muted)]">اختیاری</span>
                    </div>
                    <div class="store-panel__body">
                        <div class="store-checkout__field">
                            <label for="checkout-notes">یادداشت برای سفارش</label>
                            <textarea id="checkout-notes" name="notes" rows="4" maxlength="2000" placeholder="زمان تحویل یا نکته‌ای که لازم است بدانیم...">{{ old('notes') }}</textarea>
                        </div>
                        @error('notes')
                            <p class="mt-2 text-xs font-bold text-[var(--color-danger-ink)]">{{ $message }}</p>
                        @enderror
                    </div>
                </section>
            </div>

            <aside class="store-summary">
                <h2>خلاصه سفارش</h2>

                <div class="mt-2">
                    @foreach($items->items as $item)
                        @php
                            $product = $item->product;
                            $image = $product?->primaryImage?->image;
                        @endphp
                        <div class="store-item !grid-cols-[56px_minmax(0,1fr)] !py-2.5">
                            <div class="store-item__media !w-14">
                                @if($image)
                                    <img src="{{ asset('storage/' . $image) }}" alt="" loading="lazy" decoding="async">
                                @else
                                    <div class="grid h-full place-items-center text-[8px] text-[var(--color-text-soft)]">—</div>
                                @endif
                            </div>
                            <div>
                                <h2>{{ $item->product_name ?? $product?->name }}</h2>
                                <p>{{ $item->quantity }} عدد · {{ number_format($item->unit_price) }} تومان</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="store-summary__row">
                    <span>جمع کالاها</span>
                    <strong>{{ number_format($summary['subtotal']) }} تومان</strong>
                </div>

                <div class="store-summary__row">
                    <span>تخفیف</span>
                    <strong>{{ $summary['discount'] > 0 ? number_format($summary['discount']) . ' تومان' : '—' }}</strong>
                </div>

                <div class="store-summary__row">
                    <span>هزینه ارسال</span>
                    <strong>{{ $summary['shipping'] > 0 ? number_format($summary['shipping']) . ' تومان' : 'رایگان' }}</strong>
                </div>

                <div class="store-summary__total">
                    <span>مبلغ نهایی</span>
                    <div class="text-left">
                        <strong>{{ number_format($summary['total']) }}</strong>
                        <small>تومان</small>
                    </div>
                </div>

                <div class="store-checkout__actions">
                    <a href="{{ route('customer.cart.index') }}">بازبینی سبد</a>
                    <button type="submit" @disabled($addresses->isEmpty())>ثبت سفارش و پرداخت</button>
                </div>

                @if($addresses->isEmpty())
                    <p class="mt-2 text-center text-[10px] font-bold text-[var(--color-danger-ink)]">برای ثبت سفارش، ابتدا یک آدرس اضافه کن.</p>
                @endif

                <p class="mt-3 text-center text-[10px] leading-6 text-[var(--color-text-muted)]">
                    با ثبت سفارش، موجودی و قیمت محصولات دوباره در سمت سرور کنترل می‌شود.
                </p>
            </aside>
        </form>
    </div>
</div>
@endsection
