<article class="fz-product" data-product-card data-reveal-item>
    <a class="fz-product-img" href="{{ route('products.show', $product->slug) }}" aria-label="مشاهده {{ $product->name }}">
        @if ($product->primaryImage)
            <img
                src="{{ asset('storage/' . $product->primaryImage->image) }}"
                alt="{{ $product->primaryImage->alt ?: $product->name }}"
                loading="lazy"
                decoding="async"
                sizes="(max-width: 520px) 44vw, (max-width: 1050px) 30vw, 22vw"
                data-product-image
            >
        @else
            <div class="fz-product-placeholder" aria-label="تصویر محصول موجود نیست">FARZIN</div>
        @endif
    </a>

    <div class="fz-product-body">
        @if ($product->brand)
            <span class="fz-brand">{{ $product->brand }}</span>
        @elseif ($product->category)
            <span class="fz-brand">{{ $product->category->name }}</span>
        @endif

        <h3><a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a></h3>

        @if ((int) $product->review_count > 0)
            <p class="fz-product-rating" aria-label="امتیاز ثبت‌شده {{ number_format((float) $product->rating, 1) }} از ۵">
                <span aria-hidden="true">★</span>
                {{ number_format((float) $product->rating, 1) }}
                <span>({{ number_format((int) $product->review_count) }} دیدگاه)</span>
            </p>
        @endif

        <div class="fz-price">
            {{ number_format($product->price) }}
            <small>تومان</small>
            @if ($product->old_price && $product->old_price > $product->price)
                <span class="fz-old">{{ number_format($product->old_price) }}</span>
            @endif
        </div>

        @if ($product->stock > 0)
            @auth
                @if (auth()->user()->isCustomer())
                    <form method="POST" action="{{ route('customer.cart.add') }}" class="fz-product-buy" data-cart-add>
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button class="fz-btn" type="submit" data-cart-add-button>
                            افزودن به سبد خرید
                        </button>
                    </form>
                @else
                    <p class="fz-product-note">خرید با حساب مشتری امکان‌پذیر است.</p>
                @endif
            @else
                <a class="fz-btn fz-product-buy-link" href="{{ route('login') }}">ورود برای خرید</a>
            @endauth
        @else
            <span class="fz-product-note" aria-label="ناموجود">در حال حاضر ناموجود</span>
        @endif

        <a class="fz-product-link" href="{{ route('products.show', $product->slug) }}">
            <span>جزئیات محصول</span><span aria-hidden="true">←</span>
        </a>
    </div>
</article>
