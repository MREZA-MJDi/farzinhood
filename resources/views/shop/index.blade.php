@extends('layouts.app')

@section('title', 'فروشگاه | Farzin')

@section('content')

    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">

            <div>
                <div class="text-xs font-bold uppercase tracking-[0.25em] text-[#7b20df]">
                    Shop
                </div>

                <h1 class="mt-3 text-4xl font-black tracking-tight text-gray-950">
                    فروشگاه
                </h1>

                <p class="mt-3 max-w-2xl text-sm leading-7 text-gray-500">
                    از بین محصولات موجود، چیزی که واقعاً به کارت می‌آید را پیدا کن.
                </p>
            </div>

            <div class="text-sm text-gray-500">
                {{ $products->total() }} محصول
            </div>

        </div>


        {{-- Search --}}
        <form
            action="{{ route('shop.index') }}"
            method="GET"
            class="mt-8 rounded-[1.75rem] border border-gray-200 bg-white p-3 shadow-sm"
        >
            <div class="grid gap-3 md:grid-cols-[1fr_auto]">

                <div class="relative">
                    <input
                        type="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="نام محصول، برند یا SKU..."
                        class="w-full rounded-2xl bg-gray-50 px-5 py-4 text-sm outline-none ring-0 transition placeholder:text-gray-400 focus:bg-white focus:ring-4 focus:ring-[#7b20df]/10"
                    >
                </div>

                <button
                    type="submit"
                    class="rounded-2xl bg-[#3f207e] px-7 py-4 text-sm font-bold text-white transition hover:bg-[#321866]"
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
                    class="rounded-[1.75rem] border border-gray-200 bg-white p-5"
                >

                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-black text-gray-950">
                            فیلترها
                        </h2>

                        <a
                            href="{{ route('shop.index') }}"
                            class="text-xs font-bold text-gray-400 transition hover:text-[#7b20df]"
                        >
                            حذف همه
                        </a>
                    </div>


                    <div class="mt-7">
                        <label class="text-xs font-bold text-gray-700">
                            دسته‌بندی
                        </label>

                        <select
                            name="category"
                            class="mt-3 w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm outline-none focus:border-[#7b20df]"
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
                        <label class="text-xs font-bold text-gray-700">
                            مرتب‌سازی
                        </label>

                        <select
                            name="sort"
                            class="mt-3 w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm outline-none focus:border-[#7b20df]"
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
                            <label class="text-xs font-bold text-gray-700">
                                حداقل قیمت
                            </label>

                            <input
                                type="number"
                                name="min_price"
                                min="0"
                                value="{{ request('min_price') }}"
                                placeholder="{{ number_format($priceMin) }}"
                                class="mt-3 w-full rounded-xl border border-gray-200 px-3 py-3 text-sm outline-none focus:border-[#7b20df]"
                            >
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-700">
                                حداکثر قیمت
                            </label>

                            <input
                                type="number"
                                name="max_price"
                                min="0"
                                value="{{ request('max_price') }}"
                                placeholder="{{ number_format($priceMax) }}"
                                class="mt-3 w-full rounded-xl border border-gray-200 px-3 py-3 text-sm outline-none focus:border-[#7b20df]"
                            >
                        </div>

                    </div>


                    <button
                        type="submit"
                        class="mt-7 w-full rounded-xl bg-[#3f207e] px-4 py-3.5 text-sm font-bold text-white transition hover:bg-[#321866]"
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

                    <div class="rounded-[2rem] border border-dashed border-gray-300 bg-white px-6 py-24 text-center">

                        <div class="mx-auto flex size-16 items-center justify-center rounded-2xl bg-[#f3edfb] text-2xl text-[#3f207e]">
                            ×
                        </div>

                        <h2 class="mt-5 text-xl font-black text-gray-950">
                            محصولی پیدا نشد
                        </h2>

                        <p class="mx-auto mt-2 max-w-md text-sm leading-7 text-gray-500">
                            فیلترها یا عبارت جستجو را کمی تغییر بده.
                        </p>

                        <a
                            href="{{ route('shop.index') }}"
                            class="mt-6 inline-flex rounded-xl bg-[#3f207e] px-5 py-3 text-sm font-bold text-white"
                        >
                            بازنشانی
                        </a>

                    </div>

                @endif

            </div>

        </div>

    </section>

@endsection
