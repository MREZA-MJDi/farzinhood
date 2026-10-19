@extends('layouts.app')
@section('title', 'تنظیمات حساب | فرزین')
@section('content')
<section class="farzin-catalog">
    <div class="farzin-container">
        <header class="farzin-page-head">
            <p class="farzin-kicker">FARZIN / SETTINGS</p>
            <h1>تنظیمات <em>حساب</em></h1>
            <p>اطلاعات تماس خود را به‌روز نگه دار تا سفارش‌ها را بهتر مدیریت کنی.</p>
        </header>
        @include('partials.account-nav')

        <div class="mt-8 max-w-3xl rounded-2xl border border-[var(--color-border)] bg-white p-5 sm:p-8">
            <form action="{{ route('customer.settings.update') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                <div>
                    <label for="customer-name" class="mb-2 block text-sm font-bold">نام و نام خانوادگی</label>
                    <input id="customer-name" type="text" name="name" value="{{ old('name', $user->name) }}" required maxlength="255" autocomplete="name" class="w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-soft)] px-4 py-3 text-sm outline-none focus:border-[var(--color-brand-600)]">
                    @error('name')<p class="mt-2 text-xs font-bold text-red-700">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="customer-email" class="mb-2 block text-sm font-bold">ایمیل</label>
                    <input id="customer-email" type="email" name="email" value="{{ old('email', $user->email) }}" required maxlength="255" autocomplete="email" dir="ltr" class="w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-soft)] px-4 py-3 text-sm outline-none focus:border-[var(--color-brand-600)]">
                    @error('email')<p class="mt-2 text-xs font-bold text-red-700">{{ $message }}</p>@enderror
                    <p class="mt-2 text-xs leading-6 text-[var(--color-text-muted)]">در صورت تغییر ایمیل، تأیید ایمیل قبلی معتبر نخواهد بود.</p>
                </div>
                <div class="flex flex-wrap gap-3 border-t border-[var(--color-border)] pt-5">
                    <button class="farzin-button" type="submit">ذخیره تغییرات <span aria-hidden="true">↗</span></button>
                    <a class="farzin-reset" href="{{ route('customer.dashboard') }}">بازگشت به حساب</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
