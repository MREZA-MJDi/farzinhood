@extends('layouts.app')

@section('title', 'فروشگاه | Farzin')

@section('content')

    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">

            <div>
                <div class="text-xs font-bold uppercase tracking-[0.25em] text-[var(--color-accent-600)]">
                    Shop
                </div>

                <h1 class="mt-3 text-4xl font-black tracking-tight text-[var(--color-text-primary)]">
                    فروشگاه
                </h1>

                <p class="mt-3 max-w-2xl text-sm leading-7 text-[var(--color-text-secondary)]">
                    از بین محصولات موجود، چیزی که واقعاً به کارت می‌آید را پیدا کن.
                </p>
            </div>

            <div class="text-sm text-[var(--color-text-secondary)]">
                {{ $products->total() }} محصول
            </div>

        </div>


        {{-- Search --}}
        <form
            action="{{ route('shop.index') }}"
            method="GET"
            class="mt-8 rounded-[1.75rem] border border-[var(--color-border)] bg-[var(--color-surface)] p-3 shadow-sm"
        >
            <div class="grid gap-3 md:grid-cols-[1fr_auto]">

                <div class="relative">
                    <input
                        type="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="نام محصول، برند یا SKU..."
                        class="w-full rounded-2xl bg-[var(--color-neutral-50)] px-5 py-4 text-sm outline-none ring-0 transition placeholder:text-[var(--color-text-muted)] focus:bg-[var(--color-surface)] focus:ring-4 focus:ring-[var(--color-accent-600)]/10"
                    >
                </div>

                <button
                    type="submit"
                    class="rounded-2xl bg-[var(--color-brand-900)] px-7 py-4 text-sm font-bold text-white transition hover:bg-[var(--color-brand-800)]"
                >
                    جستجو
                </button>

            </div>
        </form>


        {{-- Main --}}
        <div class="mt-10 grid gap-8 lg:grid-cols-[240px_1fr]">

            {{-- Filters --}}
            <aside class="lg:sticky lg:top-28 lg:self-start">
                <form
                    action="{{ route('shop.index') }}"
                    method="GET"
                    class="rounded-[1.75rem] border border-[var(--color-border)] bg-[var(--color-surface)] p-5"
                >

                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-black text-[var(--color-text-primary)]">
                            فیلترها
                        </h2>

                        <a
                            href="{{ route('shop.index') }}"
                            class="text-xs font-bold text-[var(--color-text-muted)] transition hover:text-[var(--color-accent-600)]"
                        >
                            حذف همه
                        </a>
                    </div>


                    <div class="mt-7">
                        <label class="text-xs font-bold text-[var(--color-text-secondary)]">
                            دسته‌بندی
                        </label>

                        <select
                            name="category"
                            class="mt-3 w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface)] px-4 py-3 text-sm outline-none focus:border-[var(--color-accent-600)]"
                        >
                            <option value="">همه دسته‌ها</option>

                            @foreach($categories as $category)
                                <option
                                    value="{{ $category->slug }}"
                                    @selected(request('category') === $category->slug)
                                >
                                {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="mt-7">
                        <label class="text-xs font-bold text-[var(--color-text-secondary)]">
                            مرتب‌سازی
                        </label>

                        <select
                            name="sort"
                            class="mt-3 w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface)] px-4 py-3 text-sm outline-none focus:border-[var(--color-accent-600)]"
                        >
                            <option value="latest" @selected(request('sort', 'latest') === 'latest')>
                            جدیدترین
                            </option>

                            <option value="price_asc" @selected(request('sort') === 'price_asc')>
                            ارزان‌ترین
                            </option>

                            <option value="price_desc" @selected(request('sort') === 'price_desc')>
                            گران‌ترین
                            </option>

                            <option value="popular" @selected(request('sort') === 'popular')>
                            محبوب‌ترین
                            </option>

                            <option value="rating" @selected(request('sort') === 'rating')>
                            بالاترین امتیاز
                            </option>
                        </select>
                    </div>


                    <div class="mt-7 grid grid-cols-2 gap-3">

                        <div>
                            <label class="text-xs font-bold text-[var(--color-text-secondary)]">
                                حداقل قیمت
                            </label>

                            <input
                                type="number"
                                name="min_price"
                                min="0"
                                value="{{ request('min_price') }}"
                                placeholder="{{ number_format($priceMin) }}"
                                class="mt-3 w-full rounded-xl border border-[var(--color-border)] px-3 py-3 text-sm outline-none focus:border-[var(--color-accent-600)]"
                            >
                        </div>

                        <div>
                            <label class="text-xs font-bold text-[var(--color-text-secondary)]">
                                حداکثر قیمت
                            </label>

                            <input
                                type="number"
                                name="max_price"
                                min="0"
                                value="{{ request('max_price') }}"
                                placeholder="{{ number_format($priceMax) }}"
                                class="mt-3 w-full rounded-xl border border-[var(--color-border)] px-3 py-3 text-sm outline-none focus:border-[var(--color-accent-600)]"
                            >
                        </div>

                    </div>


                    <button
                        type="submit"
                        class="mt-7 w-full rounded-xl bg-[var(--color-brand-900)] px-4 py-3.5 text-sm font-bold text-white transition hover:bg-[var(--color-brand-800)]"
                    >
                        اعمال فیلتر
                    </button>

                </form>
            </aside>


            {{-- Products --}}
            <div>

                @if($products->count())

                    <div class="grid grid-cols-2 gap-x-4 gap-y-10 md:grid-cols-3 xl:grid-cols-4">

                        @foreach($products as $product)

                            @include('partials.product_card', ['product' => $product])

                        @endforeach

                    </div>

                    <div class="mt-12">
                        {{ $products->onEachSide(1)->links() }}
                    </div>

                @else

                    <div class="rounded-[2rem] border border-dashed border-gray-300 bg-[var(--color-surface)] px-6 py-24 text-center">

                        <div class="mx-auto flex size-16 items-center justify-center rounded-2xl bg-[var(--color-accent-50)] text-2xl text-[var(--color-brand-900)]">
                            ×
                        </div>

                        <h2 class="mt-5 text-xl font-black text-[var(--color-text-primary)]">
                            محصولی پیدا نشد
                        </h2>

                        <p class="mx-auto mt-2 max-w-md text-sm leading-7 text-[var(--color-text-secondary)]">
                            فیلترها یا عبارت جستجو را کمی تغییر بده.
                        </p>

                        <a
                            href="{{ route('shop.index') }}"
                            class="mt-6 inline-flex rounded-xl bg-[var(--color-brand-900)] px-5 py-3 text-sm font-bold text-white"
                        >
                            بازنشانی
                        </a>

                    </div>

                @endif

            </div>

        </div>

    </section>

@endsection
