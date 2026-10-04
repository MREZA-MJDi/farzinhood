@extends('layouts.admin')

@section('title', 'اعضای خبرنامه')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-black text-[var(--color-text-primary)]">
                اعضای خبرنامه
            </h1>

            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                مدیریت مشترکین خبرنامه فروشگاه
            </p>
        </div>


        {{-- Filters --}}
        <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

            <form action="{{ route('admin.newsletter.index') }}"
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
                           placeholder="ایمیل مشترک..."
                           dir="ltr"
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

                    <a href="{{ route('admin.newsletter.index') }}"
                       class="rounded-xl border border-[var(--color-border)] bg-[var(--color-neutral-50)] px-5 py-3 text-sm font-bold text-[var(--color-text-primary)] hover:bg-[var(--color-neutral-100)]">
                        پاک کردن
                    </a>

                </div>

            </form>

        </div>


        {{-- Subscribers --}}
        <div class="overflow-hidden rounded-2xl border border-[var(--color-border)] bg-white shadow-sm">

            <div class="overflow-x-auto">

                <table class="min-w-full text-right">

                    <thead class="border-b border-[var(--color-border)] bg-[var(--color-neutral-50)]">

                    <tr>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            ایمیل
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            وضعیت
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            تاریخ عضویت
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            آخرین بروزرسانی
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            عملیات
                        </th>

                    </tr>

                    </thead>


                    <tbody class="divide-y divide-[var(--color-border)]">

                    @forelse($subscribers as $subscriber)

                        <tr class="transition hover:bg-[var(--color-neutral-50)]">

                            {{-- Email --}}
                            <td class="px-5 py-4">

                                <div class="font-bold text-[var(--color-text-primary)]"
                                     dir="ltr">
                                    {{ $subscriber->email }}
                                </div>

                            </td>


                            {{-- Status --}}
                            <td class="px-5 py-4">

                                @if($subscriber->is_active)

                                    <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                                        فعال
                                    </span>

                                @else

                                    <span class="inline-flex rounded-full bg-red-50 px-3 py-1 text-xs font-bold text-red-700">
                                        غیرفعال
                                    </span>

                                @endif

                            </td>


                            {{-- Subscribed --}}
                            <td class="px-5 py-4">

                                @if($subscriber->subscribed_at)

                                    <div class="text-sm font-bold text-[var(--color-text-primary)]">
                                        {{ $subscriber->subscribed_at->locale('fa')->translatedFormat('Y/m/d') }}
                                    </div>

                                    <div class="mt-1 text-xs text-[var(--color-text-muted)]">
                                        {{ $subscriber->subscribed_at->locale('fa')->translatedFormat('H:i') }}
                                    </div>

                                @else
                                    —
                                @endif

                            </td>


                            {{-- Updated --}}
                            <td class="px-5 py-4">

                                <div class="text-sm text-[var(--color-text-secondary)]">
                                    {{ $subscriber->updated_at->locale('fa')->translatedFormat('Y/m/d') }}
                                </div>

                                <div class="mt-1 text-xs text-[var(--color-text-muted)]">
                                    {{ $subscriber->updated_at->locale('fa')->translatedFormat('H:i') }}
                                </div>

                            </td>


                            {{-- Actions --}}
                            <td class="px-5 py-4">

                                <div class="flex flex-wrap items-center gap-2">

                                    @if($subscriber->is_active)

                                        <form action="{{ route('admin.newsletter.deactivate', $subscriber) }}"
                                              method="POST">

                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="rounded-lg bg-amber-50 px-3 py-2 text-xs font-bold text-amber-700 hover:bg-amber-100">
                                                غیرفعال
                                            </button>

                                        </form>

                                    @else

                                        <form action="{{ route('admin.newsletter.activate', $subscriber) }}"
                                              method="POST">

                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="rounded-lg bg-emerald-50 px-3 py-2 text-xs font-bold text-emerald-700 hover:bg-emerald-100">
                                                فعال کردن
                                            </button>

                                        </form>

                                    @endif


                                    <form action="{{ route('admin.newsletter.destroy', $subscriber) }}"
                                          method="POST"
                                          onsubmit="return confirm('آیا از حذف این مشترک مطمئن هستید؟');">

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

                            <td colspan="5"
                                class="px-5 py-16 text-center">

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
                                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 8V8a2 2 0 012-2h16a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                                        </svg>

                                    </div>

                                    <h3 class="mt-4 text-lg font-black text-[var(--color-text-primary)]">
                                        مشترکی وجود ندارد
                                    </h3>

                                    <p class="mt-2 text-sm text-[var(--color-text-secondary)]">
                                        هنوز کسی در خبرنامه عضو نشده است.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>


            @if($subscribers->hasPages())

                <div class="border-t border-[var(--color-border)] px-5 py-4">
                    {{ $subscribers->withQueryString()->links() }}
                </div>

            @endif

        </div>

    </div>
@endsection
