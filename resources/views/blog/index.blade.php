@extends('layouts.app')

@section('title', 'مجله فرزین | راهنمای خرید')
@section('meta_description', 'مقاله‌ها و راهنماهای کاربردی فرزین برای انتخاب هود، سینک و تجهیزات آشپزخانه.')

@section('content')
<section class="fh-page">
    <header class="fh-page__hero">
        <p class="fh-eyebrow fh-eyebrow--dark">FARZIN / JOURNAL</p>
        <h1>مجله فرزین</h1>
        <p>راهنماهای کاربردی برای انتخاب آگاهانه‌تر تجهیزات آشپزخانه.</p>
        <form class="fh-blog-search" action="{{ route('blog.index') }}" method="GET">
            <input type="search" name="search" value="{{ $search }}" placeholder="جست‌وجو در مقاله‌ها" aria-label="جست‌وجو در مقاله‌ها">
            <select name="category" aria-label="دسته‌بندی مقاله">
                <option value="">همه دسته‌بندی‌ها</option>
                @foreach ($categories as $blogCategory)
                    <option value="{{ $blogCategory->slug }}" @selected($category === $blogCategory->slug)>{{ $blogCategory->name }}</option>
                @endforeach
            </select>
            <button class="fh-button fh-button--dark" type="submit">جست‌وجو</button>
        </form>
    </header>
    <div class="fh-page__inner">
        @if ($posts->count())
            <div class="fh-blog-grid">
                @foreach ($posts as $post)
                    <article class="fh-blog-card">
                        <a class="fh-blog-card__image" href="{{ route('blog.show', ['post' => $post->slug]) }}">
                            @if ($post->cover_image)<img src="{{ asset('storage/' . ltrim($post->cover_image, '/')) }}" alt="{{ $post->title }}" loading="lazy">@else<span aria-hidden="true">F.</span>@endif
                        </a>
                        <p class="fh-blog-card__meta">{{ $post->category?->name ?: 'مجله فرزین' }} · {{ optional($post->published_at)->format('Y/m/d') }}</p>
                        <h2><a href="{{ route('blog.show', ['post' => $post->slug]) }}">{{ $post->title }}</a></h2>
                        <p>{{ $post->excerpt }}</p>
                        <a class="fh-text-link" href="{{ route('blog.show', ['post' => $post->slug]) }}">ادامه مطلب <span aria-hidden="true">↗</span></a>
                    </article>
                @endforeach
            </div>
            <div class="fh-pagination">{{ $posts->links() }}</div>
        @else
            <div class="fh-empty"><strong>مقاله‌ای پیدا نشد</strong><p>بعداً دوباره سر بزنید یا عبارت جست‌وجو را تغییر دهید.</p></div>
        @endif
    </div>
</section>
@endsection
