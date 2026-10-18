@php
    $previewFallbackTitle = (string) ($fallbackTitle ?? '');
    $previewFallbackDescription = (string) ($fallbackDescription ?? '');
    $previewFallbackUrl = (string) ($fallbackUrl ?? '');
    $previewTitle = (string) old('meta_title', $previewFallbackTitle);
    $previewDescription = (string) old('meta_description', $previewFallbackDescription);
    $previewCanonical = (string) old('canonical_url', $previewFallbackUrl);
    $previewNoindex = (bool) old('noindex', $fallbackNoindex ?? false);
@endphp

<section
    data-seo-preview
    data-fallback-title="{{ $previewFallbackTitle }}"
    data-fallback-description="{{ $previewFallbackDescription }}"
    data-fallback-url="{{ $previewFallbackUrl }}"
    class="rounded-2xl border border-[var(--color-border)] bg-[var(--color-neutral-50)] p-5"
    aria-labelledby="seo-preview-heading"
>
    <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 id="seo-preview-heading" class="text-sm font-black text-[var(--color-text-primary)]">پیش‌نمایش زنده نتیجه جستجو</h3>
            <p class="mt-1 text-xs leading-5 text-[var(--color-text-muted)]">این نمونه با فیلدهای همین فرم تغییر می‌کند؛ ظاهر نهایی در موتور جستجو ممکن است متفاوت باشد.</p>
        </div>
        <span data-seo-preview-robots class="inline-flex w-fit rounded-full border border-[var(--color-border)] bg-white px-3 py-1 text-[11px] font-bold text-[var(--color-text-secondary)]">
            {{ $previewNoindex ? 'noindex, follow' : 'index, follow' }}
        </span>
    </div>

    <div class="max-w-2xl rounded-xl border border-[var(--color-border)] bg-white p-4 sm:p-5">
        <p data-seo-preview-url class="truncate text-xs text-[#188038]" dir="ltr">
            {{ $previewCanonical ?: 'URL واقعی صفحه پس از ذخیره مشخص می‌شود' }}
        </p>
        <p data-seo-preview-title class="mt-2 line-clamp-2 text-lg font-medium leading-7 text-[#1a0dab]">
            {{ $previewTitle ?: 'عنوان صفحه در نتایج جستجو' }}
        </p>
        <p data-seo-preview-description class="mt-2 line-clamp-3 text-sm leading-6 text-[#4d5156]">
            {{ $previewDescription ?: 'وقتی توضیحات متا را وارد کنید، پیش‌نمایش آن اینجا نشان داده می‌شود.' }}
        </p>
    </div>

    <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
        <div class="rounded-xl border border-[var(--color-border)] bg-white p-3">
            <div class="flex items-center justify-between gap-3">
                <span class="text-xs font-bold text-[var(--color-text-secondary)]">طول عنوان</span>
                <span class="text-xs tabular-nums text-[var(--color-text-primary)]"><span data-seo-title-count>{{ mb_strlen($previewTitle) }}</span> نویسه</span>
            </div>
            <p data-seo-title-guidance class="mt-1 text-[11px] leading-5 text-[var(--color-text-muted)]">راهنمای تقریبی: حدود ۳۰ تا ۶۰ نویسه</p>
        </div>
        <div class="rounded-xl border border-[var(--color-border)] bg-white p-3">
            <div class="flex items-center justify-between gap-3">
                <span class="text-xs font-bold text-[var(--color-text-secondary)]">طول توضیحات</span>
                <span class="text-xs tabular-nums text-[var(--color-text-primary)]"><span data-seo-description-count>{{ mb_strlen($previewDescription) }}</span> نویسه</span>
            </div>
            <p data-seo-description-guidance class="mt-1 text-[11px] leading-5 text-[var(--color-text-muted)]">راهنمای تقریبی: حدود ۱۲۰ تا ۱۶۰ نویسه</p>
        </div>
    </div>
</section>
