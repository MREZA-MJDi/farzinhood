@extends('layouts.app')

@section('title', 'مجله Farzin | راهنما، مقایسه و نکات خرید')

@section(
    'meta_description',
    'مجله Farzin؛ راهنماهای خرید، مقایسه محصولات و مطالب کاربردی برای انتخاب بهتر.'
)

@section('content')

    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

        {{-- =========================================================
            HERO
        ========================================================== --}}
        <div class="relative overflow-hidden rounded-[2.5rem] bg-[var(--color-brand-950)] px-7 py-12 text-white shadow-[0_24px_70px_rgba(13,27,61,0.16)] sm:px-10 lg:px-14 lg:py-16">

            {{-- Decorative elements --}}
            <div class="absolute -right-24 -top-28 h-80 w-80 rounded-full bg-[var(--color-brand-900)] opacity-80 blur-3xl"></div>

            <div class="absolute -bottom-32 left-1/4 h-80 w-80 rounded-full bg-[var(--color-accent-600)] opacity-10 blur-3xl"></div>

            <div
                class="absolute inset-0 opacity-[0.045]"
                style="
                    background-image:
                        linear-gradient(var(--color-neutral-0) 1px, transparent 1px),
                        linear-gradient(90deg, var(--color-neutral-0) 1px, transparent 1px);
                    background-size: 42px 42px;
                "
            ></div>

            <div class="relative max-w-3xl">

                <div class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-[0.25em] text-white/45">
                    <span class="h-1.5 w-1.5 rounded-full bg-[var(--color-accent-500)]"></span>
                    Farzin Journal
                </div>

                <h1 class="mt-5 text-4xl font-black leading-[1.25] tracking-tight sm:text-5xl lg:text-6xl">
                    قبل از خرید،
                    <span class="text-[var(--color-accent-400)]">
                        هوشمندانه انتخاب کن.
                    </span>
                </h1>

                <p class="mt-5 max-w-2xl text-sm leading-8 text-white/60 sm:text-base">
                    راهنماها، مقایسه‌ها و مطالب کاربردی که کمک می‌کنند
                    محصول مناسب را سریع‌تر، آگاهانه‌تر و مطمئن‌تر پیدا کنی.
                </p>

                {{-- Hero meta --}}
                <div class="mt-8 flex flex-wrap items-center gap-3 text-xs">

                    <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3.5 py-2 font-bold text-white/65 backdrop-blur-sm">
                        <svg
                            class="h-4 w-4 text-[var(--color-accent-400)]"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 6v6l4 2"
                            />
                            <circle
                                cx="12"
                                cy="12"
                                r="9"
                            />
                        </svg>

                        مطالب کاربردی
                    </div>

                    <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3.5 py-2 font-bold text-white/65 backdrop-blur-sm">
                        <svg
                            class="h-4 w-4 text-[var(--color-accent-400)]"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 4h14v16H5z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M8 8h8M8 12h8M8 16h5"
                            />
                        </svg>

                        راهنما و مقایسه
                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
            SEARCH + CATEGORY
        ========================================================== --}}
        <div class="mt-8 grid gap-4 lg:grid-cols-[1fr_auto]">

            {{-- Search --}}
            <form
                action="{{ route('blog.index') }}"
                method="GET"
                class="rounded-[1.5rem] border border-[var(--color-border)] bg-white p-3 shadow-sm"
            >

                @if(request('category'))
                    <input
                        type="hidden"
                        name="category"
                        value="{{ request('category') }}"
                    >
                @endif

                <div class="flex gap-3">

                    <div class="relative min-w-0 flex-1">

                        <svg
                            class="pointer-events-none absolute right-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[var(--color-text-muted)]"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                        >
                            <circle
                                cx="11"
                                cy="11"
                                r="7"
                            />
                            <path
                                stroke-linecap="round"
                                d="m20 20-4-4"
                            />
                        </svg>

                        <input
                            type="search"
                            name="search"
                            value="{{ $search ?? request('search') }}"
                            placeholder="جستجو در مطالب..."
                            class="w-full rounded-xl bg-[var(--color-neutral-50)] py-3.5 pl-5 pr-12 text-sm text-[var(--color-text-primary)] outline-none transition placeholder:text-[var(--color-text-muted)] hover:bg-[var(--color-neutral-100)] focus:bg-white focus:ring-4 focus:ring-[var(--color-brand-100)]"
                        >

                    </div>

                    <button
                        type="submit"
                        class="shrink-0 rounded-xl bg-[var(--color-brand-900)] px-5 py-3.5 text-sm font-black text-white transition hover:bg-[var(--color-brand-950)] focus:outline-none focus:ring-4 focus:ring-[var(--color-brand-100)]"
                    >
                        جستجو
                    </button>

                </div>

            </form>


            {{-- Category --}}
            <div class="overflow-x-auto rounded-[1.5rem] border border-[var(--color-border)] bg-white p-2 shadow-sm">

                <div class="flex min-w-max items-center gap-2">

                    <a
                        href="{{ route('blog.index') }}"
                        class="rounded-xl px-4 py-3 text-xs font-black transition
                        {{ empty($category)
                            ? 'bg-[var(--color-brand-50)] text-[var(--color-brand-800)]'
                            : 'text-[var(--color-text-muted)] hover:bg-[var(--color-neutral-50)] hover:text-[var(--color-text-primary)]' }}"
                    >
                        همه مطالب
                    </a>

                    @foreach($categories as $blogCategory)

                        <a
                            href="{{ route('blog.index', ['category' => $blogCategory->slug]) }}"
                            class="rounded-xl px-4 py-3 text-xs font-black transition
                            {{ ($category ?? '') === $blogCategory->slug
                                ? 'bg-[var(--color-brand-50)] text-[var(--color-brand-800)]'
                                : 'text-[var(--color-text-muted)] hover:bg-[var(--color-neutral-50)] hover:text-[var(--color-text-primary)]' }}"
                        >
                            {{ $blogCategory->name }}
                        </a>

                    @endforeach

                </div>

            </div>

        </div>


        {{-- =========================================================
            RESULT HEADER
        ========================================================== --}}
        <div class="mt-12 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">

            <div>

                <div class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-[0.2em] text-[var(--color-accent-600)]">

                    <span class="h-1.5 w-1.5 rounded-full bg-[var(--color-accent-600)]"></span>

                    Articles

                </div>

                <h2 class="mt-3 text-2xl font-black tracking-tight text-[var(--color-text-primary)] sm:text-3xl">
                    آخرین مطالب
                </h2>

            </div>

            <div class="rounded-full bg-[var(--color-neutral-100)] px-3 py-1.5 text-xs font-bold text-[var(--color-text-muted)]">
                {{ $posts->total() }} مطلب
            </div>

        </div>


        {{-- =========================================================
            POSTS
        ========================================================== --}}
        @if($posts->isNotEmpty())

            <div class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-3">

                @foreach($posts as $post)

                    <article
                        class="group overflow-hidden rounded-[2rem] border border-[var(--color-border)] bg-white transition duration-500 hover:-translate-y-1 hover:border-[var(--color-border-strong)] hover:shadow-[0_20px_50px_rgba(16,23,34,0.08)]"
                    >

                        {{-- Image --}}
                        <a
                            href="{{ route('blog.show', $post) }}"
                            class="block"
                        >

                            <div class="relative aspect-[1.45] overflow-hidden bg-[var(--color-neutral-100)]">

                                @if($post->featured_image)

                                    <img
                                        src="{{ asset('storage/' . $post->featured_image) }}"
                                        alt="{{ $post->title }}"
                                        class="h-full w-full object-cover transition duration-700 ease-out group-hover:scale-105"
                                        loading="lazy"
                                    >

                                @else

                                    <div class="flex h-full items-center justify-center bg-gradient-to-br from-[var(--color-neutral-100)] to-[var(--color-brand-100)]">

                                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl border border-[var(--color-brand-200)] bg-white/70 text-[var(--color-brand-700)] shadow-sm">

                                            <svg
                                                class="h-7 w-7"
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


                                {{-- Category --}}
                                @if($post->category)

                                    <div class="absolute right-4 top-4 rounded-full border border-white/60 bg-white/95 px-3 py-1.5 text-[10px] font-black text-[var(--color-brand-800)] shadow-sm backdrop-blur">
                                        {{ $post->category->name }}
                                    </div>

                                @endif

                            </div>

                        </a>


                        {{-- Content --}}
                        <div class="p-6">

                            <div class="flex items-center gap-3 text-[11px] font-medium text-[var(--color-text-muted)]">

                                @if($post->published_at)

                                    <span>
                                        {{ $post->published_at->format('Y/m/d') }}
                                    </span>

                                @endif

                                @if($post->view_count > 0)

                                    <span class="h-1 w-1 rounded-full bg-[var(--color-border-strong)]"></span>

                                    <span>
                                        {{ number_format($post->view_count) }} بازدید
                                    </span>

                                @endif

                            </div>


                            <h3 class="mt-4 line-clamp-2 text-xl font-black leading-8 text-[var(--color-text-primary)] transition group-hover:text-[var(--color-brand-800)]">

                                <a href="{{ route('blog.show', $post) }}">
                                    {{ $post->title }}
                                </a>

                            </h3>


                            @if($post->excerpt)

                                <p class="mt-3 line-clamp-3 text-sm leading-7 text-[var(--color-text-secondary)]">
                                    {{ $post->excerpt }}
                                </p>

                            @endif


                            <a
                                href="{{ route('blog.show', $post) }}"
                                class="mt-6 inline-flex items-center gap-2 text-xs font-black text-[var(--color-accent-600)] transition hover:text-[var(--color-accent-700)]"
                            >

                                ادامه مطلب

                                <span class="transition-transform duration-300 group-hover:-translate-x-1">
                                    ←
                                </span>

                            </a>

                        </div>

                    </article>

                @endforeach

            </div>


            {{-- Pagination --}}
            <div class="mt-12">
                {{ $posts->onEachSide(1)->links() }}
            </div>


        @else

            {{-- =====================================================
                EMPTY STATE
            ====================================================== --}}
            <div class="mt-8 rounded-[2rem] border border-dashed border-[var(--color-border-strong)] bg-white px-6 py-24 text-center">

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[var(--color-brand-50)] text-[var(--color-brand-700)]">

                    <svg
                        class="h-7 w-7"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.5"
                    >
                        <path d="M4 5h16v14H4z"/>
                        <path d="M8 9h8M8 13h5"/>
                    </svg>

                </div>

                <h2 class="mt-5 text-xl font-black text-[var(--color-text-primary)]">
                    مطلبی پیدا نشد
                </h2>

                <p class="mx-auto mt-2 max-w-md text-sm leading-7 text-[var(--color-text-secondary)]">
                    عبارت جستجو یا دسته‌بندی انتخابی را تغییر بده و دوباره امتحان کن.
                </p>

                <a
                    href="{{ route('blog.index') }}"
                    class="mt-6 inline-flex rounded-xl bg-[var(--color-brand-900)] px-5 py-3.5 text-sm font-black text-white transition hover:bg-[var(--color-brand-950)] focus:outline-none focus:ring-4 focus:ring-[var(--color-brand-100)]"
                >
                    نمایش همه مطالب
                </a>

            </div>

        @endif

    </section>

@endsection
