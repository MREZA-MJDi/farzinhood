@extends('layouts.admin')

@section('title', 'محصولات')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-black text-[var(--color-text-primary)]">
                    محصولات
                </h1>

                <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                    مدیریت محصولات فروشگاه
                </p>
            </div>

            <a href="{{ route('admin.products.create') }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-600)] px-5 py-3 text-sm font-bold text-white transition hover:bg-[var(--color-brand-700)]">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 4v16m8-8H4"/>
                </svg>

                افزودن محصول
            </a>
        </div>

        {{-- Filters --}}
        <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">
            <form method="GET"
                  action="{{ route('admin.products.index') }}"
                  class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">

                {{-- Search --}}
                <div class="lg:col-span-2">
                    <label for="search"
                           class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                        جستجو
                    </label>

                    <input type="text"
                           id="search"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="نام محصول، SKU یا برند..."
                           class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none transition placeholder:text-[var(--color-text-muted)] focus:border-[var(--color-brand-600)]">
                </div>

                {{-- Category --}}
                <div>
                    <label for="category_id"
                           class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                        دسته‌بندی
                    </label>

                    <select id="category_id"
                            name="category_id"
                            class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none transition focus:border-[var(--color-brand-600)]">
                        <option value="">همه دسته‌بندی‌ها</option>

                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                    @selected((string) request('category_id') === (string) $category->id)>
                            {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status --}}
                <div>
                    <label for="status"
                           class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                        وضعیت
                    </label>

                    <select id="status"
                            name="status"
                            class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none transition focus:border-[var(--color-brand-600)]">
                        <option value="">همه</option>

                        <option value="active" @selected(request('status') === 'active')}>
                        فعال
                        </option>

                        <option value="inactive" @selected(request('status') === 'inactive')}>
                        غیرفعال
                        </option>

                        <option value="featured" @selected(request('status') === 'featured')}>
                        ویژه
                        </option>
                    </select>
                </div>

                {{-- Actions --}}
                <div class="flex items-end gap-3 lg:col-span-4">
                    <button type="submit"
                            class="rounded-xl bg-[var(--color-brand-600)] px-5 py-3 text-sm font-bold text-white transition hover:bg-[var(--color-brand-700)]">
                        اعمال فیلتر
                    </button>

                    <a href="{{ route('admin.products.index') }}"
                       class="rounded-xl border border-[var(--color-border)] bg-[var(--color-neutral-50)] px-5 py-3 text-sm font-bold text-[var(--color-text-primary)] transition hover:bg-[var(--color-neutral-100)]">
                        پاک کردن
                    </a>
                </div>
            </form>
        </div>

        {{-- Products --}}
        <div class="overflow-hidden rounded-2xl border border-[var(--color-border)] bg-white shadow-sm">

            {{-- Desktop Table --}}
            <div class="hidden overflow-x-auto lg:block">
                <table class="min-w-full text-right">
                    <thead class="border-b border-[var(--color-border)] bg-[var(--color-neutral-50)]">
                    <tr>
                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            محصول
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            دسته‌بندی
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            قیمت
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            موجودی
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            وضعیت
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            عملیات
                        </th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-[var(--color-border)]">
                    @forelse($products as $product)
                        <tr class="transition hover:bg-[var(--color-neutral-50)]">

                            {{-- Product --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">

                                    <div class="h-14 w-14 flex-shrink-0 overflow-hidden rounded-xl border border-[var(--color-border)] bg-[var(--color-neutral-50)]">
                                        @if($product->primaryImage)
                                            <img src="{{ asset('storage/' . $product->primaryImage->image) }}"
                                                 alt="{{ $product->primaryImage->alt ?: $product->name }}"
                                                 class="h-full w-full object-cover">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center text-[var(--color-text-muted)]">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-7 w-7"
                                                     fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke="currentColor"
                                                     stroke-width="1.5">
                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          d="M4 16l4.5-4.5a2 2 0 012.828 0L16 16m-2-2l1.5-1.5a2 2 0 012.828 0L20 15m-2-9h.01M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="min-w-0">
                                        <div class="truncate font-bold text-[var(--color-text-primary)]">
                                            {{ $product->name }}
                                        </div>

                                        <div class="mt-1 text-xs text-[var(--color-text-muted)]">
                                            SKU: {{ $product->sku }}
                                        </div>

                                        @if($product->brand)
                                            <div class="mt-1 text-xs text-[var(--color-text-secondary)]">
                                                {{ $product->brand }}
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            </td>

                            {{-- Category --}}
                            <td class="px-5 py-4">
                                <span class="text-sm text-[var(--color-text-secondary)]">
                                    {{ $product->category?->name ?? 'بدون دسته‌بندی' }}
                                </span>
                            </td>

                            {{-- Price --}}
                            <td class="px-5 py-4">
                                <div class="font-bold text-[var(--color-text-primary)]">
                                    {{ number_format($product->price) }}
                                    تومان
                                </div>

                                @if($product->old_price)
                                    <div class="mt-1 text-xs text-[var(--color-text-muted)] line-through">
                                        {{ number_format($product->old_price) }}
                                    </div>
                                @endif
                            </td>

                            {{-- Stock --}}
                            <td class="px-5 py-4">
                                @if($product->stock <= 0)
                                    <span class="inline-flex rounded-full bg-red-50 px-3 py-1 text-xs font-bold text-red-700">
                                        ناموجود
                                    </span>
                                @elseif($product->stock <= 5)
                                    <span class="inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">
                                        {{ number_format($product->stock) }} عدد
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                                        {{ number_format($product->stock) }} عدد
                                    </span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-2">

                                    @if($product->is_active)
                                        <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                                            فعال
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-red-50 px-3 py-1 text-xs font-bold text-red-700">
                                            غیرفعال
                                        </span>
                                    @endif

                                    @if($product->is_featured)
                                        <span class="inline-flex rounded-full bg-[var(--color-brand-50)] px-3 py-1 text-xs font-bold text-[var(--color-brand-700)]">
                                            ویژه
                                        </span>
                                    @endif

                                </div>
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">

                                    <a href="{{ route('admin.products.edit', $product) }}"
                                       class="rounded-lg border border-[var(--color-border)] px-3 py-2 text-xs font-bold text-[var(--color-text-primary)] transition hover:bg-[var(--color-neutral-50)]">
                                        ویرایش
                                    </a>

                                    <form action="{{ route('admin.products.destroy', $product) }}"
                                          method="POST"
                                          onsubmit="return confirm('آیا از حذف این محصول مطمئن هستید؟');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="rounded-lg bg-red-50 px-3 py-2 text-xs font-bold text-red-700 transition hover:bg-red-100">
                                            حذف
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <div class="mx-auto max-w-sm">
                                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[var(--color-neutral-100)]">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-8 w-8 text-[var(--color-text-muted)]"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.5">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4m4-9h8"/>
                                        </svg>
                                    </div>

                                    <h3 class="mt-4 text-lg font-black text-[var(--color-text-primary)]">
                                        محصولی پیدا نشد
                                    </h3>

                                    <p class="mt-2 text-sm text-[var(--color-text-secondary)]">
                                        هنوز محصولی ثبت نشده یا فیلترهای فعلی نتیجه‌ای ندارند.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards --}}
            <div class="divide-y divide-[var(--color-border)] lg:hidden">
                @forelse($products as $product)
                    <div class="p-4">
                        <div class="flex gap-4">

                            <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-xl border border-[var(--color-border)] bg-[var(--color-neutral-50)]">
                                @if($product->primaryImage)
                                    <img src="{{ asset('storage/' . $product->primaryImage->image) }}"
                                         alt="{{ $product->primaryImage->alt ?: $product->name }}"
                                         class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-[var(--color-text-muted)]">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-7 w-7"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.5">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M4 16l4.5-4.5a2 2 0 012.828 0L16 16m-2-2l1.5-1.5a2 2 0 012.828 0L20 15m-2-9h.01M5 19h14a2 2 0 002-2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="font-black text-[var(--color-text-primary)]">
                                    {{ $product->name }}
                                </div>

                                <div class="mt-1 text-xs text-[var(--color-text-muted)]">
                                    {{ $product->category?->name ?? 'بدون دسته‌بندی' }}
                                </div>

                                <div class="mt-2 font-bold text-[var(--color-text-primary)]">
                                    {{ number_format($product->price) }} تومان
                                </div>

                                <div class="mt-2 flex flex-wrap gap-2">
                                    @if($product->is_active)
                                        <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700">
                                            فعال
                                        </span>
                                    @else
                                        <span class="rounded-full bg-red-50 px-2.5 py-1 text-[11px] font-bold text-red-700">
                                            غیرفعال
                                        </span>
                                    @endif

                                    @if($product->is_featured)
                                        <span class="rounded-full bg-[var(--color-brand-50)] px-2.5 py-1 text-[11px] font-bold text-[var(--color-brand-700)]">
                                            ویژه
                                        </span>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <div class="mt-4 flex gap-2">
                            <a href="{{ route('admin.products.edit', $product) }}"
                               class="flex-1 rounded-xl border border-[var(--color-border)] px-4 py-3 text-center text-xs font-bold text-[var(--color-text-primary)]">
                                ویرایش
                            </a>

                            <form action="{{ route('admin.products.destroy', $product) }}"
                                  method="POST"
                                  class="flex-1"
                                  onsubmit="return confirm('آیا از حذف این محصول مطمئن هستید؟');">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="w-full rounded-xl bg-red-50 px-4 py-3 text-xs font-bold text-red-700">
                                    حذف
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-16 text-center">
                        <h3 class="text-lg font-black text-[var(--color-text-primary)]">
                            محصولی پیدا نشد
                        </h3>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($products->hasPages())
                <div class="border-t border-[var(--color-border)] px-5 py-4">
                    {{ $products->withQueryString()->links() }}
                </div>
            @endif

        </div>
    </div>
@endsection
