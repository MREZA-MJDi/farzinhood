@extends('layouts.app')

@section('title', $post->meta_title ?: $post->title . ' | Farzin')

@section(
    'meta_description',
    $post->meta_description
        ?: ($post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 155))
)

@section('content')

    <article>

        {{-- =========================================================
            ARTICLE HEADER
        ========================================================== --}}
        <section class="border-b border-[var(--color-border)] bg-white">

            <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">

                {{-- Breadcrumb --}}
                <nav
                    aria-label="Breadcrumb"
                    class="flex flex-wrap items-center gap-2 text-xs text-[var(--color-text-muted)]"
                >

                    <a
                        href="{{ route('home') }}"
                        class="transition hover:text-[var(--color-accent-600)]"
                    >
                        خانه
                    </a>

                    <span class="text-[var(--color-border-strong)]">
                        /
                    </span>

                    <a
                        href="{{ route('blog.index') }}"
                        class="transition hover:text-[var(--color-accent-600)]"
                    >
                        مجله
                    </a>

                    @if($post->category)

                        <span class="text-[var(--color-border-strong)]">
                            /
                        </span>

                        <a
                            href="{{ route('blog.index', ['category' => $post->category->slug]) }}"
                            class="transition hover:text-[var(--color-accent-600)]"
                        >
                            {{ $post->category->name }}
                        </a>

                    @endif

                    <span class="text-[var(--color-border-strong)]">
                        /
                    </span>

                    <span class="font-bold text-[var(--color-text-primary)]">
                        مقاله
                    </span>

                </nav>


                {{-- Meta --}}
                <div class="mt-10 flex flex-wrap items-center gap-3">

                    @if($post->category)

                        <a
                            href="{{ route('blog.index', ['category' => $post->category->slug]) }}"
                            class="rounded-full bg-[var(--color-accent-50)] px-4 py-2 text-[10px] font-black text-[var(--color-accent-700)] transition hover:bg-[var(--color-accent-100)]"
                        >
                            {{ $post->category->name }}
                        </a>

                    @endif

                    @if($post->published_at)

                        <span class="text-xs font-medium text-[var(--color-text-muted)]">
                            {{ $post->published_at->format('Y/m/d') }}
                        </span>

                    @endif

                    @if($post->view_count > 0)

                        <span class="h-1 w-1 rounded-full bg-[var(--color-border-strong)]"></span>

                        <span class="text-xs font-medium text-[var(--color-text-muted)]">
                            {{ number_format($post->view_count) }} بازدید
                        </span>

                    @endif

                </div>


                {{-- Title --}}
                <h1 class="mt-6 max-w-4xl text-4xl font-black leading-[1.25] tracking-tight text-[var(--color-brand-950)] sm:text-5xl lg:text-6xl">
                    {{ $post->title }}
                </h1>


                {{-- Excerpt --}}
                @if($post->excerpt)

                    <p class="mt-6 max-w-3xl text-base leading-9 text-[var(--color-text-secondary)] sm:text-lg">
                        {{ $post->excerpt }}
                    </p>

                @endif

            </div>

        </section>


        {{-- =========================================================
            FEATURED IMAGE
        ========================================================== --}}
        @if($post->featured_image)

            <section class="mx-auto max-w-6xl px-4 pt-10 sm:px-6 lg:px-8">

                <div class="overflow-hidden rounded-[2.5rem] border border-[var(--color-border)] bg-[var(--color-neutral-100)] shadow-[0_20px_55px_rgba(16,23,34,0.07)]">

                    <img
                        src="{{ asset('storage/' . $post->featured_image) }}"
                        alt="{{ $post->title }}"
                        class="max-h-[680px] w-full object-cover"
                    >

                </div>

            </section>

        @endif


        {{-- =========================================================
            BODY
        ========================================================== --}}
        <section class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">

            <div class="grid gap-10 lg:grid-cols-[minmax(0,1fr)_260px]">

                {{-- Article Content --}}
                <div class="min-w-0">

                    <div class="prose-farzin rounded-[2rem] border border-[var(--color-border)] bg-white p-6 shadow-sm sm:p-10 lg:p-14">

                        {!! $post->content !!}

                    </div>


                    {{-- Back --}}
                    <div class="mt-8">

                        <a
                            href="{{ route('blog.index') }}"
                            class="group inline-flex items-center gap-2 rounded-xl border border-[var(--color-border)] bg-white px-5 py-3.5 text-xs font-black text-[var(--color-text-secondary)] transition hover:border-[var(--color-brand-300)] hover:text-[var(--color-brand-800)] hover:shadow-sm"
                        >

                            <span class="transition-transform duration-200 group-hover:-translate-x-1">
                                ←
                            </span>

                            بازگشت به مجله

                        </a>

                    </div>

                </div>


                {{-- Sidebar --}}
                <aside class="lg:sticky lg:top-28 lg:self-start">

                    <div class="space-y-4">

                        {{-- Article Info --}}
                        <div class="rounded-[2rem] border border-[var(--color-border)] bg-white p-5 shadow-sm">

                            <div class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-[0.2em] text-[var(--color-accent-600)]">

                                <span class="h-1.5 w-1.5 rounded-full bg-[var(--color-accent-600)]"></span>

                                Article

                            </div>

                            <div class="mt-5 space-y-5">

                                @if($post->category)

                                    <div>

                                        <div class="text-[10px] font-bold text-[var(--color-text-muted)]">
                                            دسته‌بندی
                                        </div>

                                        <a
                                            href="{{ route('blog.index', ['category' => $post->category->slug]) }}"
                                            class="mt-1 block text-sm font-black text-[var(--color-text-primary)] transition hover:text-[var(--color-accent-600)]"
                                        >
                                            {{ $post->category->name }}
                                        </a>

                                    </div>

                                @endif

                                @if($post->published_at)

                                    <div>

                                        <div class="text-[10px] font-bold text-[var(--color-text-muted)]">
                                            تاریخ انتشار
                                        </div>

                                        <div class="mt-1 text-sm font-black text-[var(--color-text-primary)]">
                                            {{ $post->published_at->format('Y/m/d') }}
                                        </div>

                                    </div>

                                @endif

                                <div>

                                    <div class="text-[10px] font-bold text-[var(--color-text-muted)]">
                                        بازدید
                                    </div>

                                    <div class="mt-1 text-sm font-black text-[var(--color-text-primary)]">
                                        {{ number_format($post->view_count) }}
                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- CTA --}}
                        <div class="relative overflow-hidden rounded-[2rem] bg-[var(--color-brand-950)] p-6 text-white">

                            <div class="absolute -bottom-10 -left-10 h-32 w-32 rounded-full bg-[var(--color-accent-600)] opacity-10 blur-2xl"></div>

                            <div class="relative">

                                <div class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-[0.2em] text-white/45">

                                    <span class="h-1.5 w-1.5 rounded-full bg-[var(--color-accent-500)]"></span>

                                    Keep Exploring

                                </div>

                                <h3 class="mt-3 text-xl font-black leading-8">
                                    مطالب بیشتری برای کشف کردن هست.
                                </h3>

                                <p class="mt-2 text-xs leading-6 text-white/55">
                                    راهنماها و مقالات بیشتری را برای انتخاب بهتر ببین.
                                </p>

                                <a
                                    href="{{ route('blog.index') }}"
                                    class="mt-5 inline-flex items-center gap-2 rounded-xl bg-[var(--color-accent-600)] px-4 py-3 text-xs font-black text-white transition hover:bg-[var(--color-accent-700)] focus:outline-none focus:ring-4 focus:ring-[var(--color-accent-100)]"
                                >
                                    رفتن به مجله

                                    <span>
                                        ←
                                    </span>

                                </a>

                            </div>

                        </div>

                    </div>

                </aside>

            </div>

        </section>


        {{-- =========================================================
            RELATED POSTS
        ========================================================== --}}
        @if($relatedPosts->isNotEmpty())

            <section class="border-t border-[var(--color-border)] bg-white py-16">

                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                    <div>

                        <div class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-[0.2em] text-[var(--color-accent-600)]">

                            <span class="h-1.5 w-1.5 rounded-full bg-[var(--color-accent-600)]"></span>

                            More to read

                        </div>

                        <h2 class="mt-3 text-2xl font-black tracking-tight text-[var(--color-brand-950)] sm:text-3xl">
                            مطالب مرتبط
                        </h2>

                    </div>


                    <div class="mt-8 grid gap-6 md:grid-cols-3">

                        @foreach($relatedPosts as $relatedPost)

                            <article
                                class="group overflow-hidden rounded-[2rem] border border-[var(--color-border)] bg-white transition duration-500 hover:-translate-y-1 hover:border-[var(--color-border-strong)] hover:shadow-[0_20px_50px_rgba(16,23,34,0.08)]"
                            >

                                <a
                                    href="{{ route('blog.show', $relatedPost) }}"
                                    class="block"
                                >

                                    <div class="aspect-[1.5] overflow-hidden bg-[var(--color-neutral-100)]">

                                        @if($relatedPost->featured_image)

                                            <img
                                                src="{{ asset('storage/' . $relatedPost->featured_image) }}"
                                                alt="{{ $relatedPost->title }}"
                                                class="h-full w-full object-cover transition duration-700 ease-out group-hover:scale-105"
                                                loading="lazy"
                                            >

                                        @else

                                            <div class="flex h-full items-center justify-center bg-gradient-to-br from-[var(--color-neutral-100)] to-[var(--color-brand-100)]">

                                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-[var(--color-brand-200)] bg-white/70 text-[var(--color-brand-700)] shadow-sm">

                                                    <svg
                                                        class="h-6 w-6"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="1.5"
                                                    >
                                                        <rect
                                                            x="3"
                                                            y="4"
                                                            width="18"
                                                            height="16"
                                                            rx="2"
                                                        />

                                                        <path d="m4 16 5-5 3 3 2-2 6 6"/>

                                                        <circle
                                                            cx="8.5"
                                                            cy="8.5"
                                                            r="1.5"
                                                        />
                                                    </svg>

                                                </div>

                                            </div>

                                        @endif

                                    </div>


                                    <div class="p-5">

                                        <div class="text-[10px] font-bold text-[var(--color-text-muted)]">
                                            {{ $relatedPost->published_at?->format('Y/m/d') }}
                                        </div>

                                        <h3 class="mt-2 line-clamp-2 text-base font-black leading-7 text-[var(--color-text-primary)] transition group-hover:text-[var(--color-brand-800)]">
                                            {{ $relatedPost->title }}
                                        </h3>

                                        @if($relatedPost->excerpt)

                                            <p class="mt-2 line-clamp-2 text-xs leading-6 text-[var(--color-text-secondary)]">
                                                {{ $relatedPost->excerpt }}
                                            </p>

                                        @endif

                                        <div class="mt-4 inline-flex items-center gap-2 text-[11px] font-black text-[var(--color-accent-600)]">

                                            مطالعه مقاله

                                            <span class="transition-transform duration-200 group-hover:-translate-x-1">
                                                ←
                                            </span>

                                        </div>

                                    </div>

                                </a>

                            </article>

                        @endforeach

                    </div>

                </div>

            </section>

        @endif

    </article>

@endsection
