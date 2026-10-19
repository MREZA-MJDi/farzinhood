@props(['product'])
<article class="farzin-product-card" data-product-card>
    <div class="farzin-product-card__media">
        <a class="farzin-product-card__image-link" href="{{ route('products.show', $product) }}" aria-label="مشاهده {{ $product->name }}">
            @if ($product->primaryImage?->image)
                <img src="{{ asset('storage/' . $product->primaryImage->image) }}" alt="{{ $product->primaryImage->alt ?: $product->name }}" loading="lazy">
            @else
                <span class="farzin-product-card__placeholder" aria-hidden="true"><span>FARZIN</span></span>
            @endif
        </a>
        <div class="farzin-product-card__badges">
            @if ($product->is_featured)<span class="farzin-product-card__badge farzin-product-card__badge--dark">منتخب</span>@endif
            @if ($product->old_price && $product->old_price > $product->price)<span class="farzin-product-card__badge farzin-product-card__badge--accent">پیشنهاد ویژه</span>@endif
        </div>
        <a class="farzin-product-card__hover-cta" href="{{ route('products.show', $product) }}">مشاهده محصول <span aria-hidden="true">←</span></a>
    </div>
    <div class="farzin-product-card__body">
        <a class="farzin-product-card__category" href="{{ route('categories.show', $product->category) }}">{{ $product->category?->name }}</a>
        <a class="farzin-product-card__name" href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
        <div class="farzin-product-card__meta">
            <span>{{ $product->brand ?: 'انتخاب فرزین' }}</span>
            @if ($product->rating)<span>★ {{ number_format((float) $product->rating, 1) }}</span>@endif
        </div>
        <div class="farzin-product-card__price-row">
            <div>
                @if ($product->old_price && $product->old_price > $product->price)
                    <span class="farzin-product-card__old">{{ number_format($product->old_price) }} تومان</span>
                @endif
                <strong>{{ number_format($product->price) }} <small>تومان</small></strong>
            </div>
            <span class="farzin-product-card__availability {{ $product->stock < 1 ? 'is-out' : '' }}"><i></i>{{ $product->stock > 0 ? 'موجود' : 'ناموجود' }}</span>
        </div>
    </div>
</article>