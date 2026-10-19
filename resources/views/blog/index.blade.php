@extends('layouts.storefront')

@section('title', 'مجله فرزین | راهنمای انتخاب تجهیزات آشپزخانه')
@section('meta_description', 'راهنماها و مقاله‌های منتشرشده فرزین درباره انتخاب، نگهداری و استفاده از تجهیزات آشپزخانه.')

@section('content')
    <section class="fz-blog-hero">
        <div class="fz-container">
            <nav class="fz-breadcrumb" aria-label="مسیر صفحه">
                <a href="{{ route('home') }}">خانه</a>
                <span aria-hidden="true">/</span>
                <span aria-current="page">مجله فرزین</span>
            </nav>
            <div class="fz-blog-heading">
                <span class="fz-kicker">دانش کاربردی، انتخاب بهتر</span>
                <h1 class="fz-h2">مجله فرزین</h1>
                <p class="fz-desc">راهنماهای منتشرشده برای مقایسه آگاهانه، نگهداری بهتر و انتخاب متناسب با نیاز خانه.</p>
            </div>

            <form method="GET" action="{{ route('blog.index') }}" class="fz-filterbar fz-blog-filters" role="search" aria-label="جست‌وجو در مجله">
                <label class="fz-visually-hidden" for="blog-search">جست‌وجو در مقاله‌ها</label>
                <input class="fz-input" id="blog-search" type="search" name="search" value="{{ $search }}" placeholder="عنوان یا موضوع مقاله">
                <label class="fz-visually-hidden" for="blog-category">دسته‌بندی مقاله‌ها</label>
                <select class="fz-select" id="blog-category" name="category">
                    <option value="">همه دسته‌ها</option>
                    @foreach ($categories as $blogCategory)
                        <option value="{{ $blogCategory->slug }}" @selected($category === $blogCategory->slug)>{{ $blogCategory->name }}</option>
                    @endforeach
                </select>
                <button class="fz-btn" type="submit">جست‌وجو</button>
                @if ($search !== '' || $category !== '')
                    <a class="fz-btn light" href="{{ route('blog.index') }}">پاک‌کردن فیلتر</a>
                @endif
            </form>
        </div>
    </section>

    <section class="fz-section">
        <div class="fz-container">
            <div class="fz-head">
                <div>
                    <span class="fz-kicker">محتوای منتشرشده</span>
                    <h2 class="fz-h2">راهنماها و مقاله‌ها</h2>
                    <p class="fz-desc">{{ number_format($posts->total()) }} مقاله برای نمایش پیدا شد.</p>
                </div>
            </div>

            @if ($posts->isNotEmpty())
                <div class="fz-blog-grid">
                    @foreach ($posts as $post)
                        <article class="fz-blog-card" data-blog-card data-reveal-item>
                            <a class="fz-blog-card__image" href="{{ route('blog.show', $post->slug) }}" aria-label="خواندن {{ $post->title }}">
                                @if ($post->featured_image)
                                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="" loading="lazy" decoding="async" sizes="(max-width: 520px) 90vw, (max-width: 1050px) 44vw, 30vw">
                                @else
                                    <div class="fz-blog-placeholder" aria-hidden="true">FARZIN / JOURNAL</div>
                                @endif
                            </a>
                            <div class="fz-blog-card__body">
                                <div class="fz-blog-card__meta">
                                    @if ($post->category)
                                        <span>{{ $post->category->name }}</span>
                                    @endif
                                    <time datetime="{{ $post->published_at->toIso8601String() }}">{{ $post->published_at->locale('fa')->translatedFormat('j F Y') }}</time>
                                    <span>{{ number_format((int) $post->view_count) }} بازدید</span>
                                </div>
                                <h2><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h2>
                                @if ($post->excerpt)
                                    <p>{{ \Illuminate\Support\Str::limit(strip_tags($post->excerpt), 170) }}</p>
                                @else
                                    <p>{{ \Illuminate\Support\Str::limit(strip_tags($post->content), 170) }}</p>
                                @endif
                                <a class="fz-blog-card__link" href="{{ route('blog.show', $post->slug) }}">ادامه مطلب <span aria-hidden="true">←</span></a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="fz-pagination">{{ $posts->links() }}</div>
            @else
                <div class="fz-empty" role="status">
                    <strong>مقاله‌ای با این شرایط پیدا نشد.</strong>
                    <p class="mt-2">فیلترها را تغییر بده یا مقاله‌های دیگر را بعداً بررسی کن.</p>
                </div>
            @endif
        </div>
    </section>
@endsection
