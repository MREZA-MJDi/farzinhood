@extends('layouts.admin')

@section('title', 'نظرات کاربران')

@section('content')
    @php
        $statusLabels = [
            'pending' => 'در انتظار بررسی',
            'approved' => 'تأیید شده',
            'rejected' => 'رد شده',
        ];

        $statusClasses = [
            'pending' => 'bg-amber-50 text-amber-700',
            'approved' => 'bg-emerald-50 text-emerald-700',
            'rejected' => 'bg-red-50 text-red-700',
        ];
    @endphp

    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-black text-[var(--color-text-primary)]">
                نظرات کاربران
            </h1>

            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                مدیریت، بررسی و تأیید نظرات محصولات
            </p>
        </div>

        {{-- Filters --}}
        <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

            <form action="{{ route('admin.reviews.index') }}"
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
                           placeholder="متن نظر، نام کاربر یا نام محصول..."
                           class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none transition placeholder:text-[var(--color-text-muted)] focus:border-[var(--color-brand-600)]">
                </div>

                {{-- Status --}}
                <div>
                    <label for="status"
                           class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                        وضعیت
                    </label>

                    <select id="status"
                            name="status"
                            class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm outline-none focus:border-[var(--color-brand-600)]">

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

                {{-- Rating --}}
                <div>
                    <label for="rating"
                           class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                        امتیاز
                    </label>

                    <select id="rating"
                            name="rating"
                            class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm outline-none focus:border-[var(--color-brand-600)]">

                        <option value="">
                            همه امتیازها
                        </option>

                        @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}"
                                    @selected((string) request('rating') === (string) $i)>
                            {{ $i }} ستاره
                            </option>
                        @endfor

                    </select>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 lg:col-span-4">

                    <button type="submit"
                            class="rounded-xl bg-[var(--color-brand-600)] px-5 py-3 text-sm font-bold text-white transition hover:bg-[var(--color-brand-700)]">
                        اعمال فیلتر
                    </button>

                    <a href="{{ route('admin.reviews.index') }}"
                       class="rounded-xl border border-[var(--color-border)] bg-[var(--color-neutral-50)] px-5 py-3 text-sm font-bold text-[var(--color-text-primary)] hover:bg-[var(--color-neutral-100)]">
                        پاک کردن
                    </a>

                </div>

            </form>

        </div>


        {{-- Reviews --}}
        <div class="overflow-hidden rounded-2xl border border-[var(--color-border)] bg-white shadow-sm">

            <div class="divide-y divide-[var(--color-border)]">

                @forelse($reviews as $review)

                    <div class="p-5">

                        {{-- Top --}}
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">

                            <div class="min-w-0">

                                {{-- Product --}}
                                <div class="flex flex-wrap items-center gap-2">

                                    <span class="text-sm font-black text-[var(--color-text-primary)]">
                                        {{ $review->product?->name ?? 'محصول حذف شده' }}
                                    </span>

                                    @if($review->order_id)
                                        <span class="rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-bold text-blue-700">
                                            خریداری شده
                                        </span>
                                    @endif

                                </div>

                                {{-- User --}}
                                <div class="mt-2 text-xs text-[var(--color-text-muted)]">
                                    توسط:
                                    <span class="font-bold text-[var(--color-text-secondary)]">
                                        {{ $review->user?->name ?? 'کاربر حذف شده' }}
                                    </span>

                                    @if($review->user?->email)
                                        <span class="mx-1">
                                            •
                                        </span>

                                        <span dir="ltr">
                                            {{ $review->user->email }}
                                        </span>
                                    @endif
                                </div>

                            </div>


                            {{-- Status --}}
                            <div class="flex flex-wrap items-center gap-2">

                                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $statusClasses[$review->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $statusLabels[$review->status] ?? $review->status }}
                                </span>

                                <span class="text-xs text-[var(--color-text-muted)]">
                                    {{ $review->created_at->locale('fa')->translatedFormat('Y/m/d H:i') }}
                                </span>

                            </div>

                        </div>


                        {{-- Rating --}}
                        <div class="mt-4 flex items-center gap-1">

                            @for($star = 1; $star <= 5; $star++)
                                @if($star <= $review->rating)
                                    <span class="text-lg text-amber-500">
                                        ★
                                    </span>
                                @else
                                    <span class="text-lg text-[var(--color-neutral-300)]">
                                        ★
                                    </span>
                                @endif
                            @endfor

                            <span class="mr-2 text-xs font-bold text-[var(--color-text-secondary)]">
                                {{ $review->rating }}/5
                            </span>

                        </div>


                        {{-- Content --}}
                        <div class="mt-4 rounded-xl bg-[var(--color-neutral-50)] p-4">

                            @if($review->title)
                                <h3 class="font-black text-[var(--color-text-primary)]">
                                    {{ $review->title }}
                                </h3>
                            @endif

                            <p class="{{ $review->title ? 'mt-2' : '' }} whitespace-pre-line text-sm leading-7 text-[var(--color-text-secondary)]">
                                {{ $review->body }}
                            </p>

                        </div>


                        {{-- Actions --}}
                        <div class="mt-4 flex flex-wrap items-center gap-2">

                            @if($review->status !== 'approved')
                                <form action="{{ route('admin.reviews.status', $review) }}"
                                      method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <input type="hidden"
                                           name="status"
                                           value="approved">

                                    <button type="submit"
                                            class="rounded-xl bg-emerald-50 px-4 py-2.5 text-xs font-bold text-emerald-700 transition hover:bg-emerald-100">
                                        تأیید نظر
                                    </button>
                                </form>
                            @endif


                            @if($review->status !== 'rejected')
                                <form action="{{ route('admin.reviews.status', $review) }}"
                                      method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <input type="hidden"
                                           name="status"
                                           value="rejected">

                                    <button type="submit"
                                            class="rounded-xl bg-red-50 px-4 py-2.5 text-xs font-bold text-red-700 transition hover:bg-red-100">
                                        رد نظر
                                    </button>
                                </form>
                            @endif


                            @if($review->status !== 'pending')
                                <form action="{{ route('admin.reviews.status', $review) }}"
                                      method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <input type="hidden"
                                           name="status"
                                           value="pending">

                                    <button type="submit"
                                            class="rounded-xl border border-[var(--color-border)] bg-white px-4 py-2.5 text-xs font-bold text-[var(--color-text-primary)] transition hover:bg-[var(--color-neutral-50)]">
                                        بازگشت به بررسی
                                    </button>
                                </form>
                            @endif


                            <form action="{{ route('admin.reviews.destroy', $review) }}"
                                  method="POST"
                                  onsubmit="return confirm('آیا از حذف این نظر مطمئن هستید؟');">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="rounded-xl px-4 py-2.5 text-xs font-bold text-red-700 transition hover:bg-red-50">
                                    حذف
                                </button>

                            </form>

                        </div>

                    </div>

                @empty

                    <div class="px-5 py-16 text-center">

                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[var(--color-neutral-100)]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-8 w-8 text-[var(--color-text-muted)]"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.5">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M8 10h8m-8 4h5m-9 5l-3 3V6a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H8z"/>
                            </svg>
                        </div>

                        <h3 class="mt-4 text-lg font-black text-[var(--color-text-primary)]">
                            نظری ثبت نشده است
                        </h3>

                        <p class="mt-2 text-sm text-[var(--color-text-secondary)]">
                            هنوز نظری برای بررسی وجود ندارد.
                        </p>

                    </div>

                @endforelse

            </div>


            {{-- Pagination --}}
            @if($reviews->hasPages())
                <div class="border-t border-[var(--color-border)] px-5 py-4">
                    {{ $reviews->withQueryString()->links() }}
                </div>
            @endif

        </div>

    </div>
@endsection
