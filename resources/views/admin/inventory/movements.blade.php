@extends('layouts.admin')

@section('title', 'گردش موجودی')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h1 class="text-2xl font-black text-[var(--color-text-primary)]">
                    گردش موجودی
                </h1>

                <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                    تاریخچه تغییرات موجودی «{{ $product->name }}»
                </p>
            </div>

            <a href="{{ route('admin.inventory.index') }}"
               class="rounded-xl border border-[var(--color-border)] bg-white px-5 py-3 text-sm font-bold text-[var(--color-text-primary)] hover:bg-[var(--color-neutral-50)]">
                بازگشت به انبار
            </a>

        </div>

        {{-- Product Summary --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

            <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">
                <div class="text-xs text-[var(--color-text-muted)]">
                    محصول
                </div>

                <div class="mt-2 font-black text-[var(--color-text-primary)]">
                    {{ $product->name }}
                </div>

                <div class="mt-1 text-xs text-[var(--color-text-secondary)]">
                    SKU: {{ $product->sku }}
                </div>
            </div>

            <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">
                <div class="text-xs text-[var(--color-text-muted)]">
                    موجودی فعلی
                </div>

                <div class="mt-2 text-2xl font-black text-[var(--color-text-primary)]">
                    {{ number_format($product->stock) }}
                    <span class="text-sm font-medium text-[var(--color-text-muted)]">
                        عدد
                    </span>
                </div>
            </div>

            <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">
                <div class="text-xs text-[var(--color-text-muted)]">
                    تعداد تغییرات
                </div>

                <div class="mt-2 text-2xl font-black text-[var(--color-text-primary)]">
                    {{ number_format($movements->total()) }}
                </div>
            </div>

        </div>

        {{-- Movements --}}
        <div class="overflow-hidden rounded-2xl border border-[var(--color-border)] bg-white shadow-sm">

            <div class="overflow-x-auto">
                <table class="min-w-full text-right">

                    <thead class="border-b border-[var(--color-border)] bg-[var(--color-neutral-50)]">
                    <tr>
                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            تاریخ
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            عملیات
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            تغییر
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            قبل
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            بعد
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            کاربر
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            توضیحات
                        </th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-[var(--color-border)]">

                    @forelse($movements as $movement)

                        @php
                            $typeLabels = [
                                'sale' => 'فروش',
                                'restock' => 'تأمین موجودی',
                                'return' => 'مرجوعی',
                                'adjustment' => 'اصلاح',
                            ];
                        @endphp

                        <tr class="hover:bg-[var(--color-neutral-50)]">

                            <td class="px-5 py-4">
                                <div class="text-sm font-bold text-[var(--color-text-primary)]">
                                    {{ $movement->created_at->locale('fa')->translatedFormat('Y/m/d') }}
                                </div>

                                <div class="mt-1 text-xs text-[var(--color-text-muted)]">
                                    {{ $movement->created_at->locale('fa')->translatedFormat('H:i') }}
                                </div>
                            </td>

                            <td class="px-5 py-4">
                                <span class="rounded-full bg-[var(--color-neutral-100)] px-3 py-1 text-xs font-bold text-[var(--color-text-secondary)]">
                                    {{ $typeLabels[$movement->type] ?? $movement->type }}
                                </span>
                            </td>

                            <td class="px-5 py-4">
                                @if($movement->quantity > 0)
                                    <span class="font-black text-emerald-700">
                                        +{{ number_format($movement->quantity) }}
                                    </span>
                                @else
                                    <span class="font-black text-red-700">
                                        {{ number_format($movement->quantity) }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4">
                                {{ number_format($movement->stock_before) }}
                            </td>

                            <td class="px-5 py-4">
                                <span class="font-bold">
                                    {{ number_format($movement->stock_after) }}
                                </span>
                            </td>

                            <td class="px-5 py-4">
                                {{ $movement->user?->name ?? 'سیستم' }}
                            </td>

                            <td class="max-w-xs px-5 py-4 text-sm text-[var(--color-text-secondary)]">
                                {{ $movement->note ?: '—' }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <h3 class="text-lg font-black text-[var(--color-text-primary)]">
                                    هنوز گردش موجودی ثبت نشده است
                                </h3>
                            </td>
                        </tr>

                    @endforelse

                    </tbody>
                </table>
            </div>

            @if($movements->hasPages())
                <div class="border-t border-[var(--color-border)] px-5 py-4">
                    {{ $movements->withQueryString()->links() }}
                </div>
            @endif

        </div>

    </div>
@endsection
