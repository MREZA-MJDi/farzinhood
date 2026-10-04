@extends('layouts.app')

@section('title', 'سبد خرید | فرزین')
@section('meta_description', 'سبد خرید شما در فروشگاه فرزین')

@section('content')
@php
    $cartItems = $items->items ?? collect();
    $hasUnavailable = $cartItems->contains(function ($item) {
        $product = $item->product;
        return ! $product || ! $product->is_active || $product->stock < $item->quantity;
    });
@endphp

<div class="store-transaction">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="store-transaction__head">
            <div>
                <span class="product-v2__eyebrow">FARZIN / CART</span>
                <h1>سبد خرید شما</h1>
                <p>{{ number_format($itemCount) }} آیتم انتخاب شده؛ قبل از ثبت سفارش، تعداد و قیمت را بررسی کن.</p>
            </div>

            @include('partials.back-link', [
                'href' => route('shop.index'),
                'label' => 'ادامه خرید',
            ])
        </div>

        @if($cartItems->isEmpty())
            <div class="store-empty mt-4">
                <div class="store-empty__icon">🛒</div>
                <h2>سبد خریدت خالیه</h2>
                <p>هنوز محصولی انتخاب نکردی. از فروشگاه شروع کن و هر چیزی را که لازم داری به سبد اضافه کن.</p>
                <a href="{{ route('shop.index') }}">رفتن به فروشگاه ←</a>
            </div>
        @else
            <div class="store-transaction__layout">
                <section class="store-panel">
                    <div class="store-panel__head">
                        <strong>محصولات انتخاب‌شده</strong>
                        <span class="text-[10px] text-[var(--color-text-muted)]">{{ number_format($itemCount) }} عدد</span>
                    </div>

                    <div class="store-panel__body">
                        @foreach($cartItems as $item)
                            @php
                                $product = $item->product;
                                $image = $product?->primaryImage?->image;
                                $maxQuantity = min($product?->stock ?? 1, 99);
                                $available = $product && $product->is_active && $product->stock >= $item->quantity;
                                $lineTotal = (int) $item->unit_price * (int) $item->quantity;
                            @endphp

                            <article class="store-item">
                                <a href="{{ $product ? route('products.show', $product) : route('shop.index') }}" class="store-item__media">
                                    @if($image)
                                        <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}" loading="lazy" decoding="async">
                                    @else
                                        <div class="grid h-full place-items-center text-[10px] text-[var(--color-text-soft)]">بدون تصویر</div>
                                    @endif
                                </a>

                                <div class="min-w-0">
                                    @if($product?->category)
                                        <a class="text-[9px] font-black text-[var(--color-accent-600)]" href="{{ route('categories.show', $product->category) }}">{{ $product->category->name }}</a>
                                    @endif
                                    <h2 class="mt-1">{{ $item->product_name ?? $product?->name ?? 'محصول حذف‌شده' }}</h2>
                                    <p>
                                        {{ $item->product_sku ? 'SKU / ' . $item->product_sku . ' · ' : '' }}
                                        {{ number_format($item->unit_price) }} تومان
                                    </p>

                                    <div class="mt-3 flex flex-wrap items-center gap-2">
                                        <form action="{{ route('customer.cart.update', $product) }}" method="POST" class="store-qty" data-auto-submit-quantity>
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="product_id" value="{{ $product?->id }}">
                                            <button type="button" data-quantity-action="decrease" aria-label="کاهش تعداد">−</button>
                                            <input
                                                type="number"
                                                name="quantity"
                                                value="{{ $item->quantity }}"
                                                min="1"
                                                max="{{ max(1, $maxQuantity) }}"
                                                inputmode="numeric"
                                                aria-label="تعداد {{ $item->product_name ?? $product?->name }}"
                                                data-quantity-input
                                            >
                                            <button type="button" data-quantity-action="increase" aria-label="افزایش تعداد">+</button>
                                        </form>

                                        <form action="{{ route('customer.cart.remove', $product) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="store-remove" type="submit">حذف</button>
                                        </form>
                                    </div>

                                    @unless($available)
                                        <p class="mt-2 font-bold text-[var(--color-danger-ink)]">موجودی این محصول برای تعداد انتخاب‌شده کافی نیست؛ تعداد را کم کن یا محصول را حذف کن.</p>
                                    @endunless
                                </div>

                                <div class="store-item__price">
                                    {{ number_format($lineTotal) }}
                                    <small>تومان</small>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>

                <aside class="store-summary">
                    <h2>خلاصه سبد</h2>
                    <div class="store-summary__row">
                        <span>تعداد</span>
                        <strong>{{ number_format($itemCount) }}</strong>
                    </div>
                    <div class="store-summary__row">
                        <span>جمع کالاها</span>
                        <strong>{{ number_format($subtotal) }} تومان</strong>
                    </div>

                    @if($hasUnavailable)
                        <div class="mt-3 rounded-xl border border-[#eed0c8] bg-[var(--color-danger-surface)] px-3 py-3 text-[10px] font-bold leading-6 text-[var(--color-danger-ink)]">
                            برای ادامه خرید، موجودی محصولات سبد را اصلاح کن.
                        </div>
                    @endif

                    <div class="store-summary__total">
                        <span>مبلغ فعلی سبد</span>
                        <div class="text-left">
                            <strong>{{ number_format($subtotal) }}</strong>
                            <small>تومان</small>
                        </div>
                    </div>

                    <div class="store-checkout__actions">
                        <a href="{{ route('shop.index') }}">ادامه خرید</a>
                        @if($hasUnavailable)
                            <button type="button" disabled class="opacity-50">اصلاح سبد لازم است</button>
                        @else
                            <a href="{{ route('customer.checkout.index') }}" class="!border-0 !bg-[var(--color-accent-600)] !text-white">ادامه به تسویه</a>
                        @endif
                    </div>

                    <form action="{{ route('customer.cart.clear') }}" method="POST" class="mt-2" onsubmit="return confirm('سبد خرید پاک شود؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full min-h-9 rounded-lg border border-[var(--color-border)] bg-transparent text-[10px] font-bold text-[var(--color-text-muted)] transition hover:border-[var(--color-danger-ink)] hover:text-[var(--color-danger-ink)]">
                            پاک کردن کل سبد
                        </button>
                    </form>
                </aside>
            </div>
        @endif
    </div>
</div>
@endsection
