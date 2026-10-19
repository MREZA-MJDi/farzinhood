@extends('layouts.app')
@section('title', 'آدرس‌های من | فرزین')
@section('content')
<section class="farzin-catalog">
    <div class="farzin-container">
        <header class="farzin-page-head">
            <p class="farzin-kicker">FARZIN / ADDRESSES</p>
            <h1>آدرس‌های <em>من</em></h1>
            <p>آدرس‌های ارسال سفارش را مدیریت کن؛ اطلاعات هر آدرس فقط در حساب خودت نگهداری می‌شود.</p>
        </header>
        @include('partials.account-nav')

        <div class="mt-8 grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(320px,.82fr)]">
            <section class="overflow-hidden rounded-2xl border border-[var(--color-border)] bg-white">
                <div class="border-b border-[var(--color-border)] px-5 py-4 sm:px-6">
                    <p class="farzin-kicker !mb-1">SAVED LOCATIONS</p>
                    <h2 class="text-lg font-black">آدرس‌های ذخیره‌شده</h2>
                </div>
                <div class="divide-y divide-[var(--color-border)]">
                    @forelse($addresses as $address)
                        <article class="p-5 sm:p-6">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="font-black">{{ $address->title ?: 'آدرس ارسال' }}</h3>
                                        @if($address->is_default)<span class="rounded-full bg-[var(--color-neutral-100)] px-2.5 py-1 text-[10px] font-bold">پیش‌فرض</span>@endif
                                    </div>
                                    <p class="mt-2 text-sm font-bold">{{ $address->full_name }} · <span dir="ltr">{{ $address->phone }}</span></p>
                                    <p class="mt-2 text-sm leading-7 text-[var(--color-text-secondary)]">{{ $address->province }}، {{ $address->city }}، {{ $address->address }}</p>
                                    @if($address->postal_code)<p class="mt-1 text-xs text-[var(--color-text-muted)]">کد پستی: {{ $address->postal_code }}</p>@endif
                                </div>
                                <div class="flex shrink-0 gap-2">
                                    <a href="{{ route('customer.addresses.edit', $address) }}" class="rounded-lg border border-[var(--color-border)] px-3 py-2 text-xs font-bold hover:bg-[var(--color-surface-soft)]">ویرایش</a>
                                    @if(!$address->is_default)
                                        <form action="{{ route('customer.addresses.default', $address) }}" method="POST">@csrf @method('PATCH')<button class="rounded-lg border border-[var(--color-border)] px-3 py-2 text-xs font-bold hover:bg-[var(--color-surface-soft)]" type="submit">پیش‌فرض</button></form>
                                    @endif
                                </div>
                            </div>
                            <form action="{{ route('customer.addresses.destroy', $address) }}" method="POST" class="mt-4" onsubmit="return confirm('این آدرس حذف شود؟')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-red-700 underline underline-offset-4">حذف آدرس</button>
                            </form>
                        </article>
                    @empty
                        <div class="px-6 py-12 text-center">
                            <span class="text-4xl font-black text-[var(--color-text-soft)]">⌖</span>
                            <h3 class="mt-3 font-black">هنوز آدرسی ذخیره نشده است</h3>
                            <p class="mt-2 text-sm text-[var(--color-text-muted)]">از فرم روبه‌رو اولین آدرس ارسال را اضافه کن.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <section class="h-fit rounded-2xl border border-[var(--color-border)] bg-white p-5 sm:p-6">
                <p class="farzin-kicker !mb-1">NEW ADDRESS</p>
                <h2 class="mb-5 text-lg font-black">افزودن آدرس</h2>
                <form action="{{ route('customer.addresses.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div><label for="address-title" class="mb-1.5 block text-xs font-bold">عنوان آدرس</label><input id="address-title" name="title" value="{{ old('title') }}" maxlength="100" placeholder="مثلاً خانه یا محل کار" class="w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-soft)] px-3.5 py-3 text-sm">@error('title')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror</div>
                    <div><label for="full-name" class="mb-1.5 block text-xs font-bold">نام تحویل‌گیرنده</label><input id="full-name" name="full_name" value="{{ old('full_name') }}" required maxlength="255" autocomplete="name" class="w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-soft)] px-3.5 py-3 text-sm">@error('full_name')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror</div>
                    <div><label for="address-phone" class="mb-1.5 block text-xs font-bold">شماره تماس</label><input id="address-phone" name="phone" value="{{ old('phone') }}" required maxlength="30" inputmode="tel" autocomplete="tel" class="w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-soft)] px-3.5 py-3 text-sm">@error('phone')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror</div>
                    <div class="grid grid-cols-2 gap-3">
                        <div><label for="province" class="mb-1.5 block text-xs font-bold">استان</label><input id="province" name="province" value="{{ old('province') }}" required maxlength="100" class="w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-soft)] px-3 py-3 text-sm">@error('province')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror</div>
                        <div><label for="city" class="mb-1.5 block text-xs font-bold">شهر</label><input id="city" name="city" value="{{ old('city') }}" required maxlength="100" class="w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-soft)] px-3 py-3 text-sm">@error('city')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror</div>
                    </div>
                    <div><label for="country" class="mb-1.5 block text-xs font-bold">کشور</label><input id="country" name="country" value="{{ old('country', 'ایران') }}" required maxlength="100" class="w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-soft)] px-3.5 py-3 text-sm">@error('country')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror</div>
                    <div><label for="postal-code" class="mb-1.5 block text-xs font-bold">کد پستی (اختیاری)</label><input id="postal-code" name="postal_code" value="{{ old('postal_code') }}" maxlength="20" inputmode="numeric" autocomplete="postal-code" class="w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-soft)] px-3.5 py-3 text-sm">@error('postal_code')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror</div>
                    <div><label for="address-line" class="mb-1.5 block text-xs font-bold">نشانی کامل</label><textarea id="address-line" name="address" rows="3" required maxlength="2000" autocomplete="street-address" class="w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-soft)] px-3.5 py-3 text-sm">{{ old('address') }}</textarea>@error('address')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror</div>
                    <label class="flex items-center gap-2 text-xs font-bold"><input type="checkbox" name="is_default" value="1" @checked(old('is_default'))> ثبت به‌عنوان آدرس پیش‌فرض</label>
                    <button type="submit" class="farzin-button w-full justify-center">ذخیره آدرس <span aria-hidden="true">↗</span></button>
                </form>
            </section>
        </div>
    </div>
</section>
@endsection
