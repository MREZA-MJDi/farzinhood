@extends('layouts.admin')

@section('title', 'پیام‌های تماس')

@section('content')
    @php
        $statusLabels = [
            'new' => 'جدید',
            'read' => 'خوانده شده',
            'replied' => 'پاسخ داده شده',
            'closed' => 'بسته شده',
        ];

        $statusClasses = [
            'new' => 'bg-red-50 text-red-700',
            'read' => 'bg-blue-50 text-blue-700',
            'replied' => 'bg-emerald-50 text-emerald-700',
            'closed' => 'bg-gray-100 text-gray-700',
        ];
    @endphp

    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-black text-[var(--color-text-primary)]">
                پیام‌های تماس
            </h1>

            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                مدیریت پیام‌های ارسال شده از فرم تماس با ما
            </p>
        </div>


        {{-- Filters --}}
        <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

            <form action="{{ route('admin.contact-messages.index') }}"
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
                           placeholder="نام، ایمیل، موضوع یا متن پیام..."
                           class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none placeholder:text-[var(--color-text-muted)] focus:border-[var(--color-brand-600)]">

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

                        @foreach($statusLabels as $status => $label)
                            <option value="{{ $status }}"
                                    @selected(request('status') === $status)>
                            {{ $label }}
                            </option>
                        @endforeach

                    </select>

                </div>


                <div class="flex gap-3 md:col-span-3">

                    <button type="submit"
                            class="rounded-xl bg-[var(--color-brand-600)] px-5 py-3 text-sm font-bold text-white hover:bg-[var(--color-brand-700)]">
                        اعمال فیلتر
                    </button>

                    <a href="{{ route('admin.contact-messages.index') }}"
                       class="rounded-xl border border-[var(--color-border)] bg-[var(--color-neutral-50)] px-5 py-3 text-sm font-bold text-[var(--color-text-primary)] hover:bg-[var(--color-neutral-100)]">
                        پاک کردن
                    </a>

                </div>

            </form>

        </div>


        {{-- Messages --}}
        <div class="overflow-hidden rounded-2xl border border-[var(--color-border)] bg-white shadow-sm">

            <div class="overflow-x-auto">

                <table class="min-w-full text-right">

                    <thead class="border-b border-[var(--color-border)] bg-[var(--color-neutral-50)]">

                    <tr>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            فرستنده
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            موضوع
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            وضعیت
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

                    @forelse($messages as $message)

                        <tr class="transition hover:bg-[var(--color-neutral-50)]">

                            {{-- Sender --}}
                            <td class="px-5 py-4">

                                <div class="font-bold text-[var(--color-text-primary)]">
                                    {{ $message->name }}
                                </div>

                                <div class="mt-1 text-xs text-[var(--color-text-secondary)]"
                                     dir="ltr">
                                    {{ $message->email }}
                                </div>

                                @if($message->phone)
                                    <div class="mt-1 text-xs text-[var(--color-text-muted)]"
                                         dir="ltr">
                                        {{ $message->phone }}
                                    </div>
                                @endif

                            </td>


                            {{-- Subject --}}
                            <td class="px-5 py-4">

                                <div class="max-w-sm font-bold text-[var(--color-text-primary)]">
                                    {{ $message->subject }}
                                </div>

                                <div class="mt-1 line-clamp-2 max-w-md text-xs text-[var(--color-text-secondary)]">
                                    {{ $message->message }}
                                </div>

                            </td>


                            {{-- Status --}}
                            <td class="px-5 py-4">

                                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $statusClasses[$message->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $statusLabels[$message->status] ?? $message->status }}
                                </span>

                            </td>


                            {{-- Date --}}
                            <td class="px-5 py-4">

                                <div class="text-sm font-bold text-[var(--color-text-primary)]">
                                    {{ $message->created_at->locale('fa')->translatedFormat('Y/m/d') }}
                                </div>

                                <div class="mt-1 text-xs text-[var(--color-text-muted)]">
                                    {{ $message->created_at->locale('fa')->translatedFormat('H:i') }}
                                </div>

                            </td>


                            {{-- Actions --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center gap-2">

                                    <a href="{{ route('admin.contact-messages.show', $message) }}"
                                       class="rounded-lg bg-[var(--color-brand-50)] px-4 py-2 text-xs font-bold text-[var(--color-brand-700)] hover:bg-[var(--color-brand-100)]">
                                        مشاهده
                                    </a>

                                    <form action="{{ route('admin.contact-messages.destroy', $message) }}"
                                          method="POST"
                                          onsubmit="return confirm('آیا از حذف این پیام مطمئن هستید؟');">

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
                                                  d="M21.75 8.25l-9.25 6-9.25-6M3 19.5h18a1.5 1.5 0 001.5-1.5V6A1.5 1.5 0 0019 4.5H5A1.5 1.5 0 003.5 6v12A1.5 1.5 0 005 19.5z"/>
                                        </svg>

                                    </div>

                                    <h3 class="mt-4 text-lg font-black text-[var(--color-text-primary)]">
                                        پیامی وجود ندارد
                                    </h3>

                                    <p class="mt-2 text-sm text-[var(--color-text-secondary)]">
                                        هنوز پیامی از طریق فرم تماس دریافت نشده است.
                                    </p>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>


            @if($messages->hasPages())
                <div class="border-t border-[var(--color-border)] px-5 py-4">
                    {{ $messages->withQueryString()->links() }}
                </div>
            @endif

        </div>

    </div>
@endsection
