@extends('layouts.app')

@section('title', $product->meta_title ?: $product->name . ' | فرزین')

@section('meta_description', $product->meta_description ?: ($product->short_description ?: $product->name))

@section('canonical_url', $product->canonical_url ?: url()->current())

@section('content')

    <div class="bg-[var(--color-neutral-50)]">

        {{-- =========================================================
            MAIN PRODUCT
        ========================================================== --}}

        <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">

            {{-- =====================================================
                Breadcrumb
            ====================================================== --}}

            <nav
                class="flex flex-wrap items-center gap-x-2 gap-y-1 text-xs text-[var(--color-text-muted)]"
                aria-label="مسیر صفحه"
            >

                <a
                    href="{{ route('home') }}"
                    class="transition hover:text-[var(--color-accent-600)]"
                >
                    خانه
                </a>

                <svg
                    class="h-3.5 w-3.5 text-[var(--color-neutral-400)]"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    aria-hidden="true"
                >
                    <path d="m9 18 6-6-6-6"/>
                </svg>

                <a
                    href="{{ route('shop.index') }}"
                    class="transition hover:text-[var(--color-accent-600)]"
                >
                    فروشگاه
                </a>

                @if($product->category)

                    <svg
                        class="h-3.5 w-3.5 text-[var(--color-neutral-400)]"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <path d="m9 18 6-6-6-6"/>
                    </svg>

                    <a
                        href="{{ route('categories.show', $product->category) }}"
                        class="transition hover:text-[var(--color-accent-600)]"
                    >
                        {{ $product->category->name }}
                    </a>

                @endif

                <svg
                    class="h-3.5 w-3.5 text-[var(--color-neutral-400)]"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    aria-hidden="true"
                >
                    <path d="m9 18 6-6-6-6"/>
                </svg>

                <span
                    class="max-w-[220px] truncate font-bold text-[var(--color-text-secondary)]"
                >
                    {{ $product->name }}
                </span>

            </nav>


            {{-- =====================================================
                PRODUCT GRID
            ====================================================== --}}

            <div class="mt-8 grid gap-8 lg:grid-cols-[1.02fr_.98fr] lg:gap-12">


                {{-- =================================================
                    GALLERY
                ================================================== --}}

                <div
                    x-data="{
                        active: @js(
                            $product->primaryImage?->id
                            ?? $product->images->first()?->id
                        ),

                        images: @js(
                            $product->images
                                ->map(fn($image) => [
                                    'id' => $image->id,
                                    'url' => asset('storage/' . $image->image),
                                    'alt' => $image->alt ?: $product->name,
                                ])
                                ->values()
                        )
                    }"
                    class="lg:sticky lg:top-28 lg:self-start"
                >

                    {{-- Main image --}}
                    <div
                        class="overflow-hidden rounded-[2rem] border border-[var(--color-border)] bg-white shadow-[var(--shadow-xs)]"
                    >

                        <div class="relative aspect-square overflow-hidden bg-[#eef0f3]">

                            {{-- Decorative surface --}}
                            <div
                                class="pointer-events-none absolute -right-16 -top-16 z-[1] h-52 w-52 rounded-full bg-white/60 blur-3xl"
                            ></div>

                            <div
                                class="pointer-events-none absolute -bottom-20 -left-16 z-[1] h-60 w-60 rounded-full bg-[#d8dde5]/50 blur-3xl"
                            ></div>


                            {{-- Images --}}
                            <template
                                x-for="image in images"
                                :key="image.id"
                            >

                                <div
                                    x-show="active === image.id"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 scale-[1.015]"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    class="absolute inset-0"
                                >

                                    <img
                                        :src="image.url"
                                        :alt="image.alt"
                                        class="h-full w-full object-cover"
                                    >

                                </div>

                            </template>


                            {{-- Empty gallery --}}
                            <div
                                x-show="images.length === 0"
                                class="absolute inset-0 flex items-center justify-center text-[var(--color-neutral-400)]"
                            >
                                <svg
                                    class="h-20 w-20"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1"
                                    aria-hidden="true"
                                >
                                    <rect x="3" y="4" width="18" height="16" rx="2"/>
                                    <circle cx="8.5" cy="9" r="1.4"/>
                                    <path d="m21 15-5-5-4.5 4.5-2.5-2.5L3 18"/>
                                </svg>
                            </div>


                            {{-- Discount badge --}}
                            @if($product->discount > 0)

                                <div
                                    class="absolute right-4 top-4 z-20 rounded-full bg-[var(--color-accent-600)] px-3.5 py-2 text-[11px] font-black text-white shadow-lg shadow-[var(--color-accent-600)]/20"
                                >
                                    {{ $product->discount }}٪ تخفیف
                                </div>

                            @elseif($product->is_featured)

                                <div
                                    class="absolute right-4 top-4 z-20 inline-flex items-center gap-1.5 rounded-full bg-[var(--color-brand-900)] px-3.5 py-2 text-[11px] font-black text-white shadow-lg"
                                >
                                    <svg
                                        class="h-3.5 w-3.5"
                                        viewBox="0 0 24 24"
                                        fill="currentColor"
                                        aria-hidden="true"
                                    >
                                        <path d="m12 2 2.7 6.3L21 11l-6.3 2.7L12 20l-2.7-6.3L3 11l6.3-2.7L12 2Z"/>
                                    </svg>

                                    انتخاب ویژه
                                </div>

                            @endif


                            {{-- Stock --}}
                            @if(!$product->is_active || $product->stock <= 0)

                                <div
                                    class="absolute left-4 top-4 z-20 rounded-full bg-[var(--color-brand-950)]/90 px-3.5 py-2 text-[11px] font-black text-white shadow-lg backdrop-blur"
                                >
                                    ناموجود
                                </div>

                            @elseif($product->stock <= 5)

                                <div
                                    class="absolute left-4 top-4 z-20 rounded-full border border-white/80 bg-white/90 px-3.5 py-2 text-[11px] font-black text-[var(--color-text-secondary)] shadow-sm backdrop-blur"
                                >
                                    فقط {{ $product->stock }} عدد
                                </div>

                            @endif

                        </div>

                    </div>


                    {{-- =================================================
                        Thumbnails
                    ================================================== --}}

                    <div
                        x-show="images.length > 1"
                        class="mt-4 grid grid-cols-5 gap-3"
                    >

                        <template
                            x-for="image in images"
                            :key="`thumb-${image.id}`"
                        >

                            <button
                                type="button"
                                @click="active = image.id"
                                :aria-label="`نمایش تصویر ${image.id}`"
                                :class="
                                    active === image.id
                                    ? 'border-[var(--color-brand-900)] ring-2 ring-[var(--color-brand-900)]/10'
                                    : 'border-[var(--color-border)] hover:border-[var(--color-border-strong)]'
                                "
                                class="overflow-hidden rounded-2xl border-2 bg-white transition duration-200"
                            >

                                <img
                                    :src="image.url"
                                    :alt="image.alt"
                                    class="aspect-square w-full object-cover transition duration-300 hover:scale-105"
                                    loading="lazy"
                                >

                            </button>

                        </template>

                    </div>

                </div>


                {{-- =================================================
                    PRODUCT DETAILS
                ================================================== --}}

                <div>

                    {{-- Brand / category --}}
                    <div class="flex flex-wrap items-center gap-2">

                        @if($product->brand)

                            <span
                                class="text-xs font-black text-[var(--color-brand-700)]"
                            >
                                {{ $product->brand }}
                            </span>

                        @endif

                        @if($product->category)

                            <span
                                class="text-xs text-[var(--color-neutral-400)]"
                            >
                                /
                            </span>

                            <a
                                href="{{ route('categories.show', $product->category) }}"
                                class="text-xs font-bold text-[var(--color-text-muted)] transition hover:text-[var(--color-accent-600)]"
                            >
                                {{ $product->category->name }}
                            </a>

                        @endif

                    </div>


                    {{-- Product title --}}
                    <h1
                        class="mt-4 max-w-3xl text-3xl font-black leading-[1.35] tracking-tight text-[var(--color-text-primary)] sm:text-4xl xl:text-5xl"
                    >
                        {{ $product->name }}
                    </h1>


                    {{-- Reviews --}}
                    @if($product->review_count > 0)

                        <div class="mt-5 flex flex-wrap items-center gap-3">

                            <div class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1.5 text-xs font-black text-amber-700">

                                <svg
                                    class="h-3.5 w-3.5 text-amber-500"
                                    viewBox="0 0 24 24"
                                    fill="currentColor"
                                    aria-hidden="true"
                                >
                                    <path d="m12 2.8 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2-5.6-3-5.6 3 1.1-6.2L3 9.4l6.2-.9L12 2.8Z"/>
                                </svg>

                                {{ number_format($product->rating, 1) }}

                            </div>

                            <span class="text-xs text-[var(--color-text-muted)]">
                                بر اساس {{ number_format($product->review_count) }} نظر
                            </span>

                        </div>

                    @endif


                    {{-- Short description --}}
                    @if($product->short_description)

                        <p class="mt-7 max-w-2xl text-sm leading-8 text-[var(--color-text-secondary)] sm:text-base">
                            {{ $product->short_description }}
                        </p>

                    @endif


                    {{-- =================================================
                        Price Card
                    ================================================== --}}

                    <div
                        class="mt-8 rounded-[1.75rem] border border-[var(--color-border)] bg-white p-5 shadow-[var(--shadow-xs)] sm:p-6"
                    >

                        <div class="flex flex-wrap items-end justify-between gap-5">

                            <div>

                                <p class="text-xs font-bold text-[var(--color-text-muted)]">
                                    قیمت نهایی
                                </p>

                                <div class="mt-2 flex items-baseline gap-2">

                                    <span
                                        class="text-3xl font-black tracking-tight text-[var(--color-brand-950)] sm:text-4xl"
                                    >
                                        {{ number_format($product->price) }}
                                    </span>

                                    <span
                                        class="text-xs font-bold text-[var(--color-text-muted)]"
                                    >
                                        تومان
                                    </span>

                                </div>

                                @if($product->old_price && $product->old_price > $product->price)

                                    <div class="mt-2 flex items-center gap-2">

                                        <span
                                            class="text-sm font-medium text-[var(--color-text-soft)] line-through"
                                        >
                                            {{ number_format($product->old_price) }}
                                        </span>

                                        @if($product->discount > 0)

                                            <span
                                                class="rounded-md bg-[var(--color-accent-50)] px-2 py-1 text-[10px] font-black text-[var(--color-accent-700)]"
                                            >
                                                {{ $product->discount }}٪
                                            </span>

                                        @endif

                                    </div>

                                @endif

                            </div>


                            {{-- Availability --}}
                            <div>

                                @if($product->is_active && $product->stock > 0)

                                    <div
                                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-50 px-3 py-2 text-xs font-black text-emerald-700"
                                    >
                                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                        موجود در انبار
                                    </div>

                                @else

                                    <div
                                        class="inline-flex items-center gap-2 rounded-xl bg-[var(--color-neutral-100)] px-3 py-2 text-xs font-black text-[var(--color-text-muted)]"
                                    >
                                        فعلاً ناموجود
                                    </div>

                                @endif

                            </div>

                        </div>


                        {{-- Savings --}}
                        @if($product->old_price && $product->old_price > $product->price)

                            <div class="mt-5 rounded-xl bg-[var(--color-accent-50)] px-4 py-3 text-xs font-bold text-[var(--color-accent-700)]">

                                با این خرید حدود
                                <span class="font-black">
                                    {{ number_format($product->old_price - $product->price) }}
                                </span>
                                تومان صرفه‌جویی می‌کنید.

                            </div>

                        @endif

                    </div>


                    {{-- =================================================
                        ADD TO CART
                    ================================================== --}}

                    @if($product->is_active && $product->stock > 0)

                        <form
                            action="{{ route('customer.cart.add') }}"
                            method="POST"
                            class="mt-5"
                        >
                            @csrf

                            <input
                                type="hidden"
                                name="product_id"
                                value="{{ $product->id }}"
                            >

                            <div class="flex flex-col gap-3 sm:flex-row">

                                {{-- Quantity --}}
                                <div
                                    class="flex h-14 shrink-0 items-center rounded-2xl border border-[var(--color-border)] bg-white px-3"
                                >

                                    <label
                                        for="quantity"
                                        class="ml-3 text-xs font-bold text-[var(--color-text-muted)]"
                                    >
                                        تعداد
                                    </label>

                                    <input
                                        id="quantity"
                                        type="number"
                                        name="quantity"
                                        min="1"
                                        max="{{ min($product->stock, 99) }}"
                                        value="1"
                                        inputmode="numeric"
                                        class="w-16 bg-transparent text-center text-sm font-black text-[var(--color-text-primary)] outline-none"
                                    >

                                </div>


                                {{-- Submit --}}
                                <button
                                    type="submit"
                                    class="group flex h-14 flex-1 items-center justify-center gap-3 rounded-2xl bg-[var(--color-accent-600)] px-6 text-sm font-black text-white shadow-lg shadow-[var(--color-accent-600)]/15 transition duration-300 hover:-translate-y-0.5 hover:bg-[var(--color-accent-700)] focus:outline-none focus:ring-4 focus:ring-[var(--color-accent-600)]/15"
                                >

                                    <svg
                                        class="h-5 w-5"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        aria-hidden="true"
                                    >
                                        <path d="M3 4h2l2.4 11.2a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 2-1.6L21 7H6"/>
                                        <circle cx="10" cy="20" r="1"/>
                                        <circle cx="18" cy="20" r="1"/>
                                    </svg>

                                    افزودن به سبد خرید

                                    <svg
                                        class="h-4 w-4 transition duration-300 group-hover:-translate-x-1"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        aria-hidden="true"
                                    >
                                        <path d="m9 18 6-6-6-6"/>
                                    </svg>

                                </button>

                            </div>

                        </form>

                    @else

                        <div
                            class="mt-5 flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-bold text-red-700"
                        >

                            <svg
                                class="h-5 w-5 shrink-0"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                aria-hidden="true"
                            >
                                <circle cx="12" cy="12" r="9"/>
                                <path d="M12 8v5"/>
                                <path d="M12 16h.01"/>
                            </svg>

                            این محصول در حال حاضر قابل سفارش نیست.

                        </div>

                    @endif


                    {{-- =================================================
                        Trust Features
                    ================================================== --}}

                    <div class="mt-6 grid gap-3 sm:grid-cols-3">

                        <div
                            class="rounded-2xl border border-[var(--color-border)] bg-white p-4"
                        >
                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-xl bg-[var(--color-brand-50)] text-[var(--color-brand-900)]"
                            >
                                <svg
                                    class="h-4.5 w-4.5"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                    aria-hidden="true"
                                >
                                    <path d="M3 7h11v10H3z"/>
                                    <path d="M14 10h3l4 4v3h-7z"/>
                                    <circle cx="7" cy="19" r="1.5"/>
                                    <circle cx="18" cy="19" r="1.5"/>
                                </svg>
                            </div>

                            <p class="mt-3 text-xs font-black text-[var(--color-text-primary)]">
                                ارسال مطمئن
                            </p>

                            <p class="mt-1 text-[11px] leading-5 text-[var(--color-text-muted)]">
                                سفارش شما در مسیر ارسال قابل پیگیری است.
                            </p>
                        </div>


                        <div
                            class="rounded-2xl border border-[var(--color-border)] bg-white p-4"
                        >
                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-xl bg-[var(--color-brand-50)] text-[var(--color-brand-900)]"
                            >
                                <svg
                                    class="h-4.5 w-4.5"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                    aria-hidden="true"
                                >
                                    <path d="M12 3 5 6v5c0 5 3 8.5 7 10 4-1.5 7-5 7-10V6l-7-3Z"/>
                                    <path d="m9 12 2 2 4-4"/>
                                </svg>
                            </div>

                            <p class="mt-3 text-xs font-black text-[var(--color-text-primary)]">
                                پرداخت امن
                            </p>

                            <p class="mt-1 text-[11px] leading-5 text-[var(--color-text-muted)]">
                                پرداخت آنلاین در مسیر امن انجام می‌شود.
                            </p>
                        </div>


                        <a
                            href="{{ route('contact.index') }}"
                            class="group rounded-2xl border border-[var(--color-border)] bg-white p-4 transition hover:-translate-y-0.5 hover:border-[var(--color-brand-300)] hover:shadow-sm"
                        >
                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-xl bg-[var(--color-accent-50)] text-[var(--color-accent-600)]"
                            >
                                <svg
                                    class="h-4.5 w-4.5"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                    aria-hidden="true"
                                >
                                    <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v8Z"/>
                                </svg>
                            </div>

                            <p class="mt-3 text-xs font-black text-[var(--color-text-primary)]">
                                نیاز به مشاوره؟
                            </p>

                            <p class="mt-1 text-[11px] leading-5 text-[var(--color-text-muted)]">
                                قبل از خرید با ما در ارتباط باشید.
                            </p>
                        </a>

                    </div>


                    {{-- SKU --}}
                    @if($product->sku)

                        <div class="mt-7 flex items-center justify-between border-t border-[var(--color-border)] pt-5">

                            <span class="text-xs text-[var(--color-text-muted)]">
                                کد محصول
                            </span>

                            <span
                                class="font-mono text-xs font-bold text-[var(--color-text-secondary)]"
                                dir="ltr"
                            >
                                {{ $product->sku }}
                            </span>

                        </div>

                    @endif

                </div>

            </div>


            {{-- =====================================================
                DESCRIPTION
            ====================================================== --}}

            @if($product->description)

                <section class="mt-16 border-t border-[var(--color-border)] pt-14">

                    <div class="grid gap-8 lg:grid-cols-[220px_1fr] lg:gap-12">

                        <div>

                            <div class="text-[11px] font-black uppercase tracking-[0.24em] text-[var(--color-accent-600)]">
                                Details
                            </div>

                            <h2 class="mt-3 text-2xl font-black text-[var(--color-text-primary)]">
                                درباره محصول
                            </h2>

                        </div>

                        <div
                            class="prose-farzin max-w-4xl rounded-[1.75rem] border border-[var(--color-border)] bg-white p-6 sm:p-8"
                        >
                            {!! nl2br(e($product->description)) !!}
                        </div>

                    </div>

                </section>

            @endif


            {{-- =====================================================
                REVIEWS
            ====================================================== --}}

            <section class="mt-16 border-t border-[var(--color-border)] pt-14">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

                    <div>

                        <div class="text-[11px] font-black uppercase tracking-[0.24em] text-[var(--color-accent-600)]">
                            Reviews
                        </div>

                        <h2 class="mt-3 text-2xl font-black text-[var(--color-text-primary)]">
                            تجربه خریداران
                        </h2>

                    </div>


                    @if($product->review_count > 0)

                        <div
                            class="inline-flex items-center gap-2 rounded-2xl bg-amber-50 px-4 py-3"
                        >

                            <svg
                                class="h-5 w-5 text-amber-500"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                                aria-hidden="true"
                            >
                                <path d="m12 2.8 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2-5.6-3-5.6 3 1.1-6.2L3 9.4l6.2-.9L12 2.8Z"/>
                            </svg>

                            <span class="text-xl font-black text-amber-700">
                                {{ number_format($product->rating, 1) }}
                            </span>

                            <span class="text-xs font-medium text-amber-600">
                                / ۵
                            </span>

                        </div>

                    @endif

                </div>


                <div class="mt-8 grid gap-4 md:grid-cols-2">

                    @forelse($product->reviews as $review)

                        <article
                            class="rounded-[1.75rem] border border-[var(--color-border)] bg-white p-6 shadow-[var(--shadow-xs)]"
                        >

                            <div class="flex items-start justify-between gap-4">

                                <div class="min-w-0">

                                    <div class="flex items-center gap-2">

                                        <div
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[var(--color-brand-50)] text-xs font-black text-[var(--color-brand-900)]"
                                        >
                                            {{ mb_substr($review->user?->name ?? 'ک', 0, 1) }}
                                        </div>

                                        <div class="min-w-0">

                                            <div class="truncate text-sm font-black text-[var(--color-text-primary)]">
                                                {{ $review->user?->name ?? 'کاربر' }}
                                            </div>

                                            <div class="mt-0.5 text-[10px] text-[var(--color-text-muted)]">
                                                {{ $review->created_at?->format('Y/m/d') }}
                                            </div>

                                        </div>

                                    </div>

                                </div>


                                <div class="flex shrink-0 items-center gap-1 rounded-lg bg-amber-50 px-2.5 py-1.5 text-[11px] font-black text-amber-700">

                                    <svg
                                        class="h-3 w-3 text-amber-500"
                                        viewBox="0 0 24 24"
                                        fill="currentColor"
                                        aria-hidden="true"
                                    >
                                        <path d="m12 2.8 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2-5.6-3-5.6 3 1.1-6.2L3 9.4l6.2-.9L12 2.8Z"/>
                                    </svg>

                                    {{ $review->rating }}

                                </div>

                            </div>


                            @if($review->title)

                                <h3 class="mt-5 text-sm font-black text-[var(--color-text-primary)]">
                                    {{ $review->title }}
                                </h3>

                            @endif


                            <p class="mt-3 text-sm leading-8 text-[var(--color-text-secondary)]">
                                {{ $review->body }}
                            </p>

                        </article>

                    @empty

                        <div
                            class="md:col-span-2 rounded-[1.75rem] border border-dashed border-[var(--color-border-strong)] bg-white px-6 py-14 text-center"
                        >

                            <div
                                class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[var(--color-brand-50)] text-[var(--color-brand-900)]"
                            >
                                <svg
                                    class="h-6 w-6"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                    aria-hidden="true"
                                >
                                    <path d="M4 5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v13a2 2 0 0 1-2 2H8l-4 3V5Z"/>
                                    <path d="M8 9h8M8 13h5"/>
                                </svg>
                            </div>

                            <p class="mt-4 text-sm font-black text-[var(--color-text-primary)]">
                                هنوز نظری برای این محصول ثبت نشده است.
                            </p>

                            <p class="mt-1 text-xs leading-6 text-[var(--color-text-muted)]">
                                تجربه اولین خریداران می‌تواند به انتخاب بهتر دیگران کمک کند.
                            </p>

                        </div>

                    @endforelse

                </div>

            </section>


            {{-- =====================================================
                RELATED PRODUCTS
            ====================================================== --}}

            @if($relatedProducts->count())

                <section class="mt-16 border-t border-[var(--color-border)] pt-14">

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

                        <div>

                            <div class="text-[11px] font-black uppercase tracking-[0.24em] text-[var(--color-accent-600)]">
                                You may also like
                            </div>

                            <h2 class="mt-3 text-2xl font-black text-[var(--color-text-primary)] sm:text-3xl">
                                شاید این محصولات هم مناسب شما باشند
                            </h2>

                        </div>


                        @if($product->category)

                            <a
                                href="{{ route('categories.show', $product->category) }}"
                                class="inline-flex items-center gap-2 text-sm font-black text-[var(--color-brand-900)] transition hover:text-[var(--color-accent-600)]"
                            >
                                مشاهده دسته‌بندی

                                <svg
                                    class="h-4 w-4"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path d="m9 18 6-6-6-6"/>
                                </svg>
                            </a>

                        @endif

                    </div>


                    <div class="mt-8 grid grid-cols-2 gap-x-4 gap-y-8 md:grid-cols-3 lg:grid-cols-4">

                        @foreach($relatedProducts as $relatedProduct)

                            @include('partials.product_card', [
                                'product' => $relatedProduct
                            ])

                        @endforeach

                    </div>

                </section>

            @endif

        </section>


        {{-- =========================================================
            BOTTOM CTA
        ========================================================== --}}

        <section class="border-t border-[var(--color-border)] bg-white">

            <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">

                <div
                    class="relative overflow-hidden rounded-[2rem] bg-[var(--color-brand-950)] p-7 text-white sm:p-10"
                >

                    <div
                        class="pointer-events-none absolute -left-16 -top-16 h-48 w-48 rounded-full bg-[var(--color-accent-600)]/15 blur-3xl"
                    ></div>

                    <div
                        class="pointer-events-none absolute -bottom-20 -right-10 h-52 w-52 rounded-full bg-white/[0.04] blur-3xl"
                    ></div>

                    <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">

                        <div class="max-w-2xl">

                            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-white/40">
                                FARZIN
                            </span>

                            <h2 class="mt-2 text-2xl font-black sm:text-3xl">
                                درباره انتخاب این محصول سوالی دارید؟
                            </h2>

                            <p class="mt-3 text-sm leading-7 text-white/55">
                                تیم فرزین برای مشاوره خرید و پاسخ به سوالات شما آماده است.
                            </p>

                        </div>


                        <a
                            href="{{ route('contact.index') }}"
                            class="inline-flex shrink-0 items-center justify-center gap-2 rounded-2xl bg-[var(--color-accent-600)] px-6 py-3.5 text-sm font-black text-white transition hover:bg-[var(--color-accent-700)]"
                        >
                            تماس با ما

                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path d="m9 18 6-6-6-6"/>
                            </svg>
                        </a>

                    </div>

                </div>

            </div>

        </section>

    </div>

@endsection
