@extends('layouts.app')
@php
    $selectedCategory = $categories->firstWhere('slug', $category);
@endphp
@section('title', ($selectedCategory?->meta_title ?: 'مجله فرزین | راهنمای انتخاب بهتر'))
@section('meta_description', $selectedCategory?->meta_description ?: 'راهنمای خرید، نکات کاربردی و ایده‌هایی برای انتخاب بهتر محصولات خانه و آشپزخانه.')
@section('canonical', $selectedCategory?->canonical_url ?: url()->current())
@push('meta')
    @if($selectedCategory?->noindex || request()->filled('search'))<meta name="robots" content="noindex, follow">@endif
@endpush
@section('content')
<section class="farzin-catalog farzin-blog">
    <div class="farzin-container">
        <header class="farzin-page-head">
            <p class="farzin-kicker">FARZIN / JOURNAL</p>
            <h1>مجله <em>فرزین</em></h1>
            <p>{{ $selectedCategory?->description ?: 'دانستنی‌های کاربردی برای انتخاب آگاهانه و تجربه‌ی بهتر در خانه.' }}</p>
        </header>
        <form class="farzin-blog-filters" action="{{ route('blog.index') }}" method="GET">
            <label class="farzin-field farzin-field--search"><span>جستجو در مجله</span><input type="search" name="search" value="{{ $search }}" placeholder="عنوان یا موضوع مقاله..." maxlength="255"></label>
            <label class="farzin-field"><span>موضوع</span><select name="category"><option value="">همه موضوع‌ها</option>@foreach($categories as $item)<option value="{{ $item->slug }}" @selected($category === $item->slug)>{{ $item->name }}</option>@endforeach</select></label>
            <div class="farzin-filters__actions"><button class="farzin-button" type="submit">جستجو <span aria-hidden="true">↗</span></button><a class="farzin-reset" href="{{ route('blog.index') }}">پاک کردن</a></div>
        </form>
        <div class="farzin-catalog__result"><span>{{ \App\Support\JalaliCalendar::number($posts->total()) }} مقاله</span>@if($selectedCategory)<span>{{ $selectedCategory->name }}</span>@endif</div>
        @if($posts->isNotEmpty())
            <div class="farzin-blog-grid">
                @foreach($posts as $post)
                    @php
                        $image = $post->featured_image;
                        $imageUrl = filled($image) ? (str_starts_with($image,'http://') || str_starts_with($image,'https://') || str_starts_with($image,'/') ? $image : asset('storage/'.ltrim($image,'/'))) : null;
                    @endphp
                    <article class="farzin-blog-card">
                        <a class="farzin-blog-card__media" href="{{ route('blog.show', $post->slug) }}">
                            @if($imageUrl)<img src="{{ $imageUrl }}" alt="{{ $post->title }}" width="960" height="600" loading="lazy" decoding="async">@else<span aria-hidden="true">F.</span>@endif
                        </a>
                        <div class="farzin-blog-card__content">
                            <div class="farzin-blog-card__meta">@if($post->category)<span>{{ $post->category->name }}</span>@endif<span>{{ ($post->published_at ? \App\Support\JalaliCalendar::format($post->published_at, 'date') : '—') }}</span></div>
                            <h2><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h2>
                            @if($post->excerpt)<p>{{ $post->excerpt }}</p>@endif
                            <a class="farzin-text-link" href="{{ route('blog.show', $post->slug) }}">ادامه‌ی مطلب <span aria-hidden="true">↗</span></a>
                        </div>
                    </article>
                @endforeach
            </div>
            <nav class="farzin-pagination" aria-label="صفحه‌بندی">{{ $posts->links() }}</nav>
        @else
            <div class="farzin-empty farzin-empty--large"><span class="farzin-empty__mark">F.</span><h2>مقاله‌ای پیدا نشد</h2><p>عبارت جستجو یا موضوع را تغییر بده.</p><a class="farzin-button" href="{{ route('blog.index') }}">مشاهده همه مقاله‌ها</a></div>
        @endif
    </div>
</section>
@endsection
