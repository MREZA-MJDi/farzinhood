@extends('layouts.app')

@section('title', 'علاقه‌مندی‌های من | فرزین')

@section('meta_description', 'محصولات موردعلاقه شما در فرزین')

@section('content')

    <div class="bg-[var(--color-neutral-50)]">

        <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">

            {{-- =========================================================
                HEADER
            ========================================================== --}}

            <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">

                <div>

                    <div class="inline-flex items-center gap-2 text-[11px] font-black uppercase tracking-[0.24em] text-[var(--color-accent-600)]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[var(--color-accent-600)]"></span>
                        Wishlist
                    </div>

                    <h1 class="mt-3 text-3xl font-black tracking-tight text-[var(--color-text-primary)] sm:text-4xl">
                        علاقه‌مندی‌های من
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-7 text-[var(--color-text-secondary)]">
                        محصولاتی که ذخیره کرده‌ای تا هر زمان خواستی دوباره سراغشان برگردی.
                    </p>

                </div>


                @if($wishlist->count())

                    <div class="inline-flex w-fit items-center gap-2 rounded-full bg-[var(--color-brand-50)] px-4 py-2 text-xs font-black text-[var(--color-brand-900)]">

                        <svg
                            class="h-4 w-4"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            aria-hidden="true"
                        >
                            <path d="M20.8 8.9c0 5.1-8.8 10.2-8.8 10.2S3.2 14 3.2 8.9A5 5 0 0 1 8.1 4c1.5 0 3 .7 3.9 1.9A4.9 4.9 0 0 1 15.9 4a5 5 0 0 1 4.9 4.9Z"/>
                        </svg>

                        {{ number_format($wishlist->count()) }}
                        محصول

                    </div>

                @endif

            </div>


            {{-- =========================================================
                EMPTY STATE
            ========================================================== --}}

            @if($wishlist->isEmpty())

                <div class="mt-10 overflow-hidden rounded-[2rem] border border-[var(--color-border)] bg-white shadow-[var(--shadow-xs)]">

                    <div class="relative flex flex-col items-center overflow-hidden px-6 py-20 text-center sm:py-28">

                        {{-- Decorative --}}
                        <div class="pointer-events-none absolute -right-20 -top-20 h-48 w-48 rounded-full bg-[var(--color-accent-600)]/5 blur-3xl"></div>
                        <div class="pointer-events-none absolute -bottom-20 -left-20 h-48 w-48 rounded-full bg-[var(--color-brand-900)]/5 blur-3xl"></div>


                        <div class="relative flex h-20 w-20 items-center justify-center rounded-[1.75rem] bg-[var(--color-brand-50)] text-[var(--color-brand-900)]">

                            <svg
                                class="h-10 w-10"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                aria-hidden="true"
                            >
                                <path d="M20.8 8.9c0 5.1-8.8 10.2-8.8 10.2S3.2 14 3.2 8.9A5 5 0 0 1 8.1 4c1.5 0 3 .7 3.9 1.9A4.9 4.9 0 0 1 15.9 4a5 5 0 0 1 4.9 4.9Z"/>
                            </svg>

                        </div>


                        <h2 class="relative mt-6 text-2xl font-black text-[var(--color-text-primary)]">
                            هنوز چیزی ذخیره نکرده‌ای
                        </h2>

                        <p class="relative mt-3 max-w-md text-sm leading-7 text-[var(--color-text-secondary)]">
                            هر محصولی که دوست داشتی به علاقه‌مندی‌ها اضافه کن تا
                            بعداً سریع و راحت پیدایش کنی.
                        </p>


                        <a
                            href="{{ route('shop.index') }}"
                            class="relative mt-7 inline-flex items-center gap-3 rounded-2xl bg-[var(--color-accent-600)] px-6 py-4 text-sm font-black text-white shadow-lg shadow-[var(--color-accent-600)]/15 transition duration-300 hover:-translate-y-0.5 hover:bg-[var(--color-accent-700)]"
                        >
                            کشف محصولات

                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                aria-hidden="true"
                            >
                                <path d="m9 18 6-6-6-6"/>
                            </svg>
                        </a>

                    </div>

                </div>

            @else

                {{-- =====================================================
                    WISHLIST GRID
                ====================================================== --}}

                <div class="mt-10">

                    <div class="grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-3 lg:grid-cols-4">

                        @foreach($wishlist as $item)

                            @php
                                $product = $item->product;
                                $image = $product?->primaryImage?->image;

                                $isAvailable =
                                    $product &&
                                    $product->is_active &&
                                    $product->stock > 0;

                                $hasOldPrice =
                                    $product &&
                                    $product->old_price !== null &&
                                    $product->old_price > $product->price;
                            @endphp


                            @if($product)

                                <article class="group min-w-0">

                                    {{-- =================================================
                                        CARD IMAGE
                                    ================================================== --}}

                                    <div class="relative overflow-hidden rounded-[1.75rem] border border-[var(--color-border)] bg-white shadow-[var(--shadow-xs)] transition duration-300 hover:-translate-y-1 hover:border-[var(--color-border-strong)] hover:shadow-[var(--shadow-md)]">


                                        {{-- Remove --}}
                                        <form
                                            action="{{ route('customer.wishlist.toggle', $product) }}"
                                            method="POST"
                                            class="absolute right-3 top-3 z-30"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                class="flex h-10 w-10 items-center justify-center rounded-xl border border-white/70 bg-white/95 text-[var(--color-accent-600)] shadow-lg backdrop-blur transition duration-200 hover:bg-[var(--color-accent-50)] hover:text-[var(--color-accent-700)]"
                                                title="حذف از علاقه‌مندی‌ها"
                                                aria-label="حذف {{ $product->name }} از علاقه‌مندی‌ها"
                                            >
                                                <svg
                                                    class="h-4.5 w-4.5"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="1.7"
                                                    aria-hidden="true"
                                                >
                                                    <path d="M20.8 8.9c0 5.1-8.8 10.2-8.8 10.2S3.2 14 3.2 8.9A5 5 0 0 1 8.1 4c1.5 0 3 .7 3.9 1.9A4.9 4.9 0 0 1 15.9 4a5 5 0 0 1 4.9 4.9Z"/>
                                                </svg>
                                            </button>
                                        </form>


                                        {{-- Discount --}}
                                        @if($product->discount > 0)

                                            <div class="absolute left-3 top-3 z-20 rounded-full bg-[var(--color-accent-600)] px-3 py-1.5 text-[10px] font-black text-white shadow-lg shadow-[var(--color-accent-600)]/20">
                                                {{ $product->discount }}٪ تخفیف
                                            </div>

                                        @elseif($product->is_featured)

                                            <div class="absolute left-3 top-3 z-20 inline-flex items-center gap-1 rounded-full bg-[var(--color-brand-900)] px-3 py-1.5 text-[10px] font-black text-white shadow-lg">

                                                <svg
                                                    class="h-3 w-3"
                                                    viewBox="0 0 24 24"
                                                    fill="currentColor"
                                                    aria-hidden="true"
                                                >
                                                    <path d="m12 2 2.7 6.3L21 11l-6.3 2.7L12 20l-2.7-6.3L3 11l6.3-2.7L12 2Z"/>
                                                </svg>

                                                منتخب
                                            </div>

                                        @endif


                                        {{-- Product Link --}}
                                        <a
                                            href="{{ route('products.show', $product) }}"
                                            class="block"
                                        >

                                            <div class="relative aspect-[0.96] overflow-hidden bg-[#eef0f3]">

                                                {{-- Material background --}}
                                                <div class="pointer-events-none absolute -right-10 -top-10 z-[1] h-32 w-32 rounded-full bg-white/60 blur-2xl"></div>

                                                @if($image)

                                                    <img
                                                        src="{{ asset('storage/' . $image) }}"
                                                        alt="{{ $product->name }}"
                                                        class="relative z-0 h-full w-full object-cover transition duration-700 ease-out group-hover:scale-[1.045]"
                                                        loading="lazy"
                                                        decoding="async"
                                                    >

                                                @else

                                                    <div class="relative z-0 flex h-full items-center justify-center text-[var(--color-neutral-400)]">

                                                        <svg
                                                            class="h-14 w-14"
                                                            viewBox="0 0 24 24"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            stroke-width="1.2"
                                                            aria-hidden="true"
                                                        >
                                                            <rect x="3" y="4" width="18" height="16" rx="2"/>
                                                            <circle cx="8.5" cy="9" r="1.4"/>
                                                            <path d="m21 15-5-5-4.5 4.5-2.5-2.5L3 18"/>
                                                        </svg>

                                                    </div>

                                                @endif


                                                {{-- Unavailable --}}
                                                @if(!$isAvailable)

                                                    <div class="absolute inset-0 z-10 flex items-center justify-center bg-[var(--color-brand-950)]/10">

                                                    <span class="rounded-full bg-white/95 px-3.5 py-2 text-[10px] font-black text-[var(--color-brand-950)] shadow-lg backdrop-blur">
                                                        فعلاً موجود نیست
                                                    </span>

                                                    </div>

                                                @elseif($product->stock <= 5)

                                                    <div class="absolute bottom-3 right-3 z-20 rounded-full border border-white/70 bg-white/90 px-3 py-1.5 text-[10px] font-black text-[var(--color-text-secondary)] shadow-sm backdrop-blur">
                                                        فقط {{ $product->stock }} عدد
                                                    </div>

                                                @endif


                                                {{-- Hover CTA --}}
                                                <div class="pointer-events-none absolute inset-x-3 bottom-3 z-20 translate-y-3 opacity-0 transition duration-300 group-hover:translate-y-0 group-hover:opacity-100">

                                                    <div class="flex items-center justify-center gap-2 rounded-xl border border-white/70 bg-white/95 px-4 py-3 text-xs font-black text-[var(--color-brand-900)] shadow-xl backdrop-blur">

                                                        مشاهده محصول

                                                        <svg
                                                            class="h-4 w-4"
                                                            viewBox="0 0 24 24"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            stroke-width="1.8"
                                                            aria-hidden="true"
                                                        >
                                                            <path d="m9 18 6-6-6-6"/>
                                                        </svg>

                                                    </div>

                                                </div>

                                            </div>

                                        </a>

                                    </div>


                                    {{-- =================================================
                                        CONTENT
                                    ================================================== --}}

                                    <div class="px-1 pt-4">

                                        @if($product->category)

                                            <a
                                                href="{{ route('categories.show', $product->category) }}"
                                                class="block truncate text-[11px] font-bold text-[var(--color-text-muted)] transition hover:text-[var(--color-accent-600)]"
                                            >
                                                {{ $product->category->name }}
                                            </a>

                                        @endif


                                        <a
                                            href="{{ route('products.show', $product) }}"
                                            class="mt-1 block"
                                        >

                                            <h2 class="line-clamp-2 min-h-[3rem] text-sm font-black leading-7 text-[var(--color-text-primary)] transition hover:text-[var(--color-brand-900)]">
                                                {{ $product->name }}
                                            </h2>

                                        </a>


                                        {{-- Rating --}}
                                        @if($product->review_count > 0)

                                            <div class="mt-2 flex items-center gap-1.5 text-[11px] font-bold text-[var(--color-text-muted)]">

                                                <svg
                                                    class="h-3.5 w-3.5 text-amber-500"
                                                    viewBox="0 0 24 24"
                                                    fill="currentColor"
                                                    aria-hidden="true"
                                                >
                                                    <path d="m12 2.8 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2-5.6-3-5.6 3 1.1-6.2L3 9.4l6.2-.9L12 2.8Z"/>
                                                </svg>

                                                <span>
                                                {{ number_format($product->rating, 1) }}
                                            </span>

                                                <span class="text-[var(--color-text-soft)]">
                                                ({{ number_format($product->review_count) }})
                                            </span>

                                            </div>

                                        @endif


                                        {{-- Price + cart --}}
                                        <div class="mt-4 flex items-end justify-between gap-3">

                                            <div class="min-w-0">

                                                @if($hasOldPrice)

                                                    <div class="mb-1 flex items-center gap-2">

                                                    <span class="text-[11px] text-[var(--color-text-soft)] line-through">
                                                        {{ number_format($product->old_price) }}
                                                    </span>

                                                        @if($product->discount > 0)

                                                            <span class="rounded-md bg-[var(--color-accent-50)] px-1.5 py-0.5 text-[9px] font-black text-[var(--color-accent-700)]">
                                                            {{ $product->discount }}٪
                                                        </span>

                                                        @endif

                                                    </div>

                                                @endif


                                                <div class="flex items-baseline gap-1">

                                                <span class="text-base font-black tracking-tight text-[var(--color-brand-950)] sm:text-lg">
                                                    {{ number_format($product->price) }}
                                                </span>

                                                    <span class="text-[10px] font-bold text-[var(--color-text-muted)]">
                                                    تومان
                                                </span>

                                                </div>

                                            </div>


                                            {{-- Add to cart --}}
                                            @if($isAvailable)

                                                <form
                                                    action="{{ route('customer.cart.add') }}"
                                                    method="POST"
                                                >
                                                    @csrf

                                                    <input
                                                        type="hidden"
                                                        name="product_id"
                                                        value="{{ $product->id }}"
                                                    >

                                                    <input
                                                        type="hidden"
                                                        name="quantity"
                                                        value="1"
                                                    >

                                                    <button
                                                        type="submit"
                                                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-[var(--color-accent-600)] text-white shadow-md shadow-[var(--color-accent-600)]/10 transition duration-200 hover:-translate-y-0.5 hover:bg-[var(--color-accent-700)]"
                                                        title="افزودن به سبد خرید"
                                                        aria-label="افزودن {{ $product->name }} به سبد خرید"
                                                    >

                                                        <svg
                                                            class="h-4.5 w-4.5"
                                                            viewBox="0 0 24 24"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            stroke-width="1.7"
                                                            aria-hidden="true"
                                                        >
                                                            <path d="M3 4h2l2.4 11.2a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 2-1.6L21 7H6"/>
                                                            <circle cx="10" cy="20" r="1"/>
                                                            <circle cx="18" cy="20" r="1"/>
                                                        </svg>

                                                    </button>

                                                </form>

                                            @else

                                                <span class="rounded-xl bg-[var(--color-neutral-100)] px-3 py-2.5 text-[10px] font-black text-[var(--color-text-muted)]">
                                                ناموجود
                                            </span>

                                            @endif

                                        </div>

                                    </div>

                                </article>

                            @endif

                        @endforeach

                    </div>

                </div>

            @endif

        </section>

    </div>

@endsection
