@extends('layouts.app')
@section('title', 'سبد خرید | فرزین')
@section('content')
<section class="farzin-catalog">
    <div class="farzin-container">
        <header class="farzin-page-head">
            <p class="farzin-kicker">FARZIN / SHOPPING CART</p>
            <h1>سبد خرید شما</h1>
            <p>پیش از ادامه، محصولات و تعداد انتخاب‌شده را بررسی کن.</p>
        </header>

        @if($items->items->isNotEmpty())
            <div class="mt-8 grid items-start gap-6 lg:grid-cols-[minmax(0,1fr)_340px]">
                <section class="overflow-hidden rounded-2xl border border-[var(--color-border)] bg-white">
                    @foreach($items->items as $item)
                        @php($product = $item->product)
                        @if($product)
                            <article class="grid grid-cols-[88px_minmax(0,1fr)] gap-4 border-b border-[var(--color-border)] p-4 last:border-b-0 sm:grid-cols-[112px_minmax(0,1fr)] sm:p-6">
                                <a href="{{ route('products.show', $product) }}" class="flex aspect-[4/5] items-center justify-center overflow-hidden bg-[var(--color-surface-muted)]">
                                    @if($product->primaryImage)
                                        @php($imagePath = $product->primaryImage->image)
                                        <img src="{{ str_starts_with($imagePath,'http://') || str_starts_with($imagePath,'https://') || str_starts_with($imagePath,'/') ? $imagePath : asset('storage/'.ltrim($imagePath,'/')) }}" alt="{{ $product->primaryImage->alt ?: $product->name }}" width="400" height="500" loading="lazy" decoding="async" class="h-full w-full object-cover">
                                    @else
                                        <span class="text-3xl font-black text-[var(--color-text-soft)]">F.</span>
                                    @endif
                                </a>
                                <div class="min-w-0">
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-[var(--color-text-muted)]">{{ $product->brand ?: 'FARZIN / PRODUCT' }}</p>
                                    <h2 class="mt-2 font-black"><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></h2>
                                    <p class="mt-2 text-sm font-bold">{{ \App\Support\JalaliCalendar::number($item->unit_price) }} تومان</p>
                                    <p class="mt-1 text-xs text-[var(--color-text-muted)]">جمع این کالا: {{ \App\Support\JalaliCalendar::number($item->unit_price * $item->quantity) }} تومان</p>
                                    <div class="mt-4 flex flex-wrap items-end gap-3">
                                        <form action="{{ route('customer.cart.update', $product) }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <label for="cart-quantity-{{ $product->id }}" class="text-xs font-bold">تعداد</label>
                                            <input id="cart-quantity-{{ $product->id }}" type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ min(99, max(1, (int) $product->stock)) }}" required inputmode="numeric" class="w-20 rounded-lg border border-[var(--color-border)] px-2 py-2 text-sm">
                                            <button type="submit" class="rounded-lg border border-[var(--color-border)] px-3 py-2 text-xs font-bold hover:bg-[var(--color-surface-soft)]">به‌روزرسانی</button>
                                        </form>
                                        <form action="{{ route('customer.cart.remove', $product) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs font-bold text-red-700 underline underline-offset-4">حذف</button>
                                        </form>
                                    </div>
                                </div>
                            </article>
                        @endif
                    @endforeach
                    <div class="flex justify-end border-t border-[var(--color-border)] p-4 sm:p-5">
                        <form action="{{ route('customer.cart.clear') }}" method="POST" onsubmit="return confirm('سبد خرید خالی شود؟')">
                            @csrf @method('DELETE')
                            <button class="text-xs font-bold text-[var(--color-text-muted)] underline underline-offset-4" type="submit">خالی کردن سبد</button>
                        </form>
                    </div>
                </section>

                <aside class="rounded-2xl border border-[var(--color-border)] bg-white p-5 sm:p-6 lg:sticky lg:top-24">
                    <p class="farzin-kicker !mb-1">ORDER SUMMARY</p>
                    <h2 class="text-lg font-black">خلاصه سبد</h2>
                    <div class="mt-5 flex justify-between gap-3 border-b border-[var(--color-border)] py-3 text-sm"><span class="text-[var(--color-text-muted)]">تعداد کالا</span><strong>{{ \App\Support\JalaliCalendar::number($itemCount) }}</strong></div>
                    <div class="flex justify-between gap-3 py-4"><span class="text-sm font-bold">جمع کالاها</span><strong class="text-lg">{{ \App\Support\JalaliCalendar::number($subtotal) }} <small class="text-xs font-normal">تومان</small></strong></div>
                    <p class="text-xs leading-6 text-[var(--color-text-muted)]">هزینه نهایی و هزینه ارسال در مرحله تکمیل سفارش محاسبه و نمایش داده می‌شود.</p>
                    <a href="{{ route('customer.checkout.index') }}" class="farzin-button mt-5 flex w-full justify-center">ادامه و تکمیل سفارش <span aria-hidden="true">↗</span></a>
                    <a href="{{ route('shop.index') }}" class="mt-4 block text-center text-xs font-bold underline underline-offset-4">ادامه خرید</a>
                </aside>
            </div>
        @else
            <div class="farzin-empty farzin-empty--large mt-8">
                <span class="farzin-empty__mark">F.</span>
                <h2>سبد خرید شما خالی است</h2>
                <p>از فروشگاه محصولی انتخاب کن تا اینجا نمایش داده شود.</p>
                <a href="{{ route('shop.index') }}" class="farzin-button">مشاهده فروشگاه <span aria-hidden="true">↗</span></a>
            </div>
        @endif
    </div>
</section>
@endsection
