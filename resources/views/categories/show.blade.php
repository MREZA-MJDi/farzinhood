@extends('layouts.app')

@section('title', ($category->meta_title ?: $category->name) . ' | فرزین')

@section(
    'meta_description',
    $category->meta_description
        ?: ($category->description ?: 'محصولات ' . $category->name . ' در فرزین.')
)

@section(
    'canonical_url',
    $category->canonical_url ?: url()->current()
)

@section('content')

    <div class="bg-[var(--color-neutral-50)]">

        {{-- =========================================================
            PAGE
        ========================================================== --}}

        <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">

            {{-- =====================================================
                BREADCRUMB
            ====================================================== --}}

            <nav
                class="flex flex-wrap items-center gap-x-2 gap-y-1 text-xs text-[var(--color-text-muted)]"
                aria-label="مسیر صفحه"
            >

                <a
                    href="{{ route('home') }}"
                    class="transition hover:text-[var(--color-accent-600)]"
                >
                    خانه
                </a>

                <svg
                    class="h-3.5 w-3.5 text-[var(--color-neutral-400)]"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    aria-hidden="true"
                >
                    <path d="m9 18 6-6-6-6"/>
                </svg>

                <a
                    href="{{ route('shop.index') }}"
                    class="transition hover:text-[var(--color-accent-600)]"
                >
                    فروشگاه
                </a>

                <svg
                    class="h-3.5 w-3.5 text-[var(--color-neutral-400)]"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    aria-hidden="true"
                >
                    <path d="m9 18 6-6-6-6"/>
                </svg>

                <span class="max-w-[240px] truncate font-bold text-[var(--color-text-secondary)]">
                {{ $category->name }}
            </span>

            </nav>


            {{-- =====================================================
                CATEGORY HERO
            ====================================================== --}}

            <section class="relative mt-8 overflow-hidden rounded-[2.25rem] bg-[var(--color-brand-950)] text-white shadow-[var(--shadow-lg)]">

                {{-- Decorative --}}
                <div
                    class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full bg-[var(--color-accent-600)]/15 blur-3xl"
                ></div>

                <div
                    class="pointer-events-none absolute -bottom-24 -left-20 h-72 w-72 rounded-full bg-white/[0.04] blur-3xl"
                ></div>

                <div
                    class="pointer-events-none absolute inset-0 opacity-[0.035]"
                    style="background-image: linear-gradient(rgba(255,255,255,.8) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.8) 1px, transparent 1px); background-size: 48px 48px;"
                ></div>


                <div class="relative grid lg:grid-cols-[1fr_380px]">

                    {{-- =================================================
                        Hero Copy
                    ================================================== --}}

                    <div class="p-7 sm:p-10 lg:p-14">

                        <div
                            class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/[0.05] px-3.5 py-2 text-[10px] font-black uppercase tracking-[0.18em] text-white/65"
                        >
                            <span class="h-1.5 w-1.5 rounded-full bg-[var(--color-accent-600)]"></span>
                            Category
                        </div>


                        <h1 class="mt-5 max-w-3xl text-4xl font-black leading-[1.2] tracking-tight sm:text-5xl lg:text-6xl">
                            {{ $category->name }}
                        </h1>


                        @if($category->description)

                            <p class="mt-5 max-w-2xl text-sm leading-8 text-white/60 sm:text-base">
                                {{ $category->description }}
                            </p>

                        @else

                            <p class="mt-5 max-w-2xl text-sm leading-8 text-white/60 sm:text-base">
                                مجموعه محصولات دسته
                                <span class="font-black text-white">
                                {{ $category->name }}
                            </span>
                                را مشاهده کنید و گزینه مناسب خود را پیدا کنید.
                            </p>

                        @endif


                        {{-- Category stats --}}
                        <div class="mt-8 flex flex-wrap items-center gap-3">

                            <div class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/[0.05] px-4 py-2.5 text-xs font-black text-white">

                                <svg
                                    class="h-4 w-4 text-[var(--color-accent-400)]"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    aria-hidden="true"
                                >
                                    <path d="M3 4h2l2.4 11.2a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 2-1.6L21 7H6"/>
                                    <circle cx="10" cy="20" r="1"/>
                                    <circle cx="18" cy="20" r="1"/>
                                </svg>

                                {{ number_format($products->total()) }}
                                محصول

                            </div>


                            <a
                                href="{{ route('shop.index', ['category' => $category->slug]) }}"
                                class="inline-flex items-center gap-2 rounded-xl bg-[var(--color-accent-600)] px-4 py-2.5 text-xs font-black text-white transition duration-200 hover:bg-[var(--color-accent-700)]"
                            >
                                مشاهده در فروشگاه

                                <svg
                                    class="h-4 w-4"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    aria-hidden="true"
                                >
                                    <path d="m9 18 6-6-6-6"/>
                                </svg>
                            </a>

                        </div>

                    </div>


                    {{-- =================================================
                        Hero Image
                    ================================================== --}}

                    <div class="relative min-h-[280px] overflow-hidden border-t border-white/10 lg:border-r lg:border-t-0">

                        @if($category->image)

                            <img
                                src="{{ asset('storage/' . $category->image) }}"
                                alt="{{ $category->name }}"
                                class="absolute inset-0 h-full w-full object-cover"
                                loading="eager"
                            >

                            <div
                                class="absolute inset-0 bg-gradient-to-l from-[var(--color-brand-950)]/15 via-[var(--color-brand-950)]/25 to-[var(--color-brand-950)]/70"
                            ></div>

                        @else

                            <div class="absolute inset-0 bg-gradient-to-br from-[#25365b] to-[#101a31]">

                                <div class="absolute inset-0 flex items-center justify-center">

                                    <div class="relative">

                                        <div class="absolute inset-0 scale-125 rounded-[2.5rem] bg-[var(--color-accent-600)]/10 blur-3xl"></div>

                                        <div class="relative flex h-32 w-32 items-center justify-center rounded-[2.25rem] border border-white/10 bg-white/[0.04] backdrop-blur">

                                            <svg
                                                class="h-14 w-14 text-white/30"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.1"
                                                aria-hidden="true"
                                            >
                                                <rect x="3" y="4" width="18" height="16" rx="2"/>
                                                <circle cx="8.5" cy="9" r="1.4"/>
                                                <path d="m21 15-5-5-4.5 4.5-2.5-2.5L3 18"/>
                                            </svg>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        @endif


                        {{-- Image info --}}
                        <div class="absolute inset-x-5 bottom-5">

                            <div class="rounded-2xl border border-white/10 bg-black/20 px-4 py-3 backdrop-blur-md">

                                <p class="text-[10px] font-bold text-white/45">
                                    FARZIN COLLECTION
                                </p>

                                <p class="mt-1 text-sm font-black text-white">
                                    {{ $category->name }}
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </section>


            {{-- =====================================================
                PRODUCTS HEADER
            ====================================================== --}}

            <div class="mt-14 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">

                <div>

                    <div class="inline-flex items-center gap-2 text-[11px] font-black uppercase tracking-[0.24em] text-[var(--color-accent-600)]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[var(--color-accent-600)]"></span>
                        Products
                    </div>

                    <h2 class="mt-3 text-2xl font-black text-[var(--color-text-primary)] sm:text-3xl">
                        محصولات این دسته
                    </h2>

                    <p class="mt-2 text-sm leading-7 text-[var(--color-text-secondary)]">
                        محصولات موجود در دسته
                        <span class="font-bold text-[var(--color-text-primary)]">
                        {{ $category->name }}
                    </span>
                        را بررسی کنید.
                    </p>

                </div>


                {{-- Results count --}}
                <div class="inline-flex w-fit items-center gap-2 rounded-full bg-white px-4 py-2 text-xs font-bold text-[var(--color-text-secondary)] shadow-[var(--shadow-xs)] ring-1 ring-[var(--color-border)]">

                    <span class="h-2 w-2 rounded-full bg-[var(--color-accent-600)]"></span>

                    {{ number_format($products->total()) }}
                    نتیجه

                </div>

            </div>


            {{-- =====================================================
                PRODUCTS
            ====================================================== --}}

            @if($products->count())

                <div class="mt-8 grid grid-cols-2 gap-x-4 gap-y-8 md:grid-cols-3 lg:grid-cols-4">

                    @foreach($products as $product)

                        @include('partials.product_card', [
                            'product' => $product
                        ])

                    @endforeach

                </div>


                {{-- =================================================
                    PAGINATION
                ================================================== --}}

                @if($products->hasPages())

                    <div class="mt-12 flex justify-center">

                        <div class="pagination">
                            {{ $products->onEachSide(1)->links() }}
                        </div>

                    </div>

                @endif


            @else

                {{-- =================================================
                    EMPTY STATE
                ================================================== --}}

                <div class="mt-8 overflow-hidden rounded-[2rem] border border-dashed border-[var(--color-border-strong)] bg-white">

                    <div class="relative flex flex-col items-center px-6 py-20 text-center sm:py-24">

                        <div
                            class="pointer-events-none absolute -right-16 -top-16 h-40 w-40 rounded-full bg-[var(--color-accent-600)]/5 blur-3xl"
                        ></div>

                        <div
                            class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[var(--color-brand-50)] text-[var(--color-brand-900)]"
                        >
                            <svg
                                class="h-7 w-7"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                aria-hidden="true"
                            >
                                <path d="M4 5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5Z"/>
                                <path d="M8 9h8M8 13h5"/>
                            </svg>
                        </div>

                        <h3 class="mt-5 text-xl font-black text-[var(--color-text-primary)] sm:text-2xl">
                            فعلاً محصولی در این دسته نیست.
                        </h3>

                        <p class="mt-2 max-w-md text-sm leading-7 text-[var(--color-text-secondary)]">
                            محصولات جدید به‌مرور به فروشگاه اضافه می‌شوند.
                            در همین فاصله می‌توانید سایر دسته‌ها را بررسی کنید.
                        </p>

                        <a
                            href="{{ route('shop.index') }}"
                            class="mt-7 inline-flex items-center gap-2 rounded-2xl bg-[var(--color-accent-600)] px-6 py-3.5 text-sm font-black text-white shadow-md transition hover:-translate-y-0.5 hover:bg-[var(--color-accent-700)]"
                        >
                            بازگشت به فروشگاه

                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                aria-hidden="true"
                            >
                                <path d="m9 18 6-6-6-6"/>
                            </svg>
                        </a>

                    </div>

                </div>

            @endif

        </section>


        {{-- =========================================================
            BOTTOM CTA
        ========================================================== --}}

        <section class="mt-16 border-t border-[var(--color-border)] bg-white">

            <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">

                <div class="relative overflow-hidden rounded-[2rem] bg-[var(--color-brand-950)] px-6 py-9 text-white sm:px-10">

                    <div
                        class="pointer-events-none absolute -left-16 -top-16 h-48 w-48 rounded-full bg-[var(--color-accent-600)]/15 blur-3xl"
                    ></div>

                    <div
                        class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between"
                    >

                        <div class="max-w-2xl">

                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-white/35">
                            FARZIN
                        </span>

                            <h2 class="mt-2 text-2xl font-black sm:text-3xl">
                                هنوز چیزی مناسب خودت پیدا نکردی؟
                            </h2>

                            <p class="mt-3 text-sm leading-7 text-white/55">
                                همه محصولات فرزین را ببین و با استفاده از جستجو و فیلترها،
                                گزینه مناسب خودت را پیدا کن.
                            </p>

                        </div>


                        <a
                            href="{{ route('shop.index') }}"
                            class="inline-flex shrink-0 items-center justify-center gap-2 rounded-2xl bg-[var(--color-accent-600)] px-6 py-3.5 text-sm font-black text-white transition hover:bg-[var(--color-accent-700)]"
                        >
                            مشاهده همه محصولات

                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                aria-hidden="true"
                            >
                                <path d="m9 18 6-6-6-6"/>
                            </svg>
                        </a>

                    </div>

                </div>

            </div>

        </section>

    </div>

@endsection
