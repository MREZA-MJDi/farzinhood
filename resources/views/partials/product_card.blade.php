@php
    $image = $product->primaryImage?->image;
    $imageUrl = $image ? asset('storage/' . $image) : null;

    $discount = (int) ($product->discount ?? 0);
    $hasOldPrice = $product->old_price !== null && (int) $product->old_price > (int) $product->price;
    $isAvailable = $product->is_active && (int) $product->stock > 0;
    $isLowStock = $isAvailable && (int) $product->stock <= 5;
    $rating = (float) ($product->rating ?? 0);
    $reviewCount = (int) ($product->review_count ?? 0);
    $isWishlisted = collect($wishlistedProductIds ?? [])->contains($product->id);
@endphp

<article
    class="group min-w-0"
    data-product-card
>
    <div class="overflow-hidden rounded-[1.75rem] border border-[var(--color-border)] bg-white shadow-[var(--shadow-xs)] transition duration-300 hover:-translate-y-1 hover:border-[var(--color-earth-300)] hover:shadow-[var(--shadow-md)]">

        <div class="relative overflow-hidden bg-[var(--color-earth-50)]">
            <div class="pointer-events-none absolute -right-10 -top-10 size-32 rounded-full bg-white/70 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-12 -left-12 size-36 rounded-full bg-[var(--color-earth-200)]/40 blur-3xl"></div>

            <div class="absolute inset-x-3 top-3 z-20 flex items-start justify-between gap-2">
                <div>
                    @if($discount > 0)
                        <span class="inline-flex items-center rounded-full bg-[var(--color-accent-600)] px-3 py-1.5 text-[10px] font-black text-white shadow-sm">
                            {{ $discount }}٪ تخفیف
                        </span>
                    @elseif($product->is_featured)
                        <span class="inline-flex items-center gap-1 rounded-full bg-[var(--color-brand-950)] px-3 py-1.5 text-[10px] font-black text-white shadow-sm">
                            <span aria-hidden="true">✦</span>
                            منتخب فرزین
                        </span>
                    @endif
                </div>

                @auth
                    @if(auth()->user()->isCustomer())
                        <form action="{{ route('customer.wishlist.toggle', $product) }}" method="POST">
                            @csrf
                            <button
                                type="submit"
                                class="flex size-10 items-center justify-center rounded-xl border border-white/80 bg-white/95 text-[var(--color-accent-600)] shadow-md backdrop-blur transition hover:bg-[var(--color-accent-50)]"
                                aria-label="{{ $isWishlisted ? 'حذف ' . $product->name . ' از علاقه‌مندی‌ها' : 'افزودن ' . $product->name . ' به علاقه‌مندی‌ها' }}"
                                title="{{ $isWishlisted ? 'حذف از علاقه‌مندی‌ها' : 'افزودن به علاقه‌مندی‌ها' }}"
                            >
                                <svg class="size-4.5" viewBox="0 0 24 24" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <path d="M20.8 8.7c0 5.2-8.8 10.3-8.8 10.3S3.2 13.9 3.2 8.7A4.7 4.7 0 0 1 12 6.2a4.7 4.7 0 0 1 8.8 2.5Z"/>
                                </svg>
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="flex size-10 items-center justify-center rounded-xl border border-white/80 bg-white/95 text-[var(--color-text-muted)] shadow-md backdrop-blur transition hover:bg-[var(--color-accent-50)] hover:text-[var(--color-accent-600)]" aria-label="ورود برای افزودن به علاقه‌مندی‌ها">
                        <svg class="size-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                            <path d="M20.8 8.7c0 5.2-8.8 10.3-8.8 10.3S3.2 13.9 3.2 8.7A4.7 4.7 0 0 1 12 6.2a4.7 4.7 0 0 1 8.8 2.5Z"/>
                        </svg>
                    </a>
                @endauth
            </div>

            <a href="{{ route('products.show', $product) }}" class="block" aria-label="مشاهده {{ $product->name }}">
                <div class="aspect-[0.96] overflow-hidden">
                    @if($imageUrl)
                        <img
                            src="{{ $imageUrl }}"
                            alt="{{ $product->name }}"
                            class="size-full object-cover transition duration-700 ease-out group-hover:scale-[1.045]"
                            loading="lazy"
                            decoding="async"
                        >
                    @else
                        <div class="flex size-full items-center justify-center text-[var(--color-text-soft)]">
                            <svg class="size-14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.15" aria-hidden="true">
                                <rect x="3" y="4" width="18" height="16" rx="2"/>
                                <circle cx="8.5" cy="9" r="1.4"/>
                                <path d="m21 15-5-5-4.5 4.5-2.5-2.5L3 18"/>
                            </svg>
                        </div>
                    @endif
                </div>
            </a>

            @if(!$isAvailable)
                <span class="absolute bottom-3 right-3 z-20 rounded-full bg-[var(--color-brand-950)]/90 px-3 py-1.5 text-[10px] font-black text-white">
                    ناموجود
                </span>
            @elseif($isLowStock)
                <span class="absolute bottom-3 right-3 z-20 rounded-full border border-white/80 bg-white/90 px-3 py-1.5 text-[10px] font-black text-[var(--color-earth-800)] shadow-sm backdrop-blur">
                    فقط {{ $product->stock }} عدد
                </span>
            @endif
        </div>

        <div class="p-4 sm:p-5">
            <div class="flex items-center justify-between gap-3">
                @if($product->category)
                    <a href="{{ route('categories.show', $product->category) }}" class="truncate text-[10px] font-black uppercase tracking-[0.16em] text-[var(--color-earth-700)] transition hover:text-[var(--color-accent-600)]">
                        {{ $product->category->name }}
                    </a>
                @endif

                @if($reviewCount > 0)
                    <span class="inline-flex shrink-0 items-center gap-1 text-[10px] font-black text-[var(--color-text-muted)]" title="{{ number_format($rating, 1) }} از 5 بر اساس {{ number_format($reviewCount) }} نظر">
                        <svg class="size-3.5 text-amber-500" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="m12 2.8 2.8 5.7 6.2.9  -4.5 4.4 1.1 6.2-5.6-3 1.1-6.2L3 9.4l6.2-.9L12 2.8Z"/>
                        </svg>
                        {{ number_format($rating, 1) }}
                    </span>
                @endif
            </div>

            <a href="{{ route('products.show', $product) }}" class="mt-2 block">
                <h3 class="min-h-[3.2rem] line-clamp-2 text-sm font-black leading-7 text-[var(--color-text-primary)] transition hover:text-[var(--color-brand-900)] sm:text-[15px]">
                    {{ $product->name }}
                </h3>
            </a>

            @if($product->sku)
                <div class="mt-1 font-mono text-[9px] text-[var(--color-text-soft)]" dir="ltr">{{ $product->sku }}</div>
            @endif

            <div class="mt-4 flex items-end justify-between gap-3">
                <div class="min-w-0">
                    @if($hasOldPrice)
                        <div class="flex items-center gap-2">
                            <del class="text-[10px] text-[var(--color-text-soft)]">{{ number_format($product->old_price) }}</del>
                            @if($discount > 0)
                                <span class="rounded-md bg-[var(--color-accent-50)] px-1.5 py-0.5 text-[9px] font-black text-[var(--color-accent-700)]">{{ $discount }}٪</span>
                            @endif
                        </div>
                    @endif

                    <div class="mt-1 flex items-baseline gap-1">
                        <strong class="text-lg font-black tracking-tight text-[var(--color-brand-950)]">{{ number_format($product->price) }}</strong>
                        <span class="text-[9px] font-bold text-[var(--color-text-muted)]">تومان</span>
                    </div>
                </div>

                @if($isAvailable)
                    @auth
                        @if(auth()->user()->isCustomer())
                            <form action="{{ route('customer.cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="flex size-11 items-center justify-center rounded-xl bg-[var(--color-accent-600)] text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[var(--color-accent-700)]" title="افزودن به سبد خرید" aria-label="افزودن {{ $product->name }} به سبد خرید">
                                    <svg class="size-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                        <path d="M3 4h2l2.4 11.2a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 2-1.6L21 7H6"/>
                                        <circle cx="10" cy="20" r="1"/><circle cx="18" cy="20" r="1"/>
                                    </svg>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('admin.dashboard') }}" class="rounded-xl bg-[var(--color-brand-900)] px-3 py-2.5 text-[9px] font-black text-white">
                                پنل
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="rounded-xl border border-[var(--color-border)] bg-[var(--color-earth-50)] px-3 py-2.5 text-[9px] font-black text-[var(--color-text-secondary)] transition hover:border-[var(--color-brand-300)] hover:text-[var(--color-brand-900)]">
                            ورود برای خرید
                        </a>
                    @endauth
                @else
                    <span class="rounded-xl bg-[var(--color-neutral-100)] px-3 py-2.5 text-[9px] font-black text-[var(--color-text-muted)]">
                        ناموجود
                    </span>
                @endif
            </div>
        </div>
    </div>
</article>
