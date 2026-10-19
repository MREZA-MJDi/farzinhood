@extends('layouts.app')
@section('title', $post->seo_title)
@section('meta_description', $post->seo_description ?: 'مطالعه‌ی مقاله در مجله فرزین.')
@section('canonical', $post->canonical_url ?: url()->current())
@push('meta')
    @if($post->noindex)<meta name="robots" content="noindex, follow">@endif
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $post->seo_title }}">
    @if($post->seo_description)<meta property="og:description" content="{{ $post->seo_description }}">@endif
@endpush
@section('content')
<article class="farzin-blog-article">
    <div class="farzin-container farzin-blog-article__container">
        <nav class="farzin-breadcrumb" aria-label="مسیر صفحه"><a href="{{ route('home') }}">خانه</a><span>/</span><a href="{{ route('blog.index') }}">مجله</a><span>/</span><span aria-current="page">{{ $post->title }}</span></nav>
        <header class="farzin-blog-article__head">
            <p class="farzin-kicker">FARZIN / JOURNAL @if($post->category) / {{ $post->category->name }} @endif</p>
            <h1>{{ $post->title }}</h1>
            @if($post->excerpt)<p class="farzin-blog-article__lead">{{ $post->excerpt }}</p>@endif
            <div class="farzin-blog-card__meta"><span>{{ $post->published_at?->format('Y/m/d') }}</span><span>{{ number_format($post->view_count) }} بازدید</span></div>
        </header>
        @if($post->featured_image)
            @php $image=$post->featured_image; $imageUrl=str_starts_with($image,'http://') || str_starts_with($image,'https://') || str_starts_with($image,'/') ? $image : asset('storage/'.ltrim($image,'/')); @endphp
            <figure class="farzin-blog-article__cover"><img src="{{ $imageUrl }}" alt="{{ $post->title }}" width="1400" height="850" fetchpriority="high"></figure>
        @endif
        <div class="farzin-blog-article__body">{{ $post->content }}</div>
        <footer class="farzin-blog-article__footer"><a class="farzin-text-link" href="{{ route('blog.index') }}">بازگشت به مجله <span aria-hidden="true">↗</span></a></footer>
    </div>
</article>
@if($relatedPosts->isNotEmpty())
<section class="farzin-section farzin-section--pearl"><div class="farzin-container"><header class="farzin-section__head"><div><p class="farzin-kicker">KEEP DISCOVERING</p><h2 class="farzin-section__title">مطالب <em>مرتبط</em></h2></div><a class="farzin-text-link" href="{{ route('blog.index') }}">همه مقاله‌ها <span>↗</span></a></header>
<div class="farzin-blog-grid">@foreach($relatedPosts as $related)
<article class="farzin-blog-card"><a class="farzin-blog-card__media" href="{{ route('blog.show',$related->slug) }}">@if($related->featured_image)@php $img=$related->featured_image; $imgUrl=str_starts_with($img,'http://') || str_starts_with($img,'https://') || str_starts_with($img,'/') ? $img : asset('storage/'.ltrim($img,'/')); @endphp<img src="{{ $imgUrl }}" alt="{{ $related->title }}" width="960" height="600" loading="lazy">@else<span>F.</span>@endif</a><div class="farzin-blog-card__content"><h2><a href="{{ route('blog.show',$related->slug) }}">{{ $related->title }}</a></h2>@if($related->excerpt)<p>{{ $related->excerpt }}</p>@endif<a class="farzin-text-link" href="{{ route('blog.show',$related->slug) }}">ادامه‌ی مطلب <span>↗</span></a></div></article>
@endforeach</div></div></section>
@endif
@endsection
