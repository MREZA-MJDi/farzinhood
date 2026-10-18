@extends('layouts.admin')

@section('title', 'مدیریت SEO')

@section('content')
    <div class="space-y-6">
        <header class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-[var(--color-brand-600)]">Search visibility</p>
                <h1 class="mt-2 text-2xl font-black text-[var(--color-text-primary)] sm:text-3xl">مدیریت SEO</h1>
                <p class="mt-2 max-w-3xl text-sm leading-7 text-[var(--color-text-secondary)]">
                    وضعیت زیر از رکوردهای واقعی دیتابیس محاسبه می‌شود؛ این صفحه امتیاز ساختگی تولید نمی‌کند.
                    برای هر مورد ناقص، مستقیماً فرم ویرایش همان محتوا را باز کنید.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ $sitemapUrl }}" target="_blank" rel="noopener"
                   class="inline-flex min-h-11 items-center justify-center rounded-xl border border-[var(--color-border)] bg-white px-4 py-2 text-sm font-bold text-[var(--color-text-primary)] transition hover:bg-[var(--color-neutral-50)]">
                    مشاهده Sitemap
                    <span class="sr-only">(در تب جدید)</span>
                </a>
                <a href="{{ $robotsUrl }}" target="_blank" rel="noopener"
                   class="inline-flex min-h-11 items-center justify-center rounded-xl border border-[var(--color-border)] bg-white px-4 py-2 text-sm font-bold text-[var(--color-text-primary)] transition hover:bg-[var(--color-neutral-50)]">
                    مشاهده Robots.txt
                </a>
                <a href="{{ route('admin.settings.index') }}"
                   class="inline-flex min-h-11 items-center justify-center rounded-xl bg-[var(--color-brand-600)] px-4 py-2 text-sm font-bold text-white transition hover:bg-[var(--color-brand-700)]">
                    تنظیمات پیش‌فرض SEO
                </a>
            </div>
        </header>

        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="خلاصه ممیزی SEO">
            <article class="rounded-2xl border border-[var(--color-border)] bg-white p-5">
                <p class="text-sm font-semibold text-[var(--color-text-secondary)]">کل رکوردهای محتوایی فعال</p>
                <p class="mt-3 text-3xl font-black tabular-nums text-[var(--color-text-primary)]">{{ number_format($totals['records']) }}</p>
                <p class="mt-2 text-xs leading-5 text-[var(--color-text-muted)]">محصولات و دسته‌بندی‌های فعال و مقاله‌های منتشرشده</p>
            </article>

            <article class="rounded-2xl border border-[var(--color-border)] bg-white p-5">
                <p class="text-sm font-semibold text-[var(--color-text-secondary)]">قابل ایندکس از نظر تنظیم محتوا</p>
                <p class="mt-3 text-3xl font-black tabular-nums text-[var(--color-text-primary)]">{{ number_format($totals['indexable']) }}</p>
                <p class="mt-2 text-xs leading-5 text-[var(--color-text-muted)]">رکوردهای فعال که گزینه noindex برایشان روشن نیست</p>
            </article>

            <article class="rounded-2xl border border-[var(--color-border)] bg-white p-5">
                <p class="text-sm font-semibold text-[var(--color-text-secondary)]">محتوای دارای noindex</p>
                <p class="mt-3 text-3xl font-black tabular-nums text-[var(--color-text-primary)]">{{ number_format($totals['noindex']) }}</p>
                <p class="mt-2 text-xs leading-5 text-[var(--color-text-muted)]">این رکوردها از sitemap تولیدشده حذف می‌شوند</p>
            </article>

            <article class="rounded-2xl border border-[var(--color-border)] bg-white p-5">
                <p class="text-sm font-semibold text-[var(--color-text-secondary)]">رکوردهای ناقص SEO</p>
                <p class="mt-3 text-3xl font-black tabular-nums text-[var(--color-text-primary)]">{{ number_format($totals['missing_metadata']) }}</p>
                <p class="mt-2 text-xs leading-5 text-[var(--color-text-muted)]">
                    عنوان ناقص: {{ number_format($totals['missing_title']) }} · توضیحات ناقص: {{ number_format($totals['missing_description']) }}
                </p>
            </article>
        </section>

        <section class="overflow-hidden rounded-2xl border border-[var(--color-border)] bg-white">
            <div class="border-b border-[var(--color-border)] px-5 py-4 sm:px-6">
                <h2 class="text-lg font-black text-[var(--color-text-primary)]">وضعیت بر اساس نوع محتوا</h2>
                <p class="mt-1 text-sm text-[var(--color-text-secondary)]">اعداد با هر بار باز کردن صفحه از دیتابیس محاسبه می‌شوند.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-[760px] w-full text-right text-sm">
                    <thead class="bg-[var(--color-neutral-50)] text-xs font-bold text-[var(--color-text-secondary)]">
                        <tr>
                            <th class="px-5 py-3">نوع محتوا</th>
                            <th class="px-4 py-3">فعال / منتشرشده</th>
                            <th class="px-4 py-3">قابل ایندکس</th>
                            <th class="px-4 py-3">noindex</th>
                            <th class="px-4 py-3">عنوان ناقص</th>
                            <th class="px-4 py-3">توضیحات ناقص</th>
                            <th class="px-5 py-3">عملیات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--color-border)]">
                        @foreach($groups as $group)
                            <tr>
                                <td class="px-5 py-4 font-bold text-[var(--color-text-primary)]">{{ $group['label'] }}</td>
                                <td class="px-4 py-4 tabular-nums">{{ number_format($group['total']) }}</td>
                                <td class="px-4 py-4 tabular-nums">{{ number_format($group['indexable']) }}</td>
                                <td class="px-4 py-4 tabular-nums">{{ number_format($group['noindex']) }}</td>
                                <td class="px-4 py-4 tabular-nums">{{ number_format($group['missing_title']) }}</td>
                                <td class="px-4 py-4 tabular-nums">{{ number_format($group['missing_description']) }}</td>
                                <td class="px-5 py-4">
                                    <a href="{{ $group['manage_url'] }}" class="font-bold text-[var(--color-brand-700)] underline-offset-4 hover:underline">مدیریت محتوا</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-[var(--color-border)] bg-white">
            <div class="flex flex-col gap-2 border-b border-[var(--color-border)] px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div>
                    <h2 class="text-lg font-black text-[var(--color-text-primary)]">محتوای نیازمند تکمیل</h2>
                    <p class="mt-1 text-sm text-[var(--color-text-secondary)]">تا ۸ مورد اخیر از هر نوع محتوا نمایش داده می‌شود؛ عدد بالای صفحه شمارش کامل است.</p>
                </div>
                <span class="inline-flex w-fit rounded-full border border-[var(--color-border)] px-3 py-1 text-xs font-bold text-[var(--color-text-secondary)]">
                    {{ number_format($missingRecords->count()) }} مورد در این فهرست
                </span>
            </div>

            @if($missingRecords->isEmpty())
                <div class="px-6 py-12 text-center">
                    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50 text-emerald-700">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="m5 12 4 4L19 6" />
                        </svg>
                    </span>
                    <h3 class="mt-4 font-black text-[var(--color-text-primary)]">مورد ناقصی پیدا نشد</h3>
                    <p class="mt-2 text-sm text-[var(--color-text-secondary)]">در محتوای فعال فعلی، همه عنوان‌ها و توضیحات SEO تکمیل شده‌اند.</p>
                </div>
            @else
                <div class="divide-y divide-[var(--color-border)]">
                    @foreach($missingRecords as $record)
                        <article class="flex flex-col gap-4 px-5 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="rounded-lg bg-[var(--color-neutral-100)] px-2.5 py-1 text-[11px] font-bold text-[var(--color-text-secondary)]">{{ $record['type'] }}</span>
                                    @foreach($record['missing_fields'] as $field)
                                        <span class="rounded-lg border border-amber-200 bg-amber-50 px-2.5 py-1 text-[11px] font-bold text-amber-800">{{ $field }} لازم است</span>
                                    @endforeach
                                </div>
                                <h3 class="mt-2 truncate text-sm font-black text-[var(--color-text-primary)]">{{ $record['name'] }}</h3>
                                <a href="{{ $record['public_url'] }}" target="_blank" rel="noopener" class="mt-1 block truncate text-xs text-[var(--color-text-muted)] underline decoration-dotted underline-offset-4 hover:text-[var(--color-brand-700)]">
                                    {{ $record['public_url'] }}
                                </a>
                            </div>
                            <div class="flex shrink-0 flex-wrap gap-2">
                                <a href="{{ $record['public_url'] }}" target="_blank" rel="noopener"
                                   class="inline-flex min-h-10 items-center justify-center rounded-lg border border-[var(--color-border)] px-3 py-2 text-xs font-bold text-[var(--color-text-primary)] hover:bg-[var(--color-neutral-50)]">
                                    مشاهده صفحه
                                </a>
                                <a href="{{ $record['edit_url'] }}"
                                   class="inline-flex min-h-10 items-center justify-center rounded-lg bg-[var(--color-brand-600)] px-3 py-2 text-xs font-bold text-white hover:bg-[var(--color-brand-700)]">
                                    تکمیل SEO
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="rounded-2xl border border-[var(--color-border)] bg-[var(--color-neutral-50)] p-5 sm:p-6">
            <h2 class="font-black text-[var(--color-text-primary)]">این بررسی چه چیزی را تأیید می‌کند؟</h2>
            <p class="mt-2 max-w-4xl text-sm leading-7 text-[var(--color-text-secondary)]">
                وضعیت این صفحه فقط بر اساس دیتابیس و قواعد همین برنامه است. موفقیت sitemap یا تگ‌های meta، رتبه گرفتن در گوگل را تضمین نمی‌کند.
                پس از deploy، دامنه APP_URL و تنظیمات وب‌سرور را درست قرار دهید و خروجی Sitemap و Robots.txt را از دامنه واقعی بررسی کنید.
            </p>
        </section>
    </div>
@endsection
