@extends('layouts.app')

@section('title', 'سبد خرید | فرزین')
@section('meta_description', 'مدیریت سبد خرید و ادامه ثبت سفارش در فروشگاه فرزین')

@section('content')
    <section class="farzin-container py-8 sm:py-10 lg:py-12">
        <header class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <span class="farzin-eyebrow">FARZIN / CART</span>
                <h1 class="farzin-page-title mt-3">سبد خرید</h1>
                <p class="farzin-section-description mt-3 max-w-2xl">
                    انتخاب‌هایت را بررسی کن، تعداد را تنظیم کن و برای ثبت سفارش ادامه بده.
                </p>
            </div>

            @if($itemCount > 0)
                <span class="inline-flex w-fit items-center gap-2 rounded-full border border-[var(--color-earth-200)] bg-[var(--color-earth-50)] px-4 py-2 text-xs font-black text-[var(--color-earth-800)]">
                    {{ number_format($itemCount) }} قلم در سبد
                </span>
            @endif
        </header>

        @if(session('success'))
            <div class="mt-6 rounded-2xl border border-[var(--color-success-100)] bg-[var(--color-success-50)] px-4 py-3 text-sm font-bold text-[var(--color-success-700)]">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mt-6 rounded-2xl border border-[var(--color-danger-100)] bg-[var(--color-danger-50)] px-4 py-3 text-sm font-bold text-[var(--color-danger-700)]">
                {{ session('error') }}
            </div>
        @endif

        @php($cartItems = $items->items)

        @if($cartItems->isEmpty())
            <div class="mt-8 overflow-hidden rounded-[2rem] border border-[var(--color-border)] bg-white shadow-[var(--shadow-sm)]">
                <div class="relative px-6 py-20 text-center sm:py-24">
                    <div class="pointer-events-none absolute -right-20 -top-20 size-56 rounded-full bg-[var(--color-earth-200)]/35 blur-3xl"></div>
                    <div class="pointer-events-none absolute -bottom-24 -left-20 size-64 rounded-full bg-[var(--color-brand-200)]/20 blur-3xl"></div>

                    <div class="relative mx-auto flex size-16 items-center justify-center rounded-2xl bg-[var(--color-earth-100)] text-[var(--color-earth-800)]">
                        <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                            <path d="M3 4h2l2.4 11.2a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 2-1.6L21 7H6"/>
                            <circle cx="10" cy="20" r="1"/>
                            <circle cx="18" cy="20" r="1"/>
                        </svg>
                    </div>

                    <h2 class="relative mt-5 text-2xl font-black text-[var(--color-text-primary)]">سبد خریدت خالیه</h2>
                    <p class="relative mx-auto mt-2 max-w-md text-sm leading-7 text-[var(--color-text-secondary)]">
                        هنوز محصولی انتخاب نکردی. از فروشگاه شروع کن و انتخابت را بساز.
                    </p>

                    <a href="{{ route('shop.index') }}" class="relative mt-6 inline-flex items-center gap-2 rounded-2xl bg-[var(--color-brand-900)] px-6 py-3.5 text-sm font-black text-white transition hover:-translate-y-0.5 hover:bg-[var(--color-brand-950)]">
                        رفتن به فروشگاه
                        <span aria-hidden="true">←</span>
                    </a>
                </div>
            </div>
        @else
            <div class="mt-8 grid gap-6 xl:grid-cols-[minmax(0,1fr)_340px]">
                <section class="space-y-3" aria-label="اقلام سبد خرید">
                    @foreach($cartItems as $item)
                        @php
                            $product = $item->product;
                            $available = $product?->is_active && $product->stock > 0;
                            $maxQuantity = min(max((int) ($product?->stock ?? 1), 1), 99);
                            $lineTotal = (int) $item->unit_price * (int) $item->quantity;
                        @endphp

                        @if($product)
                            <article class="group overflow-hidden rounded-[1.75rem] border border-[var(--color-border)] bg-white shadow-[var(--shadow-xs)] transition hover:-translate-y-0.5 hover:border-[var(--color-earth-300)] hover:shadow-[var(--shadow-md)]">
                                <div class="flex flex-col gap-5 p-4 sm:flex-row sm:items-center sm:p-5">
                                    <a href="{{ route('products.show', $product) }}" class="shrink-0">
                                        <div class="relative size-28 overflow-hidden rounded-2xl bg-[var(--color-earth-50)] sm:size-32">
                                            @if($product->primaryImage?->image)
                                                <img src="{{ $product->primaryImage->url }}" alt="{{ $product->name }}" class="size-full object-cover transition duration-500 group-hover:scale-105" loading="lazy" decoding="async">
                                            @else
                                                <div class="flex size-full items-center justify-center text-[var(--color-text-soft)]">
                                                    <svg class="size-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" aria-hidden="true">
                                                        <rect x="3" y="4" width="18" height="16" rx="2"/>
                                                        <circle cx="8.5" cy="9" r="1.4"/>
                                                        <path d="m21 15-5-5-4.5 4.5-2.5-2.5L3 18"/>
                                                    </svg>
                                                </div>
                                            @endif

                                            @unless($available)
                                                <span class="absolute inset-x-2 bottom-2 rounded-full bg-white/95 px-3 py-1.5 text-center text-[9px] font-black text-[var(--color-danger-700)] shadow-sm">ناموجود</span>
                                            @endunless
                                        </div>
                                    </a>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="min-w-0">
                                                @if($product->category)
                                                    <a href="{{ route('categories.show', $product->category) }}" class="text-[10px] font-black uppercase tracking-[0.15em] text-[var(--color-earth-700)] hover:text-[var(--color-accent-600)]">
                                                        {{ $product->category->name }}
                                                    </a>
                                                @endif
                                                <a href="{{ route('products.show', $product) }}" class="mt-2 block">
                                                    <h2 class="truncate text-base font-black text-[var(--color-text-primary)] transition group-hover:text-[var(--color-brand-900)] sm:text-lg">
                                                        {{ $product->name }}
                                                    </h2>
                                                </a>
                                                @if($product->sku)
                                                    <span class="mt-1 block font-mono text-[10px] text-[var(--color-text-soft)]" dir="ltr">{{ $product->sku }}</span>
                                                @endif
                                            </div>

                                            <form action="{{ route('customer.cart.remove', $product) }}" method="POST" class="shrink-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex size-9 items-center justify-center rounded-xl text-[var(--color-text-muted)] transition hover:bg-[var(--color-danger-50)] hover:text-[var(--color-danger-700)]" aria-label="حذف {{ $product->name }} از سبد" title="حذف">
                                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                                        <path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 15H6L5 6"/><path d="M10 11v6M14 11v6"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>

                                        <div class="mt-5 flex flex-col gap-4 border-t border-[var(--color-border)] pt-4 sm:flex-row sm:items-end sm:justify-between">
                                            <form action="{{ route('customer.cart.update', $product) }}" method="POST" class="flex items-center gap-3">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <span class="text-xs font-bold text-[var(--color-text-muted)]">تعداد</span>
                                                <div class="flex h-10 items-center overflow-hidden rounded-xl border border-[var(--color-border)] bg-[var(--color-earth-50)]">
                                                    <button type="button" class="flex h-full w-9 items-center justify-center text-lg text-[var(--color-text-muted)] transition hover:bg-white" onclick="const input=this.nextElementSibling;input.value=Math.max(1,Number(input.value)-1);input.form.submit();" aria-label="کاهش تعداد">−</button>
                                                    <input name="quantity" type="number" value="{{ $item->quantity }}" min="1" max="{{ $maxQuantity }}" class="h-full w-12 border-x border-[var(--color-border)] bg-transparent text-center text-xs font-black outline-none" onchange="this.form.submit();" aria-label="تعداد {{ $product->name }}">
                                                    <button type="button" class="flex h-full w-9 items-center justify-center text-lg text-[var(--color-text-muted)] transition hover:bg-white" onclick="const input=this.previousElementSibling;input.value=Math.min(Number(input.max),Number(input.value)+1);input.form.submit();" aria-label="افزایش تعداد">+</button>
                                                </div>
                                            </form>

                                            <div class="text-start">
                                                <span class="block text-[10px] text-[var(--color-text-muted)]">قیمت نهایی این قلم</span>
                                                <strong class="mt-1 block text-base font-black text-[var(--color-brand-950)]">
                                                    {{ number_format($lineTotal) }}
                                                    <small class="text-[10px] font-bold text-[var(--color-text-muted)]">تومان</small>
                                                </strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endif
                    @endforeach

                    <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:justify-between">
                        <a href="{{ route('shop.index') }}" class="inline-flex items-center justify-center rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-xs font-black text-[var(--color-text-secondary)] transition hover:border-[var(--color-earth-300)] hover:bg-[var(--color-earth-50)]">
                            ← ادامه خرید
                        </a>

                        <form action="{{ route('customer.cart.clear') }}" method="POST" onsubmit="return confirm('سبد خرید خالی شود؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl border border-[var(--color-danger-100)] bg-[var(--color-danger-50)] px-4 py-3 text-xs font-black text-[var(--color-danger-700)] transition hover:bg-[var(--color-danger-100)] sm:w-auto">
                                خالی کردن سبد
                            </button>
                        </form>
                    </div>
                </section>

                <aside class="xl:sticky xl:top-28 xl:self-start">
                    <section class="overflow-hidden rounded-[1.75rem] border border-[var(--color-brand-900)] bg-[var(--color-brand-950)] text-white shadow-[var(--shadow-lg)]">
                        <div class="relative p-6">
                            <div class="absolute -left-16 -top-16 size-40 rounded-full bg-[var(--color-earth-400)]/15 blur-3xl"></div>
                            <span class="relative text-[9px] font-black uppercase tracking-[0.22em] text-[var(--color-earth-200)]">ORDER SUMMARY</span>
                            <h2 class="relative mt-2 text-xl font-black">خلاصه سفارش</h2>

                            <div class="relative mt-6 space-y-4 border-t border-white/10 pt-5">
                                <div class="flex items-center justify-between gap-4 text-xs text-white/65">
                                    <span>جمع کالاها</span>
                                    <strong class="text-white">{{ number_format($subtotal) }} تومان</strong>
                                </div>
                                <div class="flex items-center justify-between gap-4 text-xs text-white/65">
                                    <span>تعداد اقلام</span>
                                    <strong class="text-white">{{ number_format($itemCount) }}</strong>
                                </div>
                            </div>

                            <div class="relative mt-6 border-t border-white/10 pt-5">
                                <span class="text-[10px] text-white/55">مبلغ کالاها</span>
                                <div class="mt-1 flex items-end justify-between gap-3">
                                    <strong class="text-2xl font-black">{{ number_format($subtotal) }}</strong>
                                    <span class="text-[10px] text-white/55">تومان</span>
                                </div>
                            </div>

                            <a href="{{ route('customer.checkout.index') }}" class="relative mt-6 inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-[var(--color-earth-100)] px-5 py-4 text-sm font-black text-[var(--color-earth-900)] transition hover:-translate-y-0.5 hover:bg-white">
                                ادامه برای ثبت سفارش
                                <span aria-hidden="true">←</span>
                            </a>
                        </div>
                    </section>

                    <div class="mt-3 rounded-2xl border border-[var(--color-border)] bg-white p-4 text-[10px] leading-6 text-[var(--color-text-muted)]">
                        قیمت‌های این صفحه از موجودی و اطلاعات فعلی محصولات فروشگاه محاسبه می‌شوند.
                    </div>
                </aside>
            </div>
        @endif
    </section>
@endsection
