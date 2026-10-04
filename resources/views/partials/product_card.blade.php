@php
    $image = $product->primaryImage?->image;
    $price = (float) $product->price;
    $oldPrice = $product->old_price !== null ? (float) $product->old_price : null;

    $hasDiscount = $oldPrice !== null && $oldPrice > $price;

    $computedDiscount = $hasDiscount
        ? (int) round((($oldPrice - $price) / $oldPrice) * 100)
        : 0;

    $discount = (int) ($product->discount ?: $computedDiscount);

    $isAvailable = $product->is_active && (int) $product->stock > 0;
    $isLowStock = $isAvailable && (int) $product->stock <= 5;
    $rating = (float) ($product->rating ?? 0);
    $reviewCount = (int) ($product->review_count ?? 0);
@endphp

<article class="farzin-product-card group min-w-0">
    <div class="farzin-product-card__media">
        <a
            href="{{ route('products.show', $product) }}"
            class="farzin-product-card__image-link"
            aria-label="مشاهده {{ $product->name }}"
        >
            @if($image)
                <img
                    src="{{ asset('storage/' . $image) }}"
                    alt="{{ $product->name }}"
                    loading="lazy"
                    decoding="async"
                >
            @else
                <div class="farzin-product-card__placeholder" aria-hidden="true">◎</div>
            @endif
        </a>

        <div class="farzin-product-card__badges">
            @if($discount > 0)
                <span class="farzin-product-card__badge farzin-product-card__badge--accent">
                    {{ $discount }}٪
                </span>
            @elseif($product->is_featured)
                <span class="farzin-product-card__badge">منتخب</span>
            @endif

            @if(!$isAvailable)
                <span class="farzin-product-card__badge farzin-product-card__badge--dark">ناموجود</span>
            @elseif($isLowStock)
                <span class="farzin-product-card__badge farzin-product-card__badge--light">فقط {{ $product->stock }} عدد</span>
            @endif
        </div>

        @if(auth()->check() && auth()->user()->isCustomer())
            <form action="{{ route('customer.wishlist.toggle', $product) }}" method="POST" class="farzin-product-card__wishlist">
                @csrf
                <button type="submit" aria-label="ذخیره {{ $product->name }} در علاقه‌مندی‌ها">♡</button>
            </form>
        @endif

        <div class="farzin-product-card__hover-cta" aria-hidden="true">
            مشاهده محصول <span>←</span>
        </div>
    </div>

    <div class="farzin-product-card__body">
        @if($product->category)
            <a href="{{ route('categories.show', $product->category) }}" class="farzin-product-card__category">
                {{ $product->category->name }}
            </a>
        @endif

        <a href="{{ route('products.show', $product) }}" class="farzin-product-card__name">
            {{ $product->name }}
        </a>

        <div class="farzin-product-card__meta">
            @if($product->sku)
                <span dir="ltr">{{ $product->sku }}</span>
            @else
                <span></span>
            @endif

            @if($reviewCount > 0)
                <span aria-label="امتیاز {{ number_format($rating, 1) }} از ۵">
                    ★ {{ number_format($rating, 1) }}
                </span>
            @endif
        </div>

        <div class="farzin-product-card__price-row">
            <div>
                @if($hasDiscount)
                    <span class="farzin-product-card__old">{{ number_format($oldPrice) }}</span>
                @endif

                <strong>{{ number_format($price) }}</strong>
                <small>تومان</small>
            </div>

            @if($isAvailable)
                <span class="farzin-product-card__availability"><i></i> موجود</span>
            @else
                <span class="farzin-product-card__availability is-out">ناموجود</span>
            @endif
        </div>
    </div>
</article>
