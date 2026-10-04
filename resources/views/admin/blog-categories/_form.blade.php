@php
    $isEdit = $category !== null;
@endphp

<div class="space-y-6">

    {{-- Basic Information --}}
    <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

        <div class="mb-6">
            <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                اطلاعات اصلی
            </h2>

            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                اطلاعات اصلی دسته‌بندی وبلاگ را وارد کنید.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            {{-- Name --}}
            <div class="md:col-span-2">

                <label for="name"
                       class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                    نام دسته‌بندی
                    <span class="text-red-500">*</span>
                </label>

                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name', $category?->name) }}"
                       placeholder="مثلاً تکنولوژی"
                       class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none transition placeholder:text-[var(--color-text-muted)] focus:border-[var(--color-brand-600)] @error('name') border-red-400 @enderror">

                @error('name')
                <p class="mt-2 text-xs font-medium text-red-600">
                    {{ $message }}
                </p>
                @enderror

            </div>


            {{-- Slug --}}
            <div>

                <label for="slug"
                       class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                    Slug
                </label>

                <input type="text"
                       id="slug"
                       name="slug"
                       value="{{ old('slug', $category?->slug) }}"
                       dir="ltr"
                       placeholder="technology"
                       class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none transition placeholder:text-[var(--color-text-muted)] focus:border-[var(--color-brand-600)] @error('slug') border-red-400 @enderror">

                <p class="mt-2 text-xs text-[var(--color-text-muted)]">
                    در صورت خالی بودن، از نام ساخته می‌شود.
                </p>

                @error('slug')
                <p class="mt-2 text-xs font-medium text-red-600">
                    {{ $message }}
                </p>
                @enderror

            </div>


            {{-- Sort --}}
            <div>

                <label for="sort_order"
                       class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                    ترتیب نمایش
                </label>

                <input type="number"
                       min="0"
                       id="sort_order"
                       name="sort_order"
                       value="{{ old('sort_order', $category?->sort_order ?? 0) }}"
                       class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none transition focus:border-[var(--color-brand-600)] @error('sort_order') border-red-400 @enderror">

                @error('sort_order')
                <p class="mt-2 text-xs font-medium text-red-600">
                    {{ $message }}
                </p>
                @enderror

            </div>


            {{-- Description --}}
            <div class="md:col-span-2">

                <label for="description"
                       class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                    توضیحات
                </label>

                <textarea id="description"
                          name="description"
                          rows="6"
                          placeholder="توضیحات دسته‌بندی..."
                          class="w-full resize-y rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm leading-7 text-[var(--color-text-primary)] outline-none transition placeholder:text-[var(--color-text-muted)] focus:border-[var(--color-brand-600)] @error('description') border-red-400 @enderror">{{ old('description', $category?->description) }}</textarea>

                @error('description')
                <p class="mt-2 text-xs font-medium text-red-600">
                    {{ $message }}
                </p>
                @enderror

            </div>

        </div>

    </div>


    {{-- Image --}}
    <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

        <div class="mb-6">
            <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                تصویر
            </h2>

            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                تصویر دسته‌بندی وبلاگ را انتخاب کنید.
            </p>
        </div>


        @if($isEdit && $category->image)

            <div class="mb-5">

                <div class="mb-2 text-sm font-bold text-[var(--color-text-primary)]">
                    تصویر فعلی
                </div>

                <div class="h-40 w-40 overflow-hidden rounded-2xl border border-[var(--color-border)]">
                    <img src="{{ asset('storage/' . $category->image) }}"
                         alt="{{ $category->name }}"
                         class="h-full w-full object-cover">
                </div>

            </div>

        @endif


        <label for="image"
               class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
            {{ $isEdit ? 'تصویر جدید' : 'تصویر' }}
        </label>

        <input type="file"
               id="image"
               name="image"
               accept=".jpg,.jpeg,.png,.webp"
               class="block w-full rounded-xl border border-[var(--color-border)] bg-white text-sm text-[var(--color-text-secondary)] file:mr-0 file:border-0 file:bg-[var(--color-neutral-100)] file:px-5 file:py-3 file:text-sm file:font-bold file:text-[var(--color-text-primary)]">

        <p class="mt-2 text-xs text-[var(--color-text-muted)]">
            JPG, JPEG, PNG, WEBP — حداکثر ۵ مگابایت
        </p>

        @error('image')
        <p class="mt-2 text-xs font-medium text-red-600">
            {{ $message }}
        </p>
        @enderror

    </div>


    {{-- SEO --}}
    <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

        <div class="mb-6">
            <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                تنظیمات SEO
            </h2>

            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                اطلاعات سئوی این دسته‌بندی را مستقیماً از همین صفحه مدیریت کنید.
            </p>
        </div>

        <div class="space-y-5">

            {{-- Meta Title --}}
            <div>

                <label for="meta_title"
                       class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                    Meta Title
                </label>

                <input type="text"
                       id="meta_title"
                       name="meta_title"
                       value="{{ old('meta_title', $category?->meta_title) }}"
                       placeholder="عنوان سئو..."
                       class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none transition focus:border-[var(--color-brand-600)] @error('meta_title') border-red-400 @enderror">

                <p class="mt-2 text-xs text-[var(--color-text-muted)]">
                    عنوانی که برای موتورهای جستجو در نظر گرفته می‌شود.
                </p>

                @error('meta_title')
                <p class="mt-2 text-xs font-medium text-red-600">
                    {{ $message }}
                </p>
                @enderror

            </div>


            {{-- Meta Description --}}
            <div>

                <label for="meta_description"
                       class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                    Meta Description
                </label>

                <textarea id="meta_description"
                          name="meta_description"
                          rows="5"
                          placeholder="توضیحات سئو..."
                          class="w-full resize-y rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm leading-7 text-[var(--color-text-primary)] outline-none transition focus:border-[var(--color-brand-600)] @error('meta_description') border-red-400 @enderror">{{ old('meta_description', $category?->meta_description) }}</textarea>

                @error('meta_description')
                <p class="mt-2 text-xs font-medium text-red-600">
                    {{ $message }}
                </p>
                @enderror

            </div>


            {{-- Canonical --}}
            <div>

                <label for="canonical_url"
                       class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                    Canonical URL
                </label>

                <input type="url"
                       id="canonical_url"
                       name="canonical_url"
                       value="{{ old('canonical_url', $category?->canonical_url) }}"
                       dir="ltr"
                       placeholder="https://example.com/blog/categories/technology"
                       class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none transition focus:border-[var(--color-brand-600)] @error('canonical_url') border-red-400 @enderror">

                @error('canonical_url')
                <p class="mt-2 text-xs font-medium text-red-600">
                    {{ $message }}
                </p>
                @enderror

            </div>


            {{-- Noindex --}}
            <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-[var(--color-border)] bg-[var(--color-neutral-50)] p-4">

                <input type="checkbox"
                       name="noindex"
                       value="1"
                       @checked(old('noindex', $category?->noindex ?? false))
                class="h-5 w-5 rounded border-[var(--color-border)] text-[var(--color-brand-600)] focus:ring-[var(--color-brand-600)]">

                <span>

                    <span class="block text-sm font-bold text-[var(--color-text-primary)]">
                        عدم ایندکس صفحه
                    </span>

                    <span class="mt-1 block text-xs text-[var(--color-text-muted)]">
                        این صفحه در نتایج موتورهای جستجو ایندکس نشود.
                    </span>

                </span>

            </label>

            @error('noindex')
            <p class="text-xs font-medium text-red-600">
                {{ $message }}
            </p>
            @enderror

        </div>

    </div>


    {{-- Status --}}
    <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

        <div class="mb-5">
            <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                وضعیت
            </h2>
        </div>

        <label class="flex cursor-pointer items-center justify-between rounded-xl border border-[var(--color-border)] p-4 transition hover:bg-[var(--color-neutral-50)]">

            <div>

                <div class="text-sm font-bold text-[var(--color-text-primary)]">
                    دسته‌بندی فعال باشد
                </div>

                <div class="mt-1 text-xs text-[var(--color-text-muted)]">
                    دسته‌بندی در بخش وبلاگ قابل نمایش باشد.
                </div>

            </div>

            <input type="checkbox"
                   name="is_active"
                   value="1"
                   @checked(old('is_active', $category?->is_active ?? true))
            class="h-5 w-5 rounded border-[var(--color-border)] text-[var(--color-brand-600)] focus:ring-[var(--color-brand-600)]">

        </label>

        @error('is_active')
        <p class="mt-2 text-xs font-medium text-red-600">
            {{ $message }}
        </p>
        @enderror

    </div>


    {{-- Submit --}}
    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

        <a href="{{ route('admin.blog-categories.index') }}"
           class="rounded-xl border border-[var(--color-border)] bg-white px-6 py-3 text-center text-sm font-bold text-[var(--color-text-primary)] transition hover:bg-[var(--color-neutral-50)]">
            انصراف
        </a>

        <button type="submit"
                class="rounded-xl bg-[var(--color-brand-600)] px-7 py-3 text-sm font-bold text-white transition hover:bg-[var(--color-brand-700)]">
            {{ $submitLabel }}
        </button>

    </div>

</div>
