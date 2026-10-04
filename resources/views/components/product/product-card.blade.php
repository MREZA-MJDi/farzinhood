@props([
'id' => null,
'name',
'image' => null,
'href' => '#',
'price',
'oldPrice' => null,
'discount' => null,
'brand' => null,
'meta' => null,
'rating' => null,
'reviewCount' => null,
'badge' => null,
'isWishlisted' => false,
'alt' => null,
])

@php
    /*
    |--------------------------------------------------------------------------
    | Image URL
    |--------------------------------------------------------------------------
    */

    $imageUrl = null;

    if (filled($image)) {
        $imageUrl = (
            str_starts_with($image, 'http://') ||
            str_starts_with($image, 'https://') ||
            str_starts_with($image, '/')
        )
            ? $image
            : asset('storage/' . ltrim($image, '/'));
    }
@endphp

<article
    {{ $attributes->merge([
        'class' => 'product-card',
    ]) }}
    data-product-card

    @if($id)
    data-product-id="{{ $id }}"
    @endif
>

    {{-- =====================================================
         MEDIA
    ====================================================== --}}

    <div class="product-card__media">

        @if($badge)
            <span class="product-card__badge">
                {{ $badge }}
            </span>
        @endif


        {{-- Wishlist --}}
        <button
            type="button"
            class="icon-btn icon-btn--border product-card__wishlist"

            aria-label="{{ $isWishlisted
                ? 'حذف ' . $name . ' از علاقه‌مندی‌ها'
                : 'افزودن ' . $name . ' به علاقه‌مندی‌ها'
            }}"

            aria-pressed="{{ $isWishlisted ? 'true' : 'false' }}"

            data-wishlist

            @if($id)
            data-product-id="{{ $id }}"
            @endif
        >
            <svg
                viewBox="0 0 24 24"
                fill="{{ $isWishlisted ? 'currentColor' : 'none' }}"
                stroke="currentColor"
                stroke-width="1.7"
                aria-hidden="true"
            >
                <path
                    d="M20.8 8.7C20.8 13.7 12 19 12 19S3.2 13.7 3.2 8.7C3.2 6 5.2 4 7.8 4C9.5 4 11 4.9 12 6.2C13 4.9 14.5 4 16.2 4C18.8 4 20.8 6 20.8 8.7Z"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>
        </button>


        {{-- Product Image --}}
        <a
            href="{{ $href }}"
            class="product-card__image-link"
            aria-label="مشاهده {{ $name }}"
        >

            <div class="product-card__image">

                @if($imageUrl)

                    <img
                        src="{{ $imageUrl }}"
                        alt="{{ $alt ?? $name }}"
                        width="800"
                        height="800"
                        loading="lazy"
                        decoding="async"
                    >

                @else

                    <div
                        class="product-card__image-placeholder"
                        role="img"
                        aria-label="تصویر {{ $name }} موجود نیست"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                            aria-hidden="true"
                        >
                            <rect
                                x="4"
                                y="4"
                                width="16"
                                height="16"
                                rx="2"
                            />

                            <circle
                                cx="9"
                                cy="9"
                                r="1.5"
                            />

                            <path
                                d="M4 16L9 11L13 15L16 12L20 16"
                            />
                        </svg>
                    </div>

                @endif

            </div>

        </a>

    </div>


    {{-- =====================================================
         CONTENT
    ====================================================== --}}

    <div class="product-card__content">

        {{-- Brand --}}
        @if($brand)

            <span class="product-card__brand">
                {{ $brand }}
            </span>

        @endif


        {{-- Title --}}
        <h3 class="product-card__title">

            <a href="{{ $href }}">
                {{ $name }}
            </a>

        </h3>


        {{-- Meta --}}
        @if($meta)

            <p class="product-card__meta">
                {{ $meta }}
            </p>

        @endif


        {{-- Rating --}}
        @if($rating !== null)

            <div
                class="product-card__rating"
                aria-label="امتیاز {{ $rating }} از ۵"
            >

                <span
                    class="product-card__rating-stars"
                    aria-hidden="true"
                >
                    ★
                </span>

                <span>
                    {{ $rating }}
                </span>

                @if($reviewCount !== null)

                    <span class="product-card__reviews">
                        ({{ $reviewCount }})
                    </span>

                @endif

            </div>

        @endif


        {{-- Bottom --}}
        <div class="product-card__bottom">

            <x-product.price
                :price="$price"
                :old-price="$oldPrice"
                :discount="$discount"
            />


            {{-- Add To Cart --}}
            <button
                type="button"
                class="product-card__add"

                aria-label="افزودن {{ $name }} به سبد خرید"

                data-add-to-cart

                @if($id)
                data-product-id="{{ $id }}"
                @endif

                @disabled(!$id || !is_numeric($id))
            >

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.7"
                    aria-hidden="true"
                >
                    <path d="M3 4H5L7.2 15.5H18L21 7H6" />

                    <circle
                        cx="9"
                        cy="19"
                        r="1.2"
                    />

                    <circle
                        cx="17"
                        cy="19"
                        r="1.2"
                    />
                </svg>

            </button>

        </div>

    </div>

</article>
