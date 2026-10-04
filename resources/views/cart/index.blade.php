@extends('layouts.app')

@section('title', 'سبد خرید | فرزین')

@section('meta_description', 'سبد خرید شما در فرزین')

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
                        Your Cart
                    </div>

                    <h1 class="mt-3 text-3xl font-black tracking-tight text-[var(--color-text-primary)] sm:text-4xl">
                        سبد خرید شما
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-7 text-[var(--color-text-secondary)]">
                        محصولات انتخاب‌شده‌ات را بررسی کن و برای ثبت سفارش ادامه بده.
                    </p>

                </div>


                @if($itemCount > 0)

                    <div class="inline-flex w-fit items-center gap-2 rounded-full bg-[var(--color-brand-50)] px-4 py-2 text-xs font-black text-[var(--color-brand-900)]">

                        <svg
                            class="h-4 w-4"
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

                        {{ number_format($itemCount) }}
                        آیتم

                    </div>

                @endif

            </div>


            {{-- =========================================================
                EMPTY CART
            ========================================================== --}}

            @if($items->isEmpty())

                <div class="mt-10 overflow-hidden rounded-[2rem] border border-[var(--color-border)] bg-white shadow-[var(--shadow-xs)]">

                    <div class="relative flex flex-col items-center overflow-hidden px-6 py-20 text-center sm:py-28">

                        {{-- Decorative --}}
                        <div class="pointer-events-none absolute -right-20 -top-20 h-52 w-52 rounded-full bg-[var(--color-accent-600)]/5 blur-3xl"></div>

                        <div class="pointer-events-none absolute -bottom-24 -left-20 h-56 w-56 rounded-full bg-[var(--color-brand-900)]/5 blur-3xl"></div>


                        <div class="relative flex h-20 w-20 items-center justify-center rounded-[1.75rem] bg-[var(--color-brand-50)] text-[var(--color-brand-900)]">

                            <svg
                                class="h-9 w-9"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                aria-hidden="true"
                            >
                                <path d="M3 4h2l2.4 11.2a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 2-1.6L21 7H6"/>
                                <circle cx="10" cy="20" r="1"/>
                                <circle cx="18" cy="20" r="1"/>
                            </svg>

                        </div>


                        <h2 class="relative mt-6 text-2xl font-black text-[var(--color-text-primary)]">
                            سبد خریدت خالیه
                        </h2>

                        <p class="relative mt-3 max-w-md text-sm leading-7 text-[var(--color-text-secondary)]">
                            هنوز چیزی به سبد خرید اضافه نکردی.
                            چند محصول خوب منتظرته تا انتخابشان کنی.
                        </p>


                        <a
                            href="{{ route('shop.index') }}"
                            class="relative mt-7 inline-flex items-center gap-3 rounded-2xl bg-[var(--color-accent-600)] px-6 py-4 text-sm font-black text-white shadow-lg shadow-[var(--color-accent-600)]/15 transition duration-300 hover:-translate-y-0.5 hover:bg-[var(--color-accent-700)]"
                        >
                            رفتن به فروشگاه

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
                    CART CONTENT
                ====================================================== --}}

                <div class="mt-10 grid gap-8 xl:grid-cols-[minmax(0,1fr)_380px]">


                    {{-- =================================================
                        ITEMS
                    ================================================== --}}

                    <div class="space-y-4">

                        @foreach($items as $item)

                            @php
                                $product = $item->product;
                                $image = $product?->primaryImage?->image;

                                $lineTotal =
                                    $item->unit_price * $item->quantity;

                                $maxQuantity =
                                    min($product?->stock ?? 1, 99);

                                $isAvailable =
                                    $product &&
                                    $product->is_active &&
                                    $product->stock > 0;
                            @endphp


                            @if($product)

                                <article
                                    class="group rounded-[2rem] border border-[var(--color-border)] bg-white p-4 shadow-[var(--shadow-xs)] transition duration-300 hover:-translate-y-0.5 hover:border-[var(--color-border-strong)] hover:shadow-[var(--shadow-sm)] sm:p-5"
                                >

                                    <div class="flex flex-col gap-5 sm:flex-row">


                                        {{-- =================================================
                                            PRODUCT IMAGE
                                        ================================================== --}}

                                        <a
                                            href="{{ route('products.show', $product) }}"
                                            class="block shrink-0"
                                        >

                                            <div class="relative h-28 w-28 overflow-hidden rounded-2xl bg-[#eef0f3] sm:h-32 sm:w-32">

                                                {{-- subtle surface --}}
                                                <div class="pointer-events-none absolute -right-5 -top-5 z-[1] h-20 w-20 rounded-full bg-white/60 blur-2xl"></div>

                                                @if($image)

                                                    <img
                                                        src="{{ asset('storage/' . $image) }}"
                                                        alt="{{ $product->name }}"
                                                        class="relative h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                                        loading="lazy"
                                                        decoding="async"
                                                    >

                                                @else

                                                    <div class="flex h-full w-full items-center justify-center text-[var(--color-neutral-400)]">

                                                        <svg
                                                            class="h-10 w-10"
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


                                                @if(!$isAvailable)

                                                    <div class="absolute inset-0 z-10 flex items-center justify-center bg-[var(--color-brand-950)]/10">

                                                    <span class="rounded-full bg-white/95 px-3 py-1.5 text-[9px] font-black text-[var(--color-brand-950)] shadow-sm backdrop-blur">
                                                        ناموجود
                                                    </span>

                                                    </div>

                                                @endif

                                            </div>

                                        </a>


                                        {{-- =================================================
                                            PRODUCT INFO
                                        ================================================== --}}

                                        <div class="flex min-w-0 flex-1 flex-col">

                                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">


                                                {{-- Product Identity --}}
                                                <div class="min-w-0">

                                                    @if($product->category)

                                                        <a
                                                            href="{{ route('categories.show', $product->category) }}"
                                                            class="inline-block truncate text-[11px] font-black text-[var(--color-accent-600)] transition hover:text-[var(--color-accent-700)]"
                                                        >
                                                            {{ $product->category->name }}
                                                        </a>

                                                    @endif


                                                    <a
                                                        href="{{ route('products.show', $product) }}"
                                                        class="mt-1 block"
                                                    >
                                                        <h2
                                                            class="line-clamp-2 text-base font-black leading-7 text-[var(--color-text-primary)] transition hover:text-[var(--color-brand-900)]"
                                                        >
                                                            {{ $item->product_name ?? $product->name }}
                                                        </h2>
                                                    </a>


                                                    @if($item->product_sku)

                                                        <div class="mt-1.5 text-xs text-[var(--color-text-muted)]">

                                                            کد کالا:

                                                            <span
                                                                class="font-mono font-bold text-[var(--color-text-secondary)]"
                                                                dir="ltr"
                                                            >
                                                            {{ $item->product_sku }}
                                                        </span>

                                                        </div>

                                                    @endif

                                                </div>


                                                {{-- Remove --}}
                                                <form
                                                    action="{{ route('customer.cart.remove', $product) }}"
                                                    method="POST"
                                                    class="shrink-0"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="inline-flex items-center gap-2 rounded-xl px-3 py-2 text-xs font-bold text-[var(--color-text-muted)] transition hover:bg-[var(--color-danger-50)] hover:text-[var(--color-danger-700)]"
                                                        title="حذف از سبد"
                                                        aria-label="حذف {{ $product->name }} از سبد خرید"
                                                    >

                                                        <svg
                                                            class="h-4 w-4"
                                                            viewBox="0 0 24 24"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            stroke-width="1.7"
                                                            aria-hidden="true"
                                                        >
                                                            <path d="M3 6h18"/>
                                                            <path d="M8 6V4h8v2"/>
                                                            <path d="M19 6l-1 15H6L5 6"/>
                                                            <path d="M10 11v6"/>
                                                            <path d="M14 11v6"/>
                                                        </svg>

                                                        حذف

                                                    </button>

                                                </form>

                                            </div>


                                            {{-- =================================================
                                                BOTTOM ROW
                                            ================================================== --}}

                                            <div class="mt-6 flex flex-col gap-5 sm:mt-auto sm:flex-row sm:items-end sm:justify-between">


                                                {{-- Quantity --}}
                                                <form
                                                    action="{{ route('customer.cart.update', $product) }}"
                                                    method="POST"
                                                    class="flex items-center gap-3"
                                                >
                                                    @csrf
                                                    @method('PATCH')

                                                    <input
                                                        type="hidden"
                                                        name="product_id"
                                                        value="{{ $product->id }}"
                                                    >

                                                    <span
                                                        class="text-xs font-bold text-[var(--color-text-muted)]"
                                                    >
                                                    تعداد
                                                </span>


                                                    <div
                                                        class="flex h-11 items-center overflow-hidden rounded-xl border border-[var(--color-border)] bg-white"
                                                    >

                                                        <button
                                                            type="button"
                                                            onclick="changeCartQuantity(this, -1)"
                                                            class="flex h-full w-10 items-center justify-center text-[var(--color-text-muted)] transition hover:bg-[var(--color-neutral-50)] hover:text-[var(--color-brand-900)]"
                                                            aria-label="کاهش تعداد"
                                                        >
                                                            −
                                                        </button>


                                                        <input
                                                            type="number"
                                                            name="quantity"
                                                            value="{{ $item->quantity }}"
                                                            min="1"
                                                            max="{{ max(1, $maxQuantity) }}"
                                                            class="h-full w-12 border-x border-[var(--color-border)] bg-transparent text-center text-sm font-black text-[var(--color-text-primary)] outline-none"
                                                            onchange="this.form.submit()"
                                                            aria-label="تعداد {{ $product->name }}"
                                                        >


                                                        <button
                                                            type="button"
                                                            onclick="changeCartQuantity(this, 1)"
                                                            class="flex h-full w-10 items-center justify-center text-[var(--color-text-muted)] transition hover:bg-[var(--color-neutral-50)] hover:text-[var(--color-brand-900)]"
                                                            aria-label="افزایش تعداد"
                                                        >
                                                            +
                                                        </button>

                                                    </div>

                                                </form>


                                                {{-- Prices --}}
                                                <div class="text-right sm:text-left">

                                                    <div
                                                        class="text-xs text-[var(--color-text-muted)]"
                                                    >
                                                        {{ number_format($item->unit_price) }}
                                                        تومان
                                                        ×
                                                        {{ $item->quantity }}
                                                    </div>

                                                    <div
                                                        class="mt-1 text-lg font-black text-[var(--color-brand-950)]"
                                                    >
                                                        {{ number_format($lineTotal) }}

                                                        <span class="text-[10px] font-bold text-[var(--color-text-muted)]">
                                                        تومان
                                                    </span>
                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </article>

                            @endif

                        @endforeach


                        {{-- =================================================
                            CLEAR CART
                        ================================================== --}}

                        <div class="flex justify-end pt-1">

                            <form
                                action="{{ route('customer.cart.clear') }}"
                                method="POST"
                                onsubmit="return confirm('از خالی کردن سبد خرید مطمئنی؟');"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="inline-flex items-center gap-2 rounded-xl px-3 py-2 text-xs font-bold text-[var(--color-text-muted)] transition hover:bg-[var(--color-danger-50)] hover:text-[var(--color-danger-700)]"
                                >

                                    <svg
                                        class="h-4 w-4"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                        aria-hidden="true"
                                    >
                                        <path d="M3 6h18"/>
                                        <path d="M8 6V4h8v2"/>
                                        <path d="M19 6l-1 15H6L5 6"/>
                                        <path d="M10 11v6"/>
                                        <path d="M14 11v6"/>
                                    </svg>

                                    خالی کردن سبد خرید

                                </button>

                            </form>

                        </div>

                    </div>


                    {{-- =================================================
                        SUMMARY
                    ================================================== --}}

                    <aside class="xl:sticky xl:top-28 xl:self-start">

                        <div
                            class="overflow-hidden rounded-[2rem] border border-[var(--color-border)] bg-white shadow-[var(--shadow-sm)]"
                        >

                            {{-- Summary Header --}}
                            <div class="border-b border-[var(--color-border)] px-6 py-6">

                                <div class="text-[11px] font-black uppercase tracking-[0.24em] text-[var(--color-accent-600)]">
                                    Order Summary
                                </div>

                                <h2 class="mt-2 text-xl font-black text-[var(--color-text-primary)]">
                                    خلاصه سفارش
                                </h2>

                            </div>


                            <div class="space-y-5 px-6 py-6">

                                {{-- Items --}}
                                <div class="flex items-center justify-between gap-4 text-sm">

                                <span class="text-[var(--color-text-secondary)]">
                                    تعداد اقلام
                                </span>

                                    <span class="font-black text-[var(--color-text-primary)]">
                                    {{ number_format($itemCount) }}
                                </span>

                                </div>


                                {{-- Subtotal --}}
                                <div class="flex items-center justify-between gap-4 text-sm">

                                <span class="text-[var(--color-text-secondary)]">
                                    مبلغ کالاها
                                </span>

                                    <span class="font-black text-[var(--color-text-primary)]">
                                    {{ number_format($subtotal) }}
                                    <span class="text-[10px] font-bold text-[var(--color-text-muted)]">
                                        تومان
                                    </span>
                                </span>

                                </div>


                                {{-- Shipping --}}
                                <div class="flex items-center justify-between gap-4 text-sm">

                                <span class="text-[var(--color-text-secondary)]">
                                    هزینه ارسال
                                </span>

                                    <span class="rounded-full bg-[var(--color-neutral-100)] px-2.5 py-1 text-[10px] font-black text-[var(--color-text-muted)]">
                                    در مرحله بعد
                                </span>

                                </div>


                                {{-- Total --}}
                                <div class="border-t border-dashed border-[var(--color-border)] pt-5">

                                    <div class="flex items-end justify-between gap-4">

                                        <div>

                                            <div class="text-xs text-[var(--color-text-muted)]">
                                                مبلغ فعلی سبد
                                            </div>

                                            <div class="mt-1 flex items-baseline gap-1.5">

                                            <span
                                                class="text-2xl font-black tracking-tight text-[var(--color-brand-950)]"
                                            >
                                                {{ number_format($subtotal) }}
                                            </span>

                                                <span class="text-xs font-bold text-[var(--color-text-muted)]">
                                                تومان
                                            </span>

                                            </div>

                                        </div>


                                        <div class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-[10px] font-black text-emerald-700">

                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>

                                            امن و مطمئن

                                        </div>

                                    </div>

                                </div>


                                {{-- Checkout --}}
                                <a
                                    href="{{ route('customer.checkout.index') }}"
                                    class="group flex w-full items-center justify-center gap-3 rounded-2xl bg-[var(--color-accent-600)] px-5 py-4 text-sm font-black text-white shadow-lg shadow-[var(--color-accent-600)]/15 transition duration-300 hover:-translate-y-0.5 hover:bg-[var(--color-accent-700)]"
                                >
                                    ادامه و ثبت سفارش

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

                                </a>


                                {{-- Continue shopping --}}
                                <a
                                    href="{{ route('shop.index') }}"
                                    class="flex w-full items-center justify-center gap-2 rounded-2xl border border-[var(--color-border)] px-5 py-4 text-sm font-black text-[var(--color-text-secondary)] transition duration-200 hover:border-[var(--color-brand-300)] hover:bg-[var(--color-brand-50)] hover:text-[var(--color-brand-900)]"
                                >
                                    ادامه خرید

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


                            {{-- =================================================
                                TRUST
                            ================================================== --}}

                            <div class="border-t border-[var(--color-border)] bg-[var(--color-neutral-50)] px-6 py-5">

                                <div class="space-y-4">

                                    <div class="flex items-start gap-3">

                                        <div
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-[var(--color-brand-900)] shadow-sm"
                                        >
                                            <svg
                                                class="h-4 w-4"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                aria-hidden="true"
                                            >
                                                <path d="M12 3 5 6v5c0 5 3 8.5 7 10 4-1.5 7-5 7-10V6l-7-3Z"/>
                                                <path d="m9 12 2 2 4-4"/>
                                            </svg>
                                        </div>

                                        <div>

                                            <div class="text-xs font-black text-[var(--color-text-primary)]">
                                                پرداخت امن
                                            </div>

                                            <div class="mt-1 text-[11px] leading-5 text-[var(--color-text-muted)]">
                                                اطلاعات پرداخت شما محافظت می‌شود.
                                            </div>

                                        </div>

                                    </div>


                                    <div class="flex items-start gap-3">

                                        <div
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-[var(--color-brand-900)] shadow-sm"
                                        >
                                            <svg
                                                class="h-4 w-4"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                aria-hidden="true"
                                            >
                                                <path d="M3 7h11v10H3z"/>
                                                <path d="M14 10h3l4 4v3h-7z"/>
                                                <circle cx="7" cy="19" r="1.5"/>
                                                <circle cx="18" cy="19" r="1.5"/>
                                            </svg>
                                        </div>

                                        <div>

                                            <div class="text-xs font-black text-[var(--color-text-primary)]">
                                                ارسال مطمئن
                                            </div>

                                            <div class="mt-1 text-[11px] leading-5 text-[var(--color-text-muted)]">
                                                هزینه و شرایط ارسال در مرحله سفارش مشخص می‌شود.
                                            </div>

                                        </div>

                                    </div>


                                    <div class="flex items-start gap-3">

                                        <div
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-[var(--color-accent-600)] shadow-sm"
                                        >
                                            <svg
                                                class="h-4 w-4"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                aria-hidden="true"
                                            >
                                                <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v8Z"/>
                                            </svg>
                                        </div>

                                        <div>

                                            <div class="text-xs font-black text-[var(--color-text-primary)]">
                                                نیاز به کمک؟
                                            </div>

                                            <a
                                                href="{{ route('contact.index') }}"
                                                class="mt-1 inline-block text-[11px] font-bold text-[var(--color-accent-600)] hover:text-[var(--color-accent-700)]"
                                            >
                                                تماس با پشتیبانی
                                            </a>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </aside>

                </div>

            @endif

        </section>

    </div>

@endsection


@push('scripts')
    <script>
        function changeCartQuantity(button, delta) {
            const form = button.closest('form');

            if (!form) {
                return;
            }

            const input = form.querySelector('input[name="quantity"]');

            if (!input) {
                return;
            }

            const min = parseInt(input.min || '1', 10);
            const max = parseInt(input.max || '99', 10);
            const current = parseInt(input.value || '1', 10);

            const safeCurrent = Number.isFinite(current)
                ? current
                : min;

            const next = Math.min(
                max,
                Math.max(min, safeCurrent + delta)
            );

            input.value = next;

            form.submit();
        }
    </script>
@endpush
