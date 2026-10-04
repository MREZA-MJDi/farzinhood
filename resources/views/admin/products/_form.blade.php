@php
    $isEdit = $product !== null;
@endphp

<div class="space-y-6">

    {{-- Basic Information --}}
    <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

        <div class="mb-6">
            <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                اطلاعات اصلی
            </h2>

            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                اطلاعات پایه محصول را وارد کنید.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            {{-- Name --}}
            <div class="md:col-span-2">
                <label for="name"
                       class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                    نام محصول
                    <span class="text-red-500">*</span>
                </label>

                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name', $product?->name) }}"
                       placeholder="مثلاً لپ‌تاپ ایسوس..."
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
                       value="{{ old('slug', $product?->slug) }}"
                       dir="ltr"
                       placeholder="product-slug"
                       class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none transition placeholder:text-[var(--color-text-muted)] focus:border-[var(--color-brand-600)] @error('slug') border-red-400 @enderror">

                <p class="mt-2 text-xs text-[var(--color-text-muted)]">
                    در صورت خالی بودن، از نام محصول ساخته می‌شود.
                </p>

                @error('slug')
                <p class="mt-2 text-xs font-medium text-red-600">
                    {{ $message }}
                </p>
                @enderror
            </div>

            {{-- SKU --}}
            <div>
                <label for="sku"
                       class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                    SKU
                    <span class="text-red-500">*</span>
                </label>

                <input type="text"
                       id="sku"
                       name="sku"
                       value="{{ old('sku', $product?->sku) }}"
                       dir="ltr"
                       placeholder="SKU-1001"
                       class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none transition placeholder:text-[var(--color-text-muted)] focus:border-[var(--color-brand-600)] @error('sku') border-red-400 @enderror">

                @error('sku')
                <p class="mt-2 text-xs font-medium text-red-600">
                    {{ $message }}
                </p>
                @enderror
            </div>

            {{-- Brand --}}
            <div>
                <label for="brand"
                       class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                    برند
                </label>

                <input type="text"
                       id="brand"
                       name="brand"
                       value="{{ old('brand', $product?->brand) }}"
                       placeholder="مثلاً Asus"
                       class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none transition placeholder:text-[var(--color-text-muted)] focus:border-[var(--color-brand-600)] @error('brand') border-red-400 @enderror">

                @error('brand')
                <p class="mt-2 text-xs font-medium text-red-600">
                    {{ $message }}
                </p>
                @enderror
            </div>

            {{-- Category --}}
            <div>
                <label for="category_id"
                       class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                    دسته‌بندی
                    <span class="text-red-500">*</span>
                </label>

                <select id="category_id"
                        name="category_id"
                        class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none transition focus:border-[var(--color-brand-600)] @error('category_id') border-red-400 @enderror">

                    <option value="">
                        انتخاب دسته‌بندی
                    </option>

                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                                @selected((string) old('category_id', $product?->category_id) === (string) $category->id)>
                        {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                @error('category_id')
                <p class="mt-2 text-xs font-medium text-red-600">
                    {{ $message }}
                </p>
                @enderror
            </div>

        </div>
    </div>


    {{-- Descriptions --}}
    <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

        <div class="mb-6">
            <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                توضیحات
            </h2>

            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                توضیحات کوتاه و کامل محصول را وارد کنید.
            </p>
        </div>

        <div class="space-y-5">

            {{-- Short Description --}}
            <div>
                <label for="short_description"
                       class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                    توضیح کوتاه
                </label>

                <textarea id="short_description"
                          name="short_description"
                          rows="4"
                          placeholder="توضیح کوتاه برای نمایش در لیست محصولات..."
                          class="w-full resize-y rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm leading-7 text-[var(--color-text-primary)] outline-none transition placeholder:text-[var(--color-text-muted)] focus:border-[var(--color-brand-600)] @error('short_description') border-red-400 @enderror">{{ old('short_description', $product?->short_description) }}</textarea>

                @error('short_description')
                <p class="mt-2 text-xs font-medium text-red-600">
                    {{ $message }}
                </p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label for="description"
                       class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                    توضیحات کامل
                </label>

                <textarea id="description"
                          name="description"
                          rows="10"
                          placeholder="توضیحات کامل محصول..."
                          class="w-full resize-y rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm leading-7 text-[var(--color-text-primary)] outline-none transition placeholder:text-[var(--color-text-muted)] focus:border-[var(--color-brand-600)] @error('description') border-red-400 @enderror">{{ old('description', $product?->description) }}</textarea>

                @error('description')
                <p class="mt-2 text-xs font-medium text-red-600">
                    {{ $message }}
                </p>
                @enderror
            </div>

        </div>
    </div>


    {{-- Pricing & Inventory --}}
    <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

        <div class="mb-6">
            <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                قیمت و موجودی
            </h2>

            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                قیمت، تخفیف و موجودی محصول را تعیین کنید.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-4">

            {{-- Price --}}
            <div>
                <label for="price"
                       class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                    قیمت
                    <span class="text-red-500">*</span>
                </label>

                <div class="relative">
                    <input type="number"
                           min="0"
                           id="price"
                           name="price"
                           value="{{ old('price', $product?->price) }}"
                           placeholder="0"
                           class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 pl-16 text-sm text-[var(--color-text-primary)] outline-none transition focus:border-[var(--color-brand-600)] @error('price') border-red-400 @enderror">

                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs text-[var(--color-text-muted)]">
                        تومان
                    </span>
                </div>

                @error('price')
                <p class="mt-2 text-xs font-medium text-red-600">
                    {{ $message }}
                </p>
                @enderror
            </div>

            {{-- Old Price --}}
            <div>
                <label for="old_price"
                       class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                    قیمت قبل
                </label>

                <div class="relative">
                    <input type="number"
                           min="0"
                           id="old_price"
                           name="old_price"
                           value="{{ old('old_price', $product?->old_price) }}"
                           placeholder="0"
                           class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 pl-16 text-sm text-[var(--color-text-primary)] outline-none transition focus:border-[var(--color-brand-600)] @error('old_price') border-red-400 @enderror">

                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs text-[var(--color-text-muted)]">
                        تومان
                    </span>
                </div>

                @error('old_price')
                <p class="mt-2 text-xs font-medium text-red-600">
                    {{ $message }}
                </p>
                @enderror
            </div>

            {{-- Discount --}}
            <div>
                <label for="discount"
                       class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                    درصد تخفیف
                </label>

                <div class="relative">
                    <input type="number"
                           min="0"
                           max="100"
                           id="discount"
                           name="discount"
                           value="{{ old('discount', $product?->discount) }}"
                           placeholder="0"
                           class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 pl-10 text-sm text-[var(--color-text-primary)] outline-none transition focus:border-[var(--color-brand-600)] @error('discount') border-red-400 @enderror">

                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-[var(--color-text-muted)]">
                        ٪
                    </span>
                </div>

                @error('discount')
                <p class="mt-2 text-xs font-medium text-red-600">
                    {{ $message }}
                </p>
                @enderror
            </div>

            {{-- Stock --}}
            <div>
                <label for="stock"
                       class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                    موجودی
                    <span class="text-red-500">*</span>
                </label>

                <input type="number"
                       min="0"
                       id="stock"
                       name="stock"
                       value="{{ old('stock', $product?->stock ?? 0) }}"
                       placeholder="0"
                       class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none transition focus:border-[var(--color-brand-600)] @error('stock') border-red-400 @enderror">

                @error('stock')
                <p class="mt-2 text-xs font-medium text-red-600">
                    {{ $message }}
                </p>
                @enderror
            </div>

        </div>
    </div>


    {{-- Images --}}
    <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

        <div class="mb-6">
            <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                تصاویر
            </h2>

            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                تصاویر محصول را انتخاب کنید. اولین تصویر به عنوان تصویر اصلی در نظر گرفته می‌شود.
            </p>
        </div>

        <div>
            <label for="images"
                   class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                تصاویر جدید
            </label>

            <input type="file"
                   id="images"
                   name="images[]"
                   multiple
                   accept=".jpg,.jpeg,.png,.webp"
                   class="block w-full rounded-xl border border-[var(--color-border)] bg-white text-sm text-[var(--color-text-secondary)] file:mr-0 file:border-0 file:bg-[var(--color-neutral-100)] file:px-5 file:py-3 file:text-sm file:font-bold file:text-[var(--color-text-primary)] hover:file:bg-[var(--color-neutral-200)] @error('images') border-red-400 @enderror">

            <p class="mt-2 text-xs text-[var(--color-text-muted)]">
                فرمت‌های مجاز: JPG, JPEG, PNG, WEBP — حداکثر ۵ مگابایت برای هر تصویر
            </p>

            @error('images')
            <p class="mt-2 text-xs font-medium text-red-600">
                {{ $message }}
            </p>
            @enderror

            @error('images.*')
            <p class="mt-2 text-xs font-medium text-red-600">
                {{ $message }}
            </p>
            @enderror
        </div>

        {{-- Existing Images --}}
        @if($isEdit && $product->images->isNotEmpty())
            <div class="mt-6">
                <h3 class="mb-4 text-sm font-black text-[var(--color-text-primary)]">
                    تصاویر فعلی
                </h3>

                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                    @foreach($product->images->sortBy('sort_order') as $image)
                        <div class="overflow-hidden rounded-2xl border border-[var(--color-border)] bg-[var(--color-neutral-50)]">
                            <img src="{{ asset('storage/' . $image->image) }}"
                                 alt="{{ $image->alt ?: $product->name }}"
                                 class="aspect-square w-full object-cover">

                            <div class="p-3">
                                @if($image->is_primary)
                                    <span class="inline-flex rounded-full bg-[var(--color-brand-50)] px-2.5 py-1 text-[11px] font-bold text-[var(--color-brand-700)]">
                                        تصویر اصلی
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-[var(--color-neutral-100)] px-2.5 py-1 text-[11px] font-bold text-[var(--color-text-secondary)]">
                                        تصویر محصول
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>


    {{-- SEO --}}
    <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

        <div class="mb-6">
            <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                SEO
            </h2>

            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                اطلاعات سئو برای موتورهای جستجو.
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
                       value="{{ old('meta_title', $product?->meta_title) }}"
                       placeholder="عنوان سئو..."
                       class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none transition focus:border-[var(--color-brand-600)] @error('meta_title') border-red-400 @enderror">

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
                          rows="4"
                          placeholder="توضیحات سئو..."
                          class="w-full resize-y rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm leading-7 text-[var(--color-text-primary)] outline-none transition focus:border-[var(--color-brand-600)] @error('meta_description') border-red-400 @enderror">{{ old('meta_description', $product?->meta_description) }}</textarea>

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
                       value="{{ old('canonical_url', $product?->canonical_url) }}"
                       dir="ltr"
                       placeholder="https://example.com/products/..."
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
                       @checked(old('noindex', $product?->noindex ?? false))
                class="h-5 w-5 rounded border-[var(--color-border)] text-[var(--color-brand-600)] focus:ring-[var(--color-brand-600)]">

                <span>
                    <span class="block text-sm font-bold text-[var(--color-text-primary)]">
                        عدم ایندکس
                    </span>

                    <span class="mt-1 block text-xs text-[var(--color-text-muted)]">
                        این صفحه در موتورهای جستجو ایندکس نشود.
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

        <div class="mb-6">
            <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                وضعیت نمایش
            </h2>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

            {{-- Active --}}
            <label class="flex cursor-pointer items-center justify-between rounded-xl border border-[var(--color-border)] p-4 transition hover:bg-[var(--color-neutral-50)]">

                <div>
                    <div class="text-sm font-bold text-[var(--color-text-primary)]">
                        محصول فعال باشد
                    </div>

                    <div class="mt-1 text-xs text-[var(--color-text-muted)]">
                        محصول در فروشگاه قابل مشاهده باشد.
                    </div>
                </div>

                <input type="checkbox"
                       name="is_active"
                       value="1"
                       @checked(old('is_active', $product?->is_active ?? true))
                class="h-5 w-5 rounded border-[var(--color-border)] text-[var(--color-brand-600)] focus:ring-[var(--color-brand-600)]">
            </label>

            {{-- Featured --}}
            <label class="flex cursor-pointer items-center justify-between rounded-xl border border-[var(--color-border)] p-4 transition hover:bg-[var(--color-neutral-50)]">

                <div>
                    <div class="text-sm font-bold text-[var(--color-text-primary)]">
                        محصول ویژه باشد
                    </div>

                    <div class="mt-1 text-xs text-[var(--color-text-muted)]">
                        در بخش محصولات ویژه نمایش داده شود.
                    </div>
                </div>

                <input type="checkbox"
                       name="is_featured"
                       value="1"
                       @checked(old('is_featured', $product?->is_featured ?? false))
                class="h-5 w-5 rounded border-[var(--color-border)] text-[var(--color-brand-600)] focus:ring-[var(--color-brand-600)]">
            </label>

        </div>
    </div>


    {{-- Submit --}}
    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

        <a href="{{ route('admin.products.index') }}"
           class="rounded-xl border border-[var(--color-border)] bg-white px-6 py-3 text-center text-sm font-bold text-[var(--color-text-primary)] transition hover:bg-[var(--color-neutral-50)]">
            انصراف
        </a>

        <button type="submit"
                class="rounded-xl bg-[var(--color-brand-600)] px-7 py-3 text-sm font-bold text-white transition hover:bg-[var(--color-brand-700)]">
            {{ $submitLabel }}
        </button>

    </div>

</div>
