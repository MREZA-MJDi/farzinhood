@extends('layouts.admin')

@section('title', 'دسته‌بندی‌های وبلاگ')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-black text-[var(--color-text-primary)]">
                    دسته‌بندی‌های وبلاگ
                </h1>

                <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                    مدیریت دسته‌بندی‌های بخش وبلاگ
                </p>
            </div>

            <a href="{{ route('admin.blog-categories.create') }}"
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

                افزودن دسته‌بندی
            </a>
        </div>


        {{-- Filters --}}
        <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

            <form action="{{ route('admin.blog-categories.index') }}"
                  method="GET"
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
                           placeholder="نام یا slug دسته‌بندی..."
                           class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none transition placeholder:text-[var(--color-text-muted)] focus:border-[var(--color-brand-600)]">
                </div>

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

                <div class="flex gap-3 md:col-span-3">

                    <button type="submit"
                            class="rounded-xl bg-[var(--color-brand-600)] px-5 py-3 text-sm font-bold text-white transition hover:bg-[var(--color-brand-700)]">
                        اعمال فیلتر
                    </button>

                    <a href="{{ route('admin.blog-categories.index') }}"
                       class="rounded-xl border border-[var(--color-border)] bg-[var(--color-neutral-50)] px-5 py-3 text-sm font-bold text-[var(--color-text-primary)] transition hover:bg-[var(--color-neutral-100)]">
                        پاک کردن
                    </a>

                </div>

            </form>

        </div>


        {{-- Table --}}
        <div class="overflow-hidden rounded-2xl border border-[var(--color-border)] bg-white shadow-sm">

            <div class="overflow-x-auto">
                <table class="min-w-full text-right">

                    <thead class="border-b border-[var(--color-border)] bg-[var(--color-neutral-50)]">
                    <tr>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            دسته‌بندی
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            نوشته‌ها
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            ترتیب
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

                    @forelse($categories as $category)

                        <tr class="transition hover:bg-[var(--color-neutral-50)]">

                            {{-- Category --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="h-14 w-14 flex-shrink-0 overflow-hidden rounded-xl border border-[var(--color-border)] bg-[var(--color-neutral-50)]">

                                        @if($category->image)
                                            <img src="{{ asset('storage/' . $category->image) }}"
                                                 alt="{{ $category->name }}"
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
                                                          d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                                                </svg>
                                            </div>
                                        @endif

                                    </div>

                                    <div class="min-w-0">

                                        <div class="font-bold text-[var(--color-text-primary)]">
                                            {{ $category->name }}
                                        </div>

                                        <div class="mt-1 text-xs text-[var(--color-text-muted)]"
                                             dir="ltr">
                                            {{ $category->slug }}
                                        </div>

                                        @if($category->description)
                                            <div class="mt-1 max-w-md truncate text-xs text-[var(--color-text-secondary)]">
                                                {{ $category->description }}
                                            </div>
                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- Posts --}}
                            <td class="px-5 py-4">

                                <span class="inline-flex rounded-full bg-[var(--color-brand-50)] px-3 py-1 text-xs font-bold text-[var(--color-brand-700)]">
                                    {{ number_format($category->posts_count) }}
                                    نوشته
                                </span>

                            </td>


                            {{-- Sort --}}
                            <td class="px-5 py-4">
                                <span class="text-sm font-bold text-[var(--color-text-primary)]">
                                    {{ number_format($category->sort_order) }}
                                </span>
                            </td>


                            {{-- Status --}}
                            <td class="px-5 py-4">

                                @if($category->is_active)
                                    <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                                        فعال
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-red-50 px-3 py-1 text-xs font-bold text-red-700">
                                        غیرفعال
                                    </span>
                                @endif

                            </td>


                            {{-- Actions --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center gap-2">

                                    <a href="{{ route('admin.blog-categories.edit', $category) }}"
                                       class="rounded-lg border border-[var(--color-border)] px-3 py-2 text-xs font-bold text-[var(--color-text-primary)] transition hover:bg-[var(--color-neutral-50)]">
                                        ویرایش
                                    </a>

                                    <form action="{{ route('admin.blog-categories.destroy', $category) }}"
                                          method="POST"
                                          onsubmit="return confirm('آیا از حذف این دسته‌بندی مطمئن هستید؟');">

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
                            <td colspan="5" class="px-5 py-16 text-center">

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
                                                  d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                                        </svg>

                                    </div>

                                    <h3 class="mt-4 text-lg font-black text-[var(--color-text-primary)]">
                                        دسته‌بندی‌ای وجود ندارد
                                    </h3>

                                    <p class="mt-2 text-sm text-[var(--color-text-secondary)]">
                                        هنوز دسته‌بندی‌ای برای وبلاگ ایجاد نشده است.
                                    </p>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>
            </div>


            @if($categories->hasPages())
                <div class="border-t border-[var(--color-border)] px-5 py-4">
                    {{ $categories->withQueryString()->links() }}
                </div>
            @endif

        </div>

    </div>
@endsection
