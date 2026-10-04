@extends('layouts.admin')

@section('title', 'مشتریان')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-black text-[var(--color-text-primary)]">
                مشتریان
            </h1>

            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                مدیریت کاربران و مشتریان فروشگاه
            </p>
        </div>


        {{-- Filters --}}
        <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

            <form action="{{ route('admin.customers.index') }}"
                  method="GET"
                  class="grid grid-cols-1 gap-4 md:grid-cols-3">

                {{-- Search --}}
                <div class="md:col-span-2">
                    <label for="search"
                           class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                        جستجو
                    </label>

                    <input type="text"
                           id="search"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="نام، ایمیل یا شماره تماس..."
                           class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none placeholder:text-[var(--color-text-muted)] focus:border-[var(--color-brand-600)]">
                </div>


                {{-- Status --}}
                <div>
                    <label for="status"
                           class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                        وضعیت
                    </label>

                    <select id="status"
                            name="status"
                            class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none focus:border-[var(--color-brand-600)]">

                        <option value="">
                            همه
                        </option>

                        <option value="active"
                                @selected(request('status') === 'active')}>
                        فعال
                        </option>

                        <option value="inactive"
                                @selected(request('status') === 'inactive')}>
                        غیرفعال
                        </option>

                    </select>
                </div>


                {{-- Actions --}}
                <div class="flex gap-3 md:col-span-3">

                    <button type="submit"
                            class="rounded-xl bg-[var(--color-brand-600)] px-5 py-3 text-sm font-bold text-white hover:bg-[var(--color-brand-700)]">
                        اعمال فیلتر
                    </button>

                    <a href="{{ route('admin.customers.index') }}"
                       class="rounded-xl border border-[var(--color-border)] bg-[var(--color-neutral-50)] px-5 py-3 text-sm font-bold text-[var(--color-text-primary)] hover:bg-[var(--color-neutral-100)]">
                        پاک کردن
                    </a>

                </div>

            </form>

        </div>


        {{-- Customers --}}
        <div class="overflow-hidden rounded-2xl border border-[var(--color-border)] bg-white shadow-sm">

            <div class="overflow-x-auto">

                <table class="min-w-full text-right">

                    <thead class="border-b border-[var(--color-border)] bg-[var(--color-neutral-50)]">
                    <tr>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            مشتری
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            سفارش‌ها
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            مجموع خرید
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            وضعیت
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            عضویت
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            عملیات
                        </th>

                    </tr>
                    </thead>


                    <tbody class="divide-y divide-[var(--color-border)]">

                    @forelse($customers as $customer)

                        <tr class="transition hover:bg-[var(--color-neutral-50)]">

                            {{-- Customer --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-[var(--color-brand-50)] text-lg font-black text-[var(--color-brand-700)]">
                                        {{ mb_substr($customer->name ?: '؟', 0, 1) }}
                                    </div>

                                    <div class="min-w-0">

                                        <div class="font-bold text-[var(--color-text-primary)]">
                                            {{ $customer->name ?: 'بدون نام' }}
                                        </div>

                                        <div class="mt-1 text-xs text-[var(--color-text-secondary)]"
                                             dir="ltr">
                                            {{ $customer->email }}
                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- Orders --}}
                            <td class="px-5 py-4">

                                <span class="font-bold text-[var(--color-text-primary)]">
                                    {{ number_format($customer->orders_count ?? 0) }}
                                </span>

                                <span class="text-xs text-[var(--color-text-muted)]">
                                    سفارش
                                </span>

                            </td>


                            {{-- Total --}}
                            <td class="px-5 py-4">

                                <div class="font-black text-[var(--color-text-primary)]">
                                    {{ number_format($customer->orders_sum_total ?? 0) }}
                                </div>

                                <div class="mt-1 text-xs text-[var(--color-text-muted)]">
                                    تومان
                                </div>

                            </td>


                            {{-- Status --}}
                            <td class="px-5 py-4">

                                @if($customer->is_active)

                                    <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                                        فعال
                                    </span>

                                @else

                                    <span class="inline-flex rounded-full bg-red-50 px-3 py-1 text-xs font-bold text-red-700">
                                        غیرفعال
                                    </span>

                                @endif

                            </td>


                            {{-- Created --}}
                            <td class="px-5 py-4">

                                <div class="text-sm font-bold text-[var(--color-text-primary)]">
                                    {{ $customer->created_at->locale('fa')->translatedFormat('Y/m/d') }}
                                </div>

                                <div class="mt-1 text-xs text-[var(--color-text-muted)]">
                                    {{ $customer->created_at->diffForHumans() }}
                                </div>

                            </td>


                            {{-- Actions --}}
                            <td class="px-5 py-4">

                                <a href="{{ route('admin.customers.show', $customer) }}"
                                   class="rounded-lg bg-[var(--color-brand-50)] px-4 py-2 text-xs font-bold text-[var(--color-brand-700)] hover:bg-[var(--color-brand-100)]">
                                    مشاهده
                                </a>

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
                                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM4 21a8 8 0 0116 0"/>
                                        </svg>
                                    </div>

                                    <h3 class="mt-4 text-lg font-black text-[var(--color-text-primary)]">
                                        مشتری‌ای پیدا نشد
                                    </h3>

                                    <p class="mt-2 text-sm text-[var(--color-text-secondary)]">
                                        مشتری‌ای مطابق فیلترهای فعلی وجود ندارد.
                                    </p>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>


            @if($customers->hasPages())

                <div class="border-t border-[var(--color-border)] px-5 py-4">
                    {{ $customers->withQueryString()->links() }}
                </div>

            @endif

        </div>

    </div>
@endsection
