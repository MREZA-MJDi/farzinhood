@extends('layouts.admin')

@section('title', 'موجودی انبار')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-black text-[var(--color-text-primary)]">
                موجودی انبار
            </h1>

            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                کنترل موجودی محصولات و ثبت ورود و خروج کالا
            </p>
        </div>

        {{-- Filters --}}
        <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">
            <form method="GET"
                  action="{{ route('admin.inventory.index') }}"
                  class="grid grid-cols-1 gap-4 md:grid-cols-3">

                <div class="md:col-span-2">
                    <label for="search"
                           class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                        جستجو
                    </label>

                    <input type="text"
                           id="search"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="نام محصول یا SKU..."
                           class="w-full rounded-xl border border-[var(--color-border)] px-4 py-3 text-sm outline-none focus:border-[var(--color-brand-600)]">
                </div>

                <div>
                    <label for="stock_status"
                           class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                        وضعیت موجودی
                    </label>

                    <select id="stock_status"
                            name="stock_status"
                            class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm outline-none focus:border-[var(--color-brand-600)]">
                        <option value="">همه</option>
                        <option value="out" @selected(request('stock_status') === 'out')>
                        ناموجود
                        </option>
                        <option value="low" @selected(request('stock_status') === 'low')>
                        کمبود موجودی
                        </option>
                        <option value="available" @selected(request('stock_status') === 'available')>
                        موجود
                        </option>
                    </select>
                </div>

                <div class="flex gap-3 md:col-span-3">
                    <button type="submit"
                            class="rounded-xl bg-[var(--color-brand-600)] px-5 py-3 text-sm font-bold text-white hover:bg-[var(--color-brand-700)]">
                        اعمال فیلتر
                    </button>

                    <a href="{{ route('admin.inventory.index') }}"
                       class="rounded-xl border border-[var(--color-border)] bg-[var(--color-neutral-50)] px-5 py-3 text-sm font-bold text-[var(--color-text-primary)]">
                        پاک کردن
                    </a>
                </div>

            </form>
        </div>

        {{-- Products --}}
        <div class="overflow-hidden rounded-2xl border border-[var(--color-border)] bg-white shadow-sm">

            <div class="overflow-x-auto">
                <table class="min-w-full text-right">

                    <thead class="border-b border-[var(--color-border)] bg-[var(--color-neutral-50)]">
                    <tr>
                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            محصول
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            SKU
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

                        <tr class="hover:bg-[var(--color-neutral-50)]">

                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">

                                    <div class="h-12 w-12 flex-shrink-0 overflow-hidden rounded-xl border border-[var(--color-border)] bg-[var(--color-neutral-50)]">
                                        @if($product->primaryImage)
                                            <img src="{{ asset('storage/' . $product->primaryImage->image) }}"
                                                 alt="{{ $product->primaryImage->alt ?: $product->name }}"
                                                 class="h-full w-full object-cover">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center text-[var(--color-text-muted)]">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-6 w-6"
                                                     fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke="currentColor">
                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          stroke-width="1.5"
                                                          d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <div>
                                        <div class="font-bold text-[var(--color-text-primary)]">
                                            {{ $product->name }}
                                        </div>

                                        <div class="mt-1 text-xs text-[var(--color-text-muted)]">
                                            {{ $product->category?->name ?? 'بدون دسته‌بندی' }}
                                        </div>
                                    </div>

                                </div>
                            </td>

                            <td class="px-5 py-4">
                                <span dir="ltr"
                                      class="text-sm text-[var(--color-text-secondary)]">
                                    {{ $product->sku }}
                                </span>
                            </td>

                            <td class="px-5 py-4">
                                <span class="text-lg font-black text-[var(--color-text-primary)]">
                                    {{ number_format($product->stock) }}
                                </span>
                                <span class="text-xs text-[var(--color-text-muted)]">
                                    عدد
                                </span>
                            </td>

                            <td class="px-5 py-4">
                                @if($product->stock <= 0)
                                    <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-bold text-red-700">
                                        ناموجود
                                    </span>
                                @elseif($product->stock <= 5)
                                    <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">
                                        موجودی کم
                                    </span>
                                @else
                                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                                        موجود
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-2">

                                    <a href="{{ route('admin.inventory.movements', $product) }}"
                                       class="rounded-lg border border-[var(--color-border)] px-3 py-2 text-xs font-bold text-[var(--color-text-primary)] hover:bg-[var(--color-neutral-50)]">
                                        گردش موجودی
                                    </a>

                                    <button type="button"
                                            onclick="document.getElementById('adjust-{{ $product->id }}').classList.toggle('hidden')"
                                            class="rounded-lg bg-[var(--color-brand-50)] px-3 py-2 text-xs font-bold text-[var(--color-brand-700)]">
                                        اصلاح موجودی
                                    </button>

                                </div>
                            </td>

                        </tr>

                        {{-- Adjustment --}}
                        <tr id="adjust-{{ $product->id }}" class="hidden bg-[var(--color-neutral-50)]">
                            <td colspan="5" class="px-5 py-5">

                                <form method="POST"
                                      action="{{ route('admin.inventory.adjust', $product) }}"
                                      class="grid grid-cols-1 gap-4 md:grid-cols-4">
                                    @csrf

                                    <div>
                                        <label class="mb-2 block text-xs font-bold text-[var(--color-text-primary)]">
                                            نوع عملیات
                                        </label>

                                        <select name="type"
                                                class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm outline-none focus:border-[var(--color-brand-600)]">
                                            <option value="restock">
                                                افزایش موجودی
                                            </option>

                                            <option value="adjustment">
                                                اصلاح موجودی
                                            </option>

                                            <option value="return">
                                                برگشت کالا
                                            </option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-xs font-bold text-[var(--color-text-primary)]">
                                            تعداد
                                        </label>

                                        <input type="number"
                                               name="quantity"
                                               min="1"
                                               required
                                               placeholder="مثلاً 10"
                                               class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm outline-none focus:border-[var(--color-brand-600)]">
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-xs font-bold text-[var(--color-text-primary)]">
                                            توضیحات
                                        </label>

                                        <input type="text"
                                               name="note"
                                               placeholder="توضیحات عملیات..."
                                               class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm outline-none focus:border-[var(--color-brand-600)]">
                                    </div>

                                    <div class="flex items-end">
                                        <button type="submit"
                                                class="w-full rounded-xl bg-[var(--color-brand-600)] px-5 py-3 text-sm font-bold text-white hover:bg-[var(--color-brand-700)]">
                                            ثبت تغییر موجودی
                                        </button>
                                    </div>

                                </form>

                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="px-5 py-16 text-center">
                                <h3 class="text-lg font-black text-[var(--color-text-primary)]">
                                    محصولی پیدا نشد
                                </h3>
                            </td>
                        </tr>

                    @endforelse

                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
                <div class="border-t border-[var(--color-border)] px-5 py-4">
                    {{ $products->withQueryString()->links() }}
                </div>
            @endif

        </div>

    </div>
@endsection
