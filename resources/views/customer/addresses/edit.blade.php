@extends('layouts.app')

@section('title', 'ویرایش آدرس | فرزین')
@section('meta_robots', 'noindex, follow')

@section('content')
    <section class="mx-auto max-w-3xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
        <header class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-[var(--color-brand-600)]">Address book</p>
                <h1 class="mt-2 text-2xl font-black text-[var(--color-text-primary)] sm:text-3xl">ویرایش آدرس</h1>
                <p class="mt-2 text-sm leading-6 text-[var(--color-text-secondary)]">اطلاعات ارسال را بررسی و ذخیره کنید.</p>
            </div>
            <a href="{{ route('customer.addresses.index') }}"
               class="inline-flex min-h-11 items-center justify-center rounded-xl border border-[var(--color-border)] bg-white px-4 py-2 text-sm font-bold text-[var(--color-text-primary)] hover:bg-[var(--color-neutral-50)]">
                بازگشت به آدرس‌ها
            </a>
        </header>

        <form action="{{ route('customer.addresses.update', $address) }}" method="POST"
              class="space-y-6 rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm sm:p-7">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                @foreach([
                    ['title', 'عنوان آدرس', 'text', false, 100],
                    ['full_name', 'نام گیرنده', 'text', true, 255],
                    ['phone', 'شماره تماس', 'tel', true, 30],
                    ['country', 'کشور', 'text', true, 100],
                    ['province', 'استان', 'text', true, 100],
                    ['city', 'شهر', 'text', true, 100],
                    ['postal_code', 'کد پستی', 'text', false, 20],
                ] as [$field, $label, $type, $required, $max])
                    <div class="{{ in_array($field, ['title', 'full_name']) ? 'sm:col-span-1' : '' }}">
                        <label for="{{ $field }}" class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">{{ $label }}</label>
                        <input
                            id="{{ $field }}"
                            name="{{ $field }}"
                            type="{{ $type }}"
                            value="{{ old($field, $address->{$field}) }}"
                            maxlength="{{ $max }}"
                            @if($required) required @endif
                            @if(in_array($field, ['phone', 'postal_code'])) dir="ltr" @endif
                            class="min-h-11 w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm text-[var(--color-text-primary)] outline-none transition focus:border-[var(--color-brand-600)] focus:ring-4 focus:ring-[var(--color-brand-600)]/10 @error($field) border-red-400 @enderror"
                        >
                        @error($field)
                            <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                <div class="sm:col-span-2">
                    <label for="address" class="mb-2 block text-sm font-bold text-[var(--color-text-primary)]">نشانی کامل</label>
                    <textarea
                        id="address"
                        name="address"
                        rows="4"
                        maxlength="2000"
                        required
                        class="w-full rounded-xl border border-[var(--color-border)] bg-white px-4 py-3 text-sm leading-7 text-[var(--color-text-primary)] outline-none transition focus:border-[var(--color-brand-600)] focus:ring-4 focus:ring-[var(--color-brand-600)]/10 @error('address') border-red-400 @enderror"
                    >{{ old('address', $address->address) }}</textarea>
                    @error('address')
                        <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-[var(--color-border)] bg-[var(--color-neutral-50)] p-4">
                <input type="checkbox" name="is_default" value="1" @checked(old('is_default', $address->is_default))
                       class="mt-0.5 h-5 w-5 rounded border-[var(--color-border)] text-[var(--color-brand-600)] focus:ring-[var(--color-brand-600)]">
                <span>
                    <span class="block text-sm font-bold text-[var(--color-text-primary)]">آدرس پیش‌فرض ارسال باشد</span>
                    <span class="mt-1 block text-xs leading-5 text-[var(--color-text-secondary)]">فقط یک آدرس می‌تواند پیش‌فرض باشد. اگر این آدرس را از حالت پیش‌فرض خارج کنید، در صورت وجود آدرس دیگر، آن آدرس جایگزین خواهد شد.</span>
                </span>
            </label>

            @error('is_default')
                <p class="text-xs font-semibold text-red-600">{{ $message }}</p>
            @enderror

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('customer.addresses.index') }}"
                   class="inline-flex min-h-11 items-center justify-center rounded-xl border border-[var(--color-border)] px-5 py-3 text-sm font-bold text-[var(--color-text-primary)] hover:bg-[var(--color-neutral-50)]">
                    انصراف
                </a>
                <button type="submit"
                        class="inline-flex min-h-11 items-center justify-center rounded-xl bg-[var(--color-brand-600)] px-6 py-3 text-sm font-bold text-white transition hover:bg-[var(--color-brand-700)]">
                    ذخیره تغییرات
                </button>
            </div>
        </form>
    </section>
@endsection
