@extends('layouts.app')

@section('title', 'تنظیمات حساب | فرزین')
@section('meta_description', 'مدیریت اطلاعات حساب کاربری در فرزین')

@section('content')
<div class="store-transaction">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="store-account-shell">
            <main class="store-account-content lg:order-2">
                <div class="store-transaction__head">
                    <div>
                        <span class="product-v2__eyebrow">FARZIN / SETTINGS</span>
                        <h1>تنظیمات حساب</h1>
                        <p>اطلاعات پایه حساب را مدیریت کن. تغییر نقش یا سطح دسترسی از این صفحه ممکن نیست.</p>
                    </div>
                    @include('partials.back-link', ['href' => route('customer.dashboard'), 'label' => 'بازگشت به حساب'])
                </div>

                <section class="store-panel mt-3">
                    <div class="store-panel__head">
                        <strong>اطلاعات شخصی</strong>
                        <span class="text-[10px] text-[var(--color-text-muted)]">پروفایل</span>
                    </div>
                    <form action="{{ route('customer.settings.update') }}" method="POST" class="store-panel__body">
                        @csrf
                        @method('PUT')
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <label for="name" class="text-[10px] font-black text-[var(--color-text-secondary)]">نام و نام خانوادگی</label>
                                <input id="name" type="text" name="name" value="{{ old('name', auth()->user()->name) }}" maxlength="255" required>
                                @error('name') <p class="mt-1 text-[10px] font-bold text-[var(--color-danger-ink)]">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="email" class="text-[10px] font-black text-[var(--color-text-secondary)]">ایمیل</label>
                                <input id="email" type="email" name="email" value="{{ old('email', auth()->user()->email) }}" maxlength="255" required>
                                @error('email') <p class="mt-1 text-[10px] font-bold text-[var(--color-danger-ink)]">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-[var(--color-text-secondary)]">نوع حساب</label>
                                <div class="mt-1 flex min-h-11 items-center rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-muted)] px-3 text-xs font-bold text-[var(--color-text-muted)]">مشتری</div>
                            </div>
                        </div>

                        <button type="submit" class="mt-4 min-h-11 rounded-xl bg-[var(--color-accent-600)] px-5 text-xs font-black text-white transition hover:bg-[var(--color-accent-700)]">ذخیره تغییرات</button>
                    </form>
                </section>

                <section class="store-panel mt-3">
                    <div class="store-panel__head">
                        <strong>امنیت حساب</strong>
                        <span class="text-[10px] text-[var(--color-text-muted)]">دسترسی</span>
                    </div>
                    <div class="store-panel__body">
                        <div class="rounded-xl border border-[#e8ddc4] bg-[var(--color-warning-surface)] p-3 text-xs leading-6 text-[var(--color-warning-ink)]">
                            رمز عبور فعلاً از مسیر جداگانه مدیریت می‌شود. تا زمانی که مسیر بازیابی رمز فعال نشده، اطلاعات حساس را اینجا نگه نمی‌داریم.
                        </div>
                    </div>
                </section>

                <section class="mt-3 rounded-[1.35rem] border border-[#eed0c8] bg-[var(--color-danger-surface)] p-4">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <strong class="text-sm font-black text-[var(--color-danger-ink)]">خروج از حساب</strong>
                            <p class="mt-1 text-[10px] leading-6 text-[var(--color-danger-ink)]/80">جلسه فعلی بسته می‌شود و به صفحه اصلی برمی‌گردی.</p>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="min-h-10 rounded-xl border border-[#e3beb4] bg-white px-4 text-xs font-black text-[var(--color-danger-ink)]">خروج امن</button>
                        </form>
                    </div>
                </section>
            </main>

            <aside class="lg:order-1">@include('partials.account-nav')</aside>
        </div>
    </div>
</div>
@endsection
