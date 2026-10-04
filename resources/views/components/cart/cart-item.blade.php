@props([
'item',
])

<article
    class="cart-item"
    data-cart-item
    data-cart-id="{{ $item['id'] ?? '' }}"
>

    <a
        href="{{ $item['url'] ?? '#' }}"
        class="cart-item__image"
    >

        <img
            src="{{ $item['image'] ?? '' }}"
            alt="{{ $item['name'] ?? '' }}"
            loading="lazy"
        >

    </a>


    <div class="cart-item__content">

        @if(!empty($item['brand']))
            <span class="cart-item__brand">
                {{ $item['brand'] }}
            </span>
        @endif

        <h3 class="cart-item__title">

            <a href="{{ $item['url'] ?? '#' }}">
                {{ $item['name'] ?? '' }}
            </a>

        </h3>


        @if(!empty($item['meta']))
            <div class="cart-item__meta">
                {{ $item['meta'] }}
            </div>
        @endif


        <div class="cart-item__price">
            {{ number_format($item['price'] ?? 0) }}
            تومان
        </div>

    </div>


    <div class="cart-item__actions">

        <x-product.quantity
            name="items[{{ $item['id'] ?? '' }}][quantity]"
            :value="$item['quantity'] ?? 1"
            :min="1"
        />

        <button
            type="button"
            class="icon-btn icon-btn--sm"
            data-remove-cart-item
            aria-label="حذف {{ $item['name'] ?? '' }}"
        >
            ×
        </button>

    </div>

</article>
