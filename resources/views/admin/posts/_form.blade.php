@php
    $isEdit = $post !== null;
@endphp

<div class="space-y-6">

    {{-- Basic Information --}}
    <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

        <div class="mb-6">
            <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                اطلاعات نوشته
            </h2>

            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                عنوان، دسته‌بندی و اطلاعات پایه مقاله.
            </p>
        </div>


        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            {{-- Title --}}
            <div class="md:col-span-2">

                <label for="title"
                       class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                    عنوان نوشته
                    <span class="text-red-500">*</span>
                </label>

                <input type="text"
                       id="title"
                       name="title"
                       value="{{ old('title', $post?->title) }}"
                       placeholder="عنوان مقاله..."
                       class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none transition placeholder:text-[var(--color-text-muted)] focus:border-[var(--color-brand-600)] @error('title') border-red-400 @enderror">

                @error('title')
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
                       value="{{ old('slug', $post?->slug) }}"
                       dir="ltr"
                       placeholder="article-slug"
                       class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none transition focus:border-[var(--color-brand-600)] @error('slug') border-red-400 @enderror">

                <p class="mt-2 text-xs text-[var(--color-text-muted)]">
                    در صورت خالی بودن از عنوان ساخته می‌شود.
                </p>

                @error('slug')
                <p class="mt-2 text-xs font-medium text-red-600">
                    {{ $message }}
                </p>
                @enderror

            </div>


            {{-- Category --}}
            <div>

                <label for="blog_category_id"
                       class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                    دسته‌بندی
                    <span class="text-red-500">*</span>
                </label>

                <select id="blog_category_id"
                        name="blog_category_id"
                        class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none focus:border-[var(--color-brand-600)] @error('blog_category_id') border-red-400 @enderror">

                    <option value="">
                        انتخاب دسته‌بندی
                    </option>

                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                                @selected((string) old('blog_category_id', $post?->blog_category_id) === (string) $category->id)>
                        {{ $category->name }}
                        </option>
                    @endforeach

                </select>

                @error('blog_category_id')
                <p class="mt-2 text-xs font-medium text-red-600">
                    {{ $message }}
                </p>
                @enderror

            </div>


            {{-- Excerpt --}}
            <div class="md:col-span-2">

                <label for="excerpt"
                       class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                    خلاصه مقاله
                </label>

                <textarea id="excerpt"
                          name="excerpt"
                          rows="4"
                          placeholder="خلاصه‌ای کوتاه از مقاله..."
                          class="w-full resize-y rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm leading-7 text-[var(--color-text-primary)] outline-none focus:border-[var(--color-brand-600)] @error('excerpt') border-red-400 @enderror">{{ old('excerpt', $post?->excerpt) }}</textarea>

                @error('excerpt')
                <p class="mt-2 text-xs font-medium text-red-600">
                    {{ $message }}
                </p>
                @enderror

            </div>

        </div>

    </div>


    {{-- Content --}}
    <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

        <div class="mb-6">
            <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                محتوای مقاله
            </h2>

            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                محتوای کامل مقاله را وارد کنید.
            </p>
        </div>

        <textarea id="content"
                  name="content"
                  rows="20"
                  placeholder="محتوای مقاله..."
                  class="w-full resize-y rounded-xl border border-[var(--color-border)] bg-white px-4 py-4 text-sm leading-8 text-[var(--color-text-primary)] outline-none focus:border-[var(--color-brand-600)] @error('content') border-red-400 @enderror">{{ old('content', $post?->content) }}</textarea>

        @error('content')
        <p class="mt-2 text-xs font-medium text-red-600">
            {{ $message }}
        </p>
        @enderror

    </div>


    {{-- Featured Image --}}
    <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

        <div class="mb-6">
            <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                تصویر شاخص
            </h2>

            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                تصویر اصلی مقاله که در لیست و صفحه مقاله نمایش داده می‌شود.
            </p>
        </div>


        @if($isEdit && $post->featured_image)

            <div class="mb-5">

                <div class="mb-2 text-sm font-bold text-[var(--color-text-primary)]">
                    تصویر فعلی
                </div>

                <div class="h-48 w-full max-w-md overflow-hidden rounded-2xl border border-[var(--color-border)]">
                    <img src="{{ asset('storage/' . $post->featured_image) }}"
                         alt="{{ $post->title }}"
                         class="h-full w-full object-cover">
                </div>

            </div>

        @endif


        <label for="featured_image"
               class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
            {{ $isEdit ? 'تصویر جدید' : 'تصویر' }}
        </label>

        <input type="file"
               id="featured_image"
               name="featured_image"
               accept=".jpg,.jpeg,.png,.webp"
               class="block w-full rounded-xl border border-[var(--color-border)] bg-white text-sm text-[var(--color-text-secondary)] file:mr-0 file:border-0 file:bg-[var(--color-neutral-100)] file:px-5 file:py-3 file:text-sm file:font-bold file:text-[var(--color-text-primary)]">

        <p class="mt-2 text-xs text-[var(--color-text-muted)]">
            JPG, JPEG, PNG, WEBP — حداکثر ۵ مگابایت
        </p>

        @error('featured_image')
        <p class="mt-2 text-xs font-medium text-red-600">
            {{ $message }}
        </p>
        @enderror

    </div>


    {{-- Publication --}}
    <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

        <div class="mb-6">
            <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                انتشار
            </h2>

            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                وضعیت انتشار و زمان انتشار مقاله.
            </p>
        </div>


        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            {{-- Status --}}
            <div>

                <label for="status"
                       class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                    وضعیت
                    <span class="text-red-500">*</span>
                </label>

                <select id="status"
                        name="status"
                        class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none focus:border-[var(--color-brand-600)] @error('status') border-red-400 @enderror">

                    <option value="draft"
                            @selected(old('status', $post?->status ?? 'draft') === 'draft')}>
                    پیش‌نویس
                    </option>

                    <option value="published"
                            @selected(old('status', $post?->status) === 'published')}>
                    منتشر شده
                    </option>

                </select>

                @error('status')
                <p class="mt-2 text-xs font-medium text-red-600">
                    {{ $message }}
                </p>
                @enderror

            </div>


            {{-- Published At --}}
            <div>

                <label for="published_at"
                       class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                    تاریخ انتشار
                </label>

                <input type="datetime-local"
                       id="published_at"
                       name="published_at"
                       value="{{ old('published_at', $post?->published_at?->format('Y-m-d\TH:i')) }}"
                       class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none focus:border-[var(--color-brand-600)] @error('published_at') border-red-400 @enderror">

                <p class="mt-2 text-xs text-[var(--color-text-muted)]">
                    برای نوشته منتشر شده زمان انتشار را مشخص کنید.
                </p>

                @error('published_at')
                <p class="mt-2 text-xs font-medium text-red-600">
                    {{ $message }}
                </p>
                @enderror

            </div>

        </div>

    </div>


    {{-- SEO --}}
    <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

        <div class="mb-6">
            <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                تنظیمات SEO
            </h2>

            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                تمام تنظیمات پایه SEO مقاله را از همین صفحه مدیریت کنید.
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
                       value="{{ old('meta_title', $post?->meta_title) }}"
                       placeholder="عنوان سئو..."
                       class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none focus:border-[var(--color-brand-600)] @error('meta_title') border-red-400 @enderror">

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
                          class="w-full resize-y rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm leading-7 text-[var(--color-text-primary)] outline-none focus:border-[var(--color-brand-600)] @error('meta_description') border-red-400 @enderror">{{ old('meta_description', $post?->meta_description) }}</textarea>

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
                       value="{{ old('canonical_url', $post?->canonical_url) }}"
                       dir="ltr"
                       placeholder="https://example.com/blog/article"
                       class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none focus:border-[var(--color-brand-600)] @error('canonical_url') border-red-400 @enderror">

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
                       @checked(old('noindex', $post?->noindex ?? false))
                class="h-5 w-5 rounded border-[var(--color-border)] text-[var(--color-brand-600)] focus:ring-[var(--color-brand-600)]">

                <span>

                    <span class="block text-sm font-bold text-[var(--color-text-primary)]">
                        عدم ایندکس
                    </span>

                    <span class="mt-1 block text-xs text-[var(--color-text-muted)]">
                        این مقاله در نتایج موتورهای جستجو ایندکس نشود.
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


    {{-- Submit --}}
    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

        <a href="{{ route('admin.posts.index') }}"
           class="rounded-xl border border-[var(--color-border)] bg-white px-6 py-3 text-center text-sm font-bold text-[var(--color-text-primary)] hover:bg-[var(--color-neutral-50)]">
            انصراف
        </a>

        <button type="submit"
                class="rounded-xl bg-[var(--color-brand-600)] px-7 py-3 text-sm font-bold text-white transition hover:bg-[var(--color-brand-700)]">
            {{ $submitLabel }}
        </button>

    </div>

</div>
