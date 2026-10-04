@props([
'images' => [],
'name' => '',
])

@php
    /*
    |--------------------------------------------------------------------------
    | Normalize images
    |--------------------------------------------------------------------------
    |
    | Supports:
    | - ProductImage Eloquent models
    | - arrays
    | - plain strings
    |
    */

    $normalizedImages = collect($images)
        ->map(function ($image) use ($name) {

            /*
            |--------------------------------------------------------------------------
            | Eloquent ProductImage
            |--------------------------------------------------------------------------
            */

            if ($image instanceof \App\Models\ProductImage) {
                return [
                    'src' => $image->image
                        ? asset('storage/' . ltrim($image->image, '/'))
                        : null,

                    'alt' => $image->alt ?: $name,

                    'sort_order' => $image->sort_order,

                    'is_primary' => (bool) $image->is_primary,
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | Array
            |--------------------------------------------------------------------------
            */

            if (is_array($image)) {
                $src = $image['src']
                    ?? $image['image']
                    ?? null;

                return [
                    'src' => $src
                        ? (
                            str_starts_with($src, 'http://')
                            || str_starts_with($src, 'https://')
                            || str_starts_with($src, '/')
                                ? $src
                                : asset('storage/' . ltrim($src, '/'))
                        )
                        : null,

                    'alt' => $image['alt']
                        ?? $name,

                    'sort_order' => $image['sort_order']
                        ?? 0,

                    'is_primary' => (bool) (
                        $image['is_primary']
                        ?? false
                    ),
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | String
            |--------------------------------------------------------------------------
            */

            if (is_string($image) && filled($image)) {
                return [
                    'src' => (
                        str_starts_with($image, 'http://')
                        || str_starts_with($image, 'https://')
                        || str_starts_with($image, '/')
                            ? $image
                            : asset('storage/' . ltrim($image, '/'))
                    ),

                    'alt' => $name,

                    'sort_order' => 0,

                    'is_primary' => false,
                ];
            }


            return null;
        })
        ->filter(
            fn ($image) =>
                filled($image['src'] ?? null)
        )
        ->sortBy(function ($image) {
            return [
                $image['is_primary'] ? 0 : 1,
                $image['sort_order'] ?? 0,
            ];
        })
        ->values();


    $hasImages = $normalizedImages->isNotEmpty();

    $mainImage = $normalizedImages->first();
@endphp


<div
    {{ $attributes->merge([
        'class' => 'product-gallery',
    ]) }}
    data-product-gallery
>

    {{-- =====================================================
         MAIN IMAGE
    ====================================================== --}}

    <div class="product-gallery__main">

        @if($hasImages)

            <img
                src="{{ $mainImage['src'] }}"
                alt="{{ $mainImage['alt'] }}"
                width="900"
                height="900"
                loading="eager"
                decoding="async"
                data-gallery-main
            >

        @else

            <div
                class="product-gallery__empty"
                role="status"
                aria-label="تصویری برای این محصول موجود نیست"
            >

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.6"
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

                <span>
                    تصویر موجود نیست
                </span>

            </div>

        @endif

    </div>


    {{-- =====================================================
         THUMBNAILS
    ====================================================== --}}

    @if($normalizedImages->count() > 1)

        <div
            class="product-gallery__thumbs"
            role="list"
            aria-label="تصاویر {{ $name }}"
        >

            @foreach($normalizedImages as $index => $image)

                <button
                    type="button"
                    class="product-gallery__thumb {{ $index === 0 ? 'is-active' : '' }}"
                    data-gallery-thumb
                    data-image="{{ $image['src'] }}"
                    data-alt="{{ $image['alt'] }}"
                    aria-label="نمایش تصویر {{ $index + 1 }}"
                    aria-pressed="{{ $index === 0 ? 'true' : 'false' }}"
                    role="listitem"
                >

                    <img
                        src="{{ $image['src'] }}"
                        alt="{{ $image['alt'] }}"
                        width="96"
                        height="96"
                        loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                        decoding="async"
                    >

                </button>

            @endforeach

        </div>

    @endif

</div>

