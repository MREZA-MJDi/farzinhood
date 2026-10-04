@extends('layouts.app')

@section('title', 'علاقه‌مندی‌ها | فرزین')
@section('meta_description', 'محصولات ذخیره‌شده شما در فرزین')

@section('content')
<div class="store-transaction">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="store-transaction__head">
            <div>
                <span class="product-v2__eyebrow">FARZIN / WISHLIST</span>
                <h1>علاقه‌مندی‌های من</h1>
                <p>{{ number_format($wishlist->count()) }} محصول ذخیره شده؛ انتخاب‌های خوبت را برای بعد نگه دار.</p>
            </div>

            @include('partials.back-link', ['href' => route('shop.index'), 'label' => 'بازگشت به فروشگاه'])
        </div>

        @if($wishlist->isEmpty())
            <div class="store-empty mt-4">
                <div class="store-empty__icon">♡</div>
                <h2>هنوز چیزی ذخیره نکردی</h2>
                <p>وقتی محصولی را برای بعد نگه داری، اینجا در دسترس خواهد بود.</p>
                <a href="{{ route('shop.index') }}">کشف محصولات ←</a>
            </div>
        @else
            <section class="mt-4">
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                    @foreach($wishlist as $item)
                        @php
                            $product = $item->product;
                            $image = $product?->primaryImage?->image;
                            $available = $product && $product->is_active && $product->stock > 0;
                            $oldPrice = $product?->old_price && $product->old_price > $product->price;
                        @endphp

                        @if($product)
                            <article class="group overflow-hidden rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface)] shadow-[var(--shadow-xs)] transition duration-300 hover:-translate-y-1 hover:shadow-[var(--shadow-sm)]">
                                <div class="relative aspect-[4/5] overflow-hidden bg-[var(--color-neutral-100)]">
                                    <a href="{{ route('products.show', $product) }}" class="block h-full w-full">
                                        @if($image)
                                            <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.035]" loading="lazy" decoding="async">
                                        @else
                                            <div class="grid h-full place-items-center text-xs text-[var(--color-text-soft)]">بدون تصویر</div>
                                        @endif
                                    </a>

                                    @if($product->discount > 0)
                                        <span class="absolute right-3 top-3 rounded-full bg-[var(--color-accent-600)] px-2.5 py-1.5 text-[9px] font-black text-white">{{ $product->discount }}٪</span>
                                    @endif

                                    <form action="{{ route('customer.wishlist.toggle', $product) }}" method="POST" class="absolute left-3 top-3">
                                        @csrf
                                        <button type="submit" class="grid h-9 w-9 place-items-center rounded-full border border-white/70 bg-white/90 text-sm text-[var(--color-danger-ink)] shadow-sm backdrop-blur" aria-label="حذف {{ $product->name }} از علاقه‌مندی‌ها">♥</button>
                                    </form>
                                </div>

                                <div class="p-3.5">
                                    @if($product->category)
                                        <a class="text-[9px] font-black text-[var(--color-accent-600)]" href="{{ route('categories.show', $product->category) }}">{{ $product->category->name }}</a>
                                    @endif
                                    <a href="{{ route('products.show', $product) }}" class="mt-1 block">
                                        <h2 class="line-clamp-2 text-sm font-black leading-6 text-[var(--color-text-primary)]">{{ $product->name }}</h2>
                                    </a>

                                    <div class="mt-3">
                                        @if($oldPrice)
                                            <span class="block text-[10px] text-[var(--color-text-soft)] line-through">{{ number_format($product->old_price) }}</span>
                                        @endif
                                        <div class="mt-0.5 flex items-baseline gap-1">
                                            <strong class="text-base font-black text-[var(--color-brand-950)]">{{ number_format($product->price) }}</strong>
                                            <span class="text-[9px] font-bold text-[var(--color-text-muted)]">تومان</span>
                                        </div>
                                    </div>

                                    @if($available)
                                        <form action="{{ route('customer.cart.add') }}" method="POST" class="mt-3">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="flex min-h-10 w-full items-center justify-center rounded-xl bg-[var(--color-brand-900)] px-3 text-[10px] font-black text-white transition hover:bg-[var(--color-brand-950)]">افزودن به سبد</button>
                                        </form>
                                    @else
                                        <div class="mt-3 flex min-h-10 items-center justify-center rounded-xl bg-[var(--color-neutral-100)] px-3 text-[10px] font-bold text-[var(--color-text-muted)]">ناموجود</div>
                                    @endif
                                </div>
                            </article>
                        @endif
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</div>
@endsection
