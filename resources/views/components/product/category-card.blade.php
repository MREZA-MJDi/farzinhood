@props([
'title',
'image' => null,
'href' => '#',
'eyebrow' => null,
'description' => null,
])

@php
    /*
    |--------------------------------------------------------------------------
    | Category Image URL
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


<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => 'category-card',
    ]) }}
>

    {{-- =====================================================
         IMAGE
    ====================================================== --}}

    <div class="category-card__image">

        @if($imageUrl)

            <img
                src="{{ $imageUrl }}"
                alt="{{ $title }}"
                width="800"
                height="600"
                loading="lazy"
                decoding="async"
            >

        @else

            <div
                class="category-card__image-placeholder"
                role="img"
                aria-label="تصویر {{ $title }} موجود نیست"
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


    {{-- =====================================================
         OVERLAY
    ====================================================== --}}

    <span
        class="category-card__overlay"
        aria-hidden="true"
    ></span>


    {{-- =====================================================
         CONTENT
    ====================================================== --}}

    <div class="category-card__content">

        @if($eyebrow)

            <span class="category-card__eyebrow">
                {{ $eyebrow }}
            </span>

        @endif


        <h3 class="category-card__title">
            {{ $title }}
        </h3>


        @if($description)

            <p class="category-card__description">
                {{ $description }}
            </p>

        @endif


        <span class="category-card__link">

            <span>
                مشاهده محصولات
            </span>

            <span
                class="category-card__link-arrow"
                aria-hidden="true"
            >
                ←
            </span>

        </span>

    </div>

</a>

