@extends('layouts.app')
@section('title', 'ویرایش آدرس | فرزین')
@section('content')
<section class="farzin-catalog">
    <div class="farzin-container">
        <header class="farzin-page-head">
            <p class="farzin-kicker">FARZIN / ADDRESSES</p>
            <h1>ویرایش <em>آدرس</em></h1>
            <p>آدرس ارسال را اصلاح و اطلاعات را ذخیره کن.</p>
        </header>
        @include('partials.account-nav')
        <div class="mt-8 max-w-2xl rounded-2xl border border-[var(--color-border)] bg-white p-5 sm:p-8">
            <form action="{{ route('customer.addresses.update', $address) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div><label for="address-title" class="mb-1.5 block text-xs font-bold">عنوان آدرس</label><input id="address-title" name="title" value="{{ old('title', $address->title) }}" maxlength="100" class="w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-soft)] px-3.5 py-3 text-sm">@error('title')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror</div>
                <div><label for="full-name" class="mb-1.5 block text-xs font-bold">نام تحویل‌گیرنده</label><input id="full-name" name="full_name" value="{{ old('full_name', $address->full_name) }}" required maxlength="255" autocomplete="name" class="w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-soft)] px-3.5 py-3 text-sm">@error('full_name')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror</div>
                <div><label for="address-phone" class="mb-1.5 block text-xs font-bold">شماره تماس</label><input id="address-phone" name="phone" value="{{ old('phone', $address->phone) }}" required maxlength="30" inputmode="tel" autocomplete="tel" class="w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-soft)] px-3.5 py-3 text-sm">@error('phone')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror</div>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div><label for="province" class="mb-1.5 block text-xs font-bold">استان</label><input id="province" name="province" value="{{ old('province', $address->province) }}" required maxlength="100" class="w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-soft)] px-3.5 py-3 text-sm">@error('province')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror</div>
                    <div><label for="city" class="mb-1.5 block text-xs font-bold">شهر</label><input id="city" name="city" value="{{ old('city', $address->city) }}" required maxlength="100" class="w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-soft)] px-3.5 py-3 text-sm">@error('city')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror</div>
                </div>
                <div><label for="country" class="mb-1.5 block text-xs font-bold">کشور</label><input id="country" name="country" value="{{ old('country', $address->country ?: 'ایران') }}" required maxlength="100" class="w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-soft)] px-3.5 py-3 text-sm">@error('country')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror</div>
                <div><label for="postal-code" class="mb-1.5 block text-xs font-bold">کد پستی</label><input id="postal-code" name="postal_code" value="{{ old('postal_code', $address->postal_code) }}" maxlength="20" inputmode="numeric" autocomplete="postal-code" class="w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-soft)] px-3.5 py-3 text-sm">@error('postal_code')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror</div>
                <div><label for="address-line" class="mb-1.5 block text-xs font-bold">نشانی کامل</label><textarea id="address-line" name="address" rows="4" required maxlength="2000" autocomplete="street-address" class="w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-soft)] px-3.5 py-3 text-sm">{{ old('address', $address->address) }}</textarea>@error('address')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror</div>
                <label class="flex items-center gap-2 text-xs font-bold"><input type="checkbox" name="is_default" value="1" @checked(old('is_default', $address->is_default))> ثبت به‌عنوان آدرس پیش‌فرض</label>
                <div class="flex flex-wrap gap-3 pt-2">
                    <button type="submit" class="farzin-button">ذخیره تغییرات <span aria-hidden="true">↗</span></button>
                    <a href="{{ route('customer.addresses.index') }}" class="farzin-reset">انصراف</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
