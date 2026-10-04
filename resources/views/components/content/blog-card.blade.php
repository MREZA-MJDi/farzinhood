@props([
'title',
'image',
'href' => '#',
'category' => null,
'excerpt' => null,
'meta' => null,
'alt' => null,
])

<article class="blog-card">

    <a
        href="{{ $href }}"
        class="blog-card__image"
        aria-label="مطالعه {{ $title }}"
    >
        <img
            src="{{ $image }}"
            alt="{{ $alt ?? $title }}"
            width="800"
            height="500"
            loading="lazy"
        >
    </a>


    <div class="blog-card__content">

        @if($category)
            <span class="blog-card__category">
                {{ $category }}
            </span>
        @endif


        <h3 class="blog-card__title">

            <a href="{{ $href }}">
                {{ $title }}
            </a>

        </h3>


        @if($excerpt)
            <p class="blog-card__excerpt">
                {{ $excerpt }}
            </p>
        @endif


        @if($meta)
            <div class="blog-card__meta">

                <span>
                    {{ $meta }}
                </span>

                <span aria-hidden="true">
                    ←
                </span>

            </div>
        @endif

    </div>

</article>
