@extends('layouts.storefront')

@section('title', $post->meta_title ?: $post->title . ' | مجله فرزین')
@section('meta_description', $post->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($post->excerpt ?: $post->content), 155))

@push('head')
    <link rel="canonical" href="{{ $post->canonical_url ?: route('blog.show', $post->slug) }}">
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $post->meta_title ?: $post->title }}">
    <meta property="og:description" content="{{ $post->meta_description ?: $post->excerpt }}">
    @if ($post->featured_image)
        <meta property="og:image" content="{{ asset('storage/' . $post->featured_image) }}">
    @endif
    @if ($post->noindex)
        <meta name="robots" content="noindex, follow">
    @endif
    <script type="application/ld+json">@json([
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'mainEntityOfPage' => route('blog.show', $post->slug),
        'headline' => $post->title,
        'description' => \Illuminate\Support\Str::limit(strip_tags($post->excerpt ?: $post->content), 300),
        'datePublished' => $post->published_at?->toIso8601String(),
        'dateModified' => $post->updated_at?->toIso8601String(),
        'image' => $post->featured_image ? asset('storage/' . $post->featured_image) : null,
        'author' => $post->author?->name ? ['@type' => 'Person', 'name' => $post->author->name] : ['@type' => 'Organization', 'name' => 'فرزین'],
        'publisher' => ['@type' => 'Organization', 'name' => 'فرزین'],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
@endpush

@section('content')
    <div class="fz-container">
        <nav class="fz-breadcrumb" aria-label="مسیر صفحه">
            <a href="{{ route('home') }}">خانه</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('blog.index') }}">مجله فرزین</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page">{{ $post->title }}</span>
        </nav>

        <article class="fz-blog-article">
            <header class="fz-blog-article__head">
                <div class="fz-blog-card__meta">
                    @if ($post->category)
                        <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}">{{ $post->category->name }}</a>
                    @endif
                    <time datetime="{{ $post->published_at->toIso8601String() }}">{{ $post->published_at->locale('fa')->translatedFormat('j F Y') }}</time>
                    <span>{{ number_format((int) $post->view_count) }} بازدید</span>
                </div>
                <h1 class="fz-h2">{{ $post->title }}</h1>
                @if ($post->excerpt)
                    <p class="fz-blog-article__lead">{{ $post->excerpt }}</p>
                @endif
            </header>

            @if ($post->featured_image)
                <img class="fz-blog-article__cover" src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" fetchpriority="high" decoding="async">
            @endif

            <div class="fz-blog-article__content">
                {!! nl2br(e($post->content)) !!}
            </div>

            <footer class="fz-blog-article__footer">
                <a class="fz-btn light" href="{{ route('blog.index') }}">بازگشت به مجله</a>
                <a class="fz-btn" href="{{ route('shop.index') }}">مشاهده محصولات فرزین</a>
            </footer>
        </article>

        @if ($relatedPosts->isNotEmpty())
            <section class="fz-section" aria-labelledby="related-articles-heading">
                <div class="fz-head">
                    <div>
                        <span class="fz-kicker">برای مطالعه بیشتر</span>
                        <h2 class="fz-h2" id="related-articles-heading">مقاله‌های مرتبط</h2>
                    </div>
                </div>
                <div class="fz-blog-grid">
                    @foreach ($relatedPosts as $relatedPost)
                        <article class="fz-blog-card" data-blog-card data-reveal-item>
                            <a class="fz-blog-card__image" href="{{ route('blog.show', $relatedPost->slug) }}">
                                @if ($relatedPost->featured_image)
                                    <img src="{{ asset('storage/' . $relatedPost->featured_image) }}" alt="" loading="lazy" decoding="async">
                                @else
                                    <div class="fz-blog-placeholder" aria-hidden="true">FARZIN / JOURNAL</div>
                                @endif
                            </a>
                            <div class="fz-blog-card__body">
                                <div class="fz-blog-card__meta">
                                    @if ($relatedPost->category)<span>{{ $relatedPost->category->name }}</span>@endif
                                    <time datetime="{{ $relatedPost->published_at->toIso8601String() }}">{{ $relatedPost->published_at->locale('fa')->translatedFormat('j F Y') }}</time>
                                </div>
                                <h2><a href="{{ route('blog.show', $relatedPost->slug) }}">{{ $relatedPost->title }}</a></h2>
                                <p>{{ \Illuminate\Support\Str::limit(strip_tags($relatedPost->excerpt ?: $relatedPost->content), 130) }}</p>
                                <a class="fz-blog-card__link" href="{{ route('blog.show', $relatedPost->slug) }}">ادامه مطلب <span aria-hidden="true">←</span></a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
