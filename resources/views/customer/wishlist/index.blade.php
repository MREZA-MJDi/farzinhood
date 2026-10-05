@extends('layouts.app')

@section('title', 'علاقه‌مندی‌های من | فرزین')
@section('meta_description', 'محصولات ذخیره‌شده در علاقه‌مندی‌های حساب کاربری فرزین')

@section('content')
    <section class="farzin-container py-8 sm:py-10 lg:py-12">
        <header class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <span class="farzin-eyebrow">FARZIN / WISHLIST</span>
                <h1 class="farzin-page-title mt-3">علاقه‌مندی‌های من</h1>
                <p class="farzin-section-description mt-3 max-w-2xl">
                    محصولاتی که برای بعد ذخیره کرده‌ای را اینجا یک‌جا می‌بینی.
                </p>
            </div>

            <a href="{{ route('shop.index') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[var(--color-brand-900)] px-5 py-3.5 text-sm font-black text-white transition hover:-translate-y-0.5 hover:bg-[var(--color-brand-950)]">
                کشف محصولات
                <span aria-hidden="true">←</span>
            </a>
        </header>

        @if(session('success'))
            <div class="mt-6 rounded-2xl border border-[var(--color-success-100)] bg-[var(--color-success-50)] px-4 py-3 text-sm font-bold text-[var(--color-success-700)]">
                {{ session('success') }}
            </div>
        @endif

        @if($wishlist->isEmpty())
            <div class="mt-8 rounded-[2rem] border border-dashed border-[var(--color-border-strong)] bg-white px-6 py-20 text-center">
                <div class="mx-auto flex size-16 items-center justify-center rounded-2xl bg-[var(--color-earth-100)] text-[var(--color-earth-800)]">
                    <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <path d="M20.8 8.7c0 5.2-8.8 10.3-8.8 10.3S3.2 13.9 3.2 8.7A4.7 4.7 0 0 1 12 6.2a4.7 4.7 0 0 1 8.8 2.5Z"/>
                    </svg>
                </div>
                <h2 class="mt-5 text-xl font-black">هنوز چیزی ذخیره نکردی</h2>
                <p class="mx-auto mt-2 max-w-md text-sm leading-7 text-[var(--color-text-secondary)]">
                    از فروشگاه محصول موردعلاقه‌ات را پیدا کن و برای تصمیم‌گیری بعدی نگهش دار.
                </p>
                <a href="{{ route('shop.index') }}" class="mt-6 inline-flex rounded-2xl bg-[var(--color-accent-600)] px-6 py-3.5 text-sm font-black text-white transition hover:bg-[var(--color-accent-700)]">
                    رفتن به فروشگاه
                </a>
            </div>
        @else
            <div class="mt-8 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4 xl:gap-5">
                @foreach($wishlist as $item)
                    @php
                        $product = $item->product;
                        $available = $product?->is_active && $product->stock > 0;
                    @endphp

                    @if($product)
                        <article class="group overflow-hidden rounded-[1.75rem] border border-[var(--color-border)] bg-white shadow-[var(--shadow-xs)] transition hover:-translate-y-1 hover:border-[var(--color-earth-300)] hover:shadow-[var(--shadow-md)]">
                            <div class="relative">
                                <form action="{{ route('customer.wishlist.toggle', $product) }}" method="POST" class="absolute left-3 top-3 z-20">
                                    @csrf
                                    <button type="submit" class="flex size-9 items-center justify-center rounded-xl border border-white/80 bg-white/95 text-[var(--color-accent-600)] shadow-md backdrop-blur transition hover:bg-[var(--color-accent-50)]" aria-label="حذف {{ $product->name }} از علاقه‌مندی‌ها" title="حذف از علاقه‌مندی‌ها">
                                        <svg class="size-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <path d="m12 21-1.7-1.5C5.3 15 2 12 2 8.5 2 5.5 4.4 3 7.4 3c1.7 0 3.3.8 4.6 2.1A6.2 6.2 0 0 1 16.6 3C19.6 3 22 5.5 22 8.5c0 3.5-3.3 6.5-8.3 11Z"/>
                                        </svg>
                                    </button>
                                </form>

                                <a href="{{ route('products.show', $product) }}" class="block">
                                    <div class="aspect-[0.96] overflow-hidden bg-[var(--color-earth-50)]">
                                        @if($product->primaryImage?->image)
                                            <img src="{{ $product->primaryImage->url }}" alt="{{ $product->name }}" class="size-full object-cover transition duration-700 group-hover:scale-105" loading="lazy" decoding="async">
                                        @else
                                            <div class="flex size-full items-center justify-center text-[var(--color-text-soft)]">
                                                <svg class="size-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.15" aria-hidden="true">
                                                    <rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="9" r="1.4"/><path d="m21 15-5-5-4.5 4.5-2.5-2.5L3 18"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                            </div>

                            <div class="p-4">
                                @if($product->category)
                                    <span class="text-[10px] font-black uppercase tracking-[0.15em] text-[var(--color-earth-700)]">{{ $product->category->name }}</span>
                                @endif

                                <a href="{{ route('products.show', $product) }}" class="mt-2 block text-sm font-black leading-7 text-[var(--color-text-primary)] hover:text-[var(--color-brand-900)]">
                                    {{ $product->name }}
                                </a>

                                <div class="mt-4 flex items-end justify-between gap-3">
                                    <div>
                                        @if($product->old_price && $product->old_price > $product->price)
                                            <del class="block text-[10px] text-[var(--color-text-soft)]">{{ number_format($product->old_price) }}</del>
                                        @endif
                                        <strong class="mt-1 block text-base font-black text-[var(--color-brand-950)]">
                                            {{ number_format($product->price) }}
                                            <span class="text-[10px] text-[var(--color-text-muted)]">تومان</span>
                                        </strong>
                                    </div>

                                    @if($available)
                                        <form action="{{ route('customer.cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="flex size-10 items-center justify-center rounded-xl bg-[var(--color-accent-600)] text-white transition hover:-translate-y-0.5 hover:bg-[var(--color-accent-700)]" aria-label="افزودن {{ $product->name }} به سبد خرید" title="افزودن به سبد خرید">
                                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                                    <path d="M3 4h2l2.4 11.2a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 2-1.6L21 7H6"/><circle cx="10" cy="20" r="1"/><circle cx="18" cy="20" r="1"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="rounded-xl bg-[var(--color-neutral-100)] px-3 py-2.5 text-[9px] font-black text-[var(--color-text-muted)]">ناموجود</span>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endif
                @endforeach
            </div>
        @endif
    </section>
@endsection
