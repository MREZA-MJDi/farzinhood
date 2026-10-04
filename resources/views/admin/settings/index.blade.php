@extends('layouts.admin')

@section('title', 'تنظیمات سایت')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-black text-[var(--color-text-primary)]">
                تنظیمات سایت
            </h1>

            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                اطلاعات عمومی، ارتباطی و تنظیمات پایه سایت را مدیریت کنید.
            </p>
        </div>


        {{-- Settings Form --}}
        <form action="{{ route('admin.settings.update') }}"
              method="POST"
              class="space-y-6">

            @csrf
            @method('PUT')


            {{-- General --}}
            <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

                <div class="mb-6">
                    <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                        اطلاعات عمومی
                    </h2>

                    <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                        اطلاعات اصلی برند و سایت.
                    </p>
                </div>


                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    {{-- Site Name --}}
                    <div>
                        <label for="site_name"
                               class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                            نام سایت
                        </label>

                        <input type="text"
                               id="site_name"
                               name="site_name"
                               value="{{ old('site_name', $settings['site_name'] ?? '') }}"
                               placeholder="نام فروشگاه"
                               class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none transition focus:border-[var(--color-brand-600)] @error('site_name') border-red-400 @enderror">

                        @error('site_name')
                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>


                    {{-- Phone --}}
                    <div>
                        <label for="phone"
                               class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                            شماره تماس
                        </label>

                        <input type="text"
                               id="phone"
                               name="phone"
                               value="{{ old('phone', $settings['phone'] ?? '') }}"
                               dir="ltr"
                               placeholder="09xxxxxxxxx"
                               class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none transition focus:border-[var(--color-brand-600)] @error('phone') border-red-400 @enderror">

                        @error('phone')
                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>


                    {{-- Description --}}
                    <div class="md:col-span-2">

                        <label for="site_description"
                               class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                            توضیح کوتاه سایت
                        </label>

                        <textarea id="site_description"
                                  name="site_description"
                                  rows="4"
                                  placeholder="توضیح کوتاه درباره سایت..."
                                  class="w-full resize-y rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm leading-7 text-[var(--color-text-primary)] outline-none transition focus:border-[var(--color-brand-600)] @error('site_description') border-red-400 @enderror">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>

                        @error('site_description')
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
                        SEO سایت
                    </h2>

                    <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                        تنظیمات پایه SEO برای صفحات عمومی سایت.
                    </p>
                </div>


                <div class="space-y-5">

                    {{-- Default Meta Title --}}
                    <div>

                        <label for="default_meta_title"
                               class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                            Default Meta Title
                        </label>

                        <input type="text"
                               id="default_meta_title"
                               name="default_meta_title"
                               value="{{ old('default_meta_title', $settings['default_meta_title'] ?? '') }}"
                               placeholder="عنوان پیش‌فرض سایت..."
                               class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none focus:border-[var(--color-brand-600)] @error('default_meta_title') border-red-400 @enderror">

                        @error('default_meta_title')
                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>
                        @enderror

                    </div>


                    {{-- Default Meta Description --}}
                    <div>

                        <label for="default_meta_description"
                               class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                            Default Meta Description
                        </label>

                        <textarea id="default_meta_description"
                                  name="default_meta_description"
                                  rows="4"
                                  placeholder="توضیحات پیش‌فرض سایت..."
                                  class="w-full resize-y rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm leading-7 text-[var(--color-text-primary)] outline-none focus:border-[var(--color-brand-600)] @error('default_meta_description') border-red-400 @enderror">{{ old('default_meta_description', $settings['default_meta_description'] ?? '') }}</textarea>

                        @error('default_meta_description')
                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>
                        @enderror

                    </div>

                </div>

            </div>


            {{-- Contact --}}
            <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

                <div class="mb-6">
                    <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                        اطلاعات تماس
                    </h2>

                    <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                        اطلاعاتی که در فوتر و صفحات تماس استفاده می‌شوند.
                    </p>
                </div>


                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    {{-- Email --}}
                    <div>

                        <label for="email"
                               class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                            ایمیل
                        </label>

                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email', $settings['email'] ?? '') }}"
                               dir="ltr"
                               placeholder="info@example.com"
                               class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none focus:border-[var(--color-brand-600)] @error('email') border-red-400 @enderror">

                        @error('email')
                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>
                        @enderror

                    </div>


                    {{-- Address --}}
                    <div>

                        <label for="address"
                               class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                            آدرس
                        </label>

                        <input type="text"
                               id="address"
                               name="address"
                               value="{{ old('address', $settings['address'] ?? '') }}"
                               placeholder="آدرس فروشگاه..."
                               class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none focus:border-[var(--color-brand-600)] @error('address') border-red-400 @enderror">

                        @error('address')
                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>
                        @enderror

                    </div>

                </div>

            </div>


            {{-- Social --}}
            <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

                <div class="mb-6">
                    <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                        شبکه‌های اجتماعی
                    </h2>

                    <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                        لینک شبکه‌های اجتماعی برند.
                    </p>
                </div>


                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    {{-- Instagram --}}
                    <div>

                        <label for="instagram"
                               class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                            Instagram
                        </label>

                        <input type="url"
                               id="instagram"
                               name="instagram"
                               value="{{ old('instagram', $settings['instagram'] ?? '') }}"
                               dir="ltr"
                               placeholder="https://instagram.com/..."
                               class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none focus:border-[var(--color-brand-600)] @error('instagram') border-red-400 @enderror">

                        @error('instagram')
                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>
                        @enderror

                    </div>


                    {{-- Telegram --}}
                    <div>

                        <label for="telegram"
                               class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">
                            Telegram
                        </label>

                        <input type="url"
                               id="telegram"
                               name="telegram"
                               value="{{ old('telegram', $settings['telegram'] ?? '') }}"
                               dir="ltr"
                               placeholder="https://t.me/..."
                               class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none focus:border-[var(--color-brand-600)] @error('telegram') border-red-400 @enderror">

                        @error('telegram')
                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>
                        @enderror

                    </div>

                </div>

            </div>


            {{-- Submit --}}
            <div class="flex justify-end">

                <button type="submit"
                        class="rounded-xl bg-[var(--color-brand-600)] px-7 py-3 text-sm font-bold text-white transition hover:bg-[var(--color-brand-700)]">
                    ذخیره تنظیمات
                </button>

            </div>

        </form>

    </div>
@endsection
