@extends('layouts.app')

@section('title', $post->meta_title ?: $post->title . ' | مجله فرزین')
@section('meta_description', $post->meta_description ?: ($post->excerpt ?: $post->title))

@section('content')
<article class="fh-page fh-article">
    <header class="fh-page__hero">
        <p class="fh-eyebrow fh-eyebrow--dark">FARZIN / JOURNAL</p>
        <nav class="fh-breadcrumb" aria-label="مسیر صفحه"><a href="{{ route('home') }}">خانه</a><span>/</span><a href="{{ route('blog.index') }}">مجله</a></nav>
        <h1>{{ $post->title }}</h1>
        <p>{{ $post->excerpt }}</p>
        <small>{{ $post->category?->name }} @if($post->published_at) · {{ $post->published_at->format('Y/m/d') }} @endif · {{ number_format((int) $post->view_count) }} بازدید</small>
    </header>
    <div class="fh-article__body">
        @if ($post->cover_image)<img class="fh-article__cover" src="{{ asset('storage/' . ltrim($post->cover_image, '/')) }}" alt="{{ $post->title }}">@endif
        <div class="fh-article__content">{{ $post->content }}</div>
    </div>
    @if ($relatedPosts->isNotEmpty())
        <section class="fh-page__inner"><div class="fh-section__heading"><div><p class="fh-eyebrow fh-eyebrow--dark">KEEP READING</p><h2>مطالب مرتبط</h2></div></div><div class="fh-blog-grid">
            @foreach ($relatedPosts as $related)
                <article class="fh-blog-card"><p class="fh-blog-card__meta">{{ $related->category?->name }}</p><h3><a href="{{ route('blog.show', ['post' => $related->slug]) }}">{{ $related->title }}</a></h3><p>{{ $related->excerpt }}</p></article>
            @endforeach
        </div></section>
    @endif
</article>
@endsection
