@extends('layouts.admin')

@section('title', 'نوشته‌ها')

@section('content')
    @php
        $statusLabels = [
            'draft' => 'پیش‌نویس',
            'published' => 'منتشر شده',
        ];

        $statusClasses = [
            'draft' => 'bg-amber-50 text-amber-700',
            'published' => 'bg-emerald-50 text-emerald-700',
        ];
    @endphp

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-black text-[var(--color-text-primary)]">
                    نوشته‌ها
                </h1>

                <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                    مدیریت مقالات و محتوای وبلاگ
                </p>
            </div>

            <a href="{{ route('admin.posts.create') }}"
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

                افزودن نوشته
            </a>
        </div>


        {{-- Filters --}}
        <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

            <form action="{{ route('admin.posts.index') }}"
                  method="GET"
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
                           placeholder="عنوان، slug یا خلاصه مقاله..."
                           class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none transition placeholder:text-[var(--color-text-muted)] focus:border-[var(--color-brand-600)]">
                </div>


                {{-- Category --}}
                <div>
                    <label for="blog_category_id"
                           class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                        دسته‌بندی
                    </label>

                    <select id="blog_category_id"
                            name="blog_category_id"
                            class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none focus:border-[var(--color-brand-600)]">

                        <option value="">
                            همه دسته‌بندی‌ها
                        </option>

                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                    @selected((string) request('blog_category_id') === (string) $category->id)>
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
                            class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none focus:border-[var(--color-brand-600)]">

                        <option value="">
                            همه
                        </option>

                        @foreach($statusLabels as $status => $label)
                            <option value="{{ $status }}"
                                    @selected(request('status') === $status)>
                            {{ $label }}
                            </option>
                        @endforeach

                    </select>
                </div>


                {{-- Actions --}}
                <div class="flex gap-3 lg:col-span-4">

                    <button type="submit"
                            class="rounded-xl bg-[var(--color-brand-600)] px-5 py-3 text-sm font-bold text-white transition hover:bg-[var(--color-brand-700)]">
                        اعمال فیلتر
                    </button>

                    <a href="{{ route('admin.posts.index') }}"
                       class="rounded-xl border border-[var(--color-border)] bg-[var(--color-neutral-50)] px-5 py-3 text-sm font-bold text-[var(--color-text-primary)] transition hover:bg-[var(--color-neutral-100)]">
                        پاک کردن
                    </a>

                </div>

            </form>

        </div>


        {{-- Posts --}}
        <div class="overflow-hidden rounded-2xl border border-[var(--color-border)] bg-white shadow-sm">

            <div class="overflow-x-auto">
                <table class="min-w-full text-right">

                    <thead class="border-b border-[var(--color-border)] bg-[var(--color-neutral-50)]">

                    <tr>
                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            نوشته
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            دسته‌بندی
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            نویسنده
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            وضعیت
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            بازدید
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            تاریخ
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            عملیات
                        </th>
                    </tr>

                    </thead>


                    <tbody class="divide-y divide-[var(--color-border)]">

                    @forelse($posts as $post)

                        <tr class="transition hover:bg-[var(--color-neutral-50)]">

                            {{-- Post --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="h-14 w-20 flex-shrink-0 overflow-hidden rounded-xl border border-[var(--color-border)] bg-[var(--color-neutral-50)]">

                                        @if($post->featured_image)
                                            <img src="{{ asset('storage/' . $post->featured_image) }}"
                                                 alt="{{ $post->title }}"
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
                                                          d="M4 16l4.5-4.5a2 2 0 012.828 0L16 16m-2-2l1.5-1.5a2 2 0 012.828 0L20 15m-2-9h.01M5 19h14a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                                                </svg>
                                            </div>
                                        @endif

                                    </div>


                                    <div class="min-w-0">

                                        <div class="line-clamp-2 font-bold text-[var(--color-text-primary)]">
                                            {{ $post->title }}
                                        </div>

                                        <div class="mt-1 text-xs text-[var(--color-text-muted)]"
                                             dir="ltr">
                                            {{ $post->slug }}
                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- Category --}}
                            <td class="px-5 py-4">
                                <span class="text-sm text-[var(--color-text-secondary)]">
                                    {{ $post->category?->name ?? 'بدون دسته‌بندی' }}
                                </span>
                            </td>


                            {{-- Author --}}
                            <td class="px-5 py-4">
                                <span class="text-sm font-bold text-[var(--color-text-primary)]">
                                    {{ $post->author?->name ?? 'نامشخص' }}
                                </span>
                            </td>


                            {{-- Status --}}
                            <td class="px-5 py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $statusClasses[$post->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $statusLabels[$post->status] ?? $post->status }}
                                </span>
                            </td>


                            {{-- Views --}}
                            <td class="px-5 py-4">
                                <span class="font-bold text-[var(--color-text-primary)]">
                                    {{ number_format($post->view_count) }}
                                </span>
                            </td>


                            {{-- Date --}}
                            <td class="px-5 py-4">

                                @if($post->published_at)
                                    <div class="text-sm font-bold text-[var(--color-text-primary)]">
                                        {{ $post->published_at->locale('fa')->translatedFormat('Y/m/d') }}
                                    </div>

                                    <div class="mt-1 text-xs text-[var(--color-text-muted)]">
                                        انتشار
                                    </div>
                                @else
                                    <div class="text-sm text-[var(--color-text-secondary)]">
                                        {{ $post->created_at->locale('fa')->translatedFormat('Y/m/d') }}
                                    </div>

                                    <div class="mt-1 text-xs text-[var(--color-text-muted)]">
                                        ایجاد
                                    </div>
                                @endif

                            </td>


                            {{-- Actions --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center gap-2">

                                    <a href="{{ route('admin.posts.edit', $post) }}"
                                       class="rounded-lg border border-[var(--color-border)] px-3 py-2 text-xs font-bold text-[var(--color-text-primary)] hover:bg-[var(--color-neutral-50)]">
                                        ویرایش
                                    </a>

                                    <form action="{{ route('admin.posts.destroy', $post) }}"
                                          method="POST"
                                          onsubmit="return confirm('آیا از حذف این نوشته مطمئن هستید؟');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="rounded-lg bg-red-50 px-3 py-2 text-xs font-bold text-red-700 hover:bg-red-100">
                                            حذف
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">

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
                                                  d="M12 20h9"/>
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M16.5 3.5a2.121 2.121 0 013 3L8 18l-4 1 1-4L16.5 3.5z"/>
                                        </svg>
                                    </div>

                                    <h3 class="mt-4 text-lg font-black text-[var(--color-text-primary)]">
                                        نوشته‌ای پیدا نشد
                                    </h3>

                                    <p class="mt-2 text-sm text-[var(--color-text-secondary)]">
                                        هنوز نوشته‌ای ثبت نشده یا فیلترهای فعلی نتیجه‌ای ندارند.
                                    </p>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>
            </div>


            @if($posts->hasPages())
                <div class="border-t border-[var(--color-border)] px-5 py-4">
                    {{ $posts->withQueryString()->links() }}
                </div>
            @endif

        </div>

    </div>
@endsection
