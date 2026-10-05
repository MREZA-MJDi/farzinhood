@extends('layouts.app')

@section('title', 'تنظیمات حساب | Farzin')
@section('meta_description', 'مدیریت اطلاعات حساب کاربری فرزین')

@section('content')
<section class="farzin-container py-8 sm:py-10 lg:py-12">
    <header class="max-w-2xl">
        <span class="farzin-eyebrow">FARZIN / ACCOUNT SETTINGS</span>
        <h1 class="farzin-page-title mt-3">تنظیمات حساب</h1>
        <p class="farzin-section-description mt-3">
            اطلاعات پایه حساب را به‌روز نگه دار تا خرید و پیگیری سفارش‌ها بدون اصطکاک انجام شود.
        </p>
    </header>

    @if(session('success'))
        <div class="mt-6 rounded-2xl border border-[var(--color-success-100)] bg-[var(--color-success-50)] px-4 py-3 text-sm font-bold text-[var(--color-success-700)]">
            {{ session('success') }}
        </div>
    @endif

    <div class="mt-8 grid gap-6 lg:grid-cols-[230px_minmax(0,1fr)]">
        <aside class="h-fit rounded-[1.75rem] border border-[var(--color-border)] bg-[var(--color-earth-50)] p-3 lg:sticky lg:top-28">
            @foreach([
                ['label' => 'داشبورد', 'href' => route('customer.dashboard')],
                ['label' => 'سفارش‌ها', 'href' => route('customer.orders.index')],
                ['label' => 'علاقه‌مندی‌ها', 'href' => route('customer.wishlist.index')],
                ['label' => 'آدرس‌ها', 'href' => route('customer.addresses.index')],
                ['label' => 'تنظیمات', 'href' => route('customer.settings.index')],
            ] as $item)
                <a href="{{ $item['href'] }}" class="mt-1 flex items-center justify-between rounded-xl px-4 py-3.5 text-xs font-black transition first:mt-0 {{ request()->url() === $item['href'] ? 'bg-[var(--color-brand-900)] text-white' : 'text-[var(--color-text-secondary)] hover:bg-white hover:text-[var(--color-brand-900)]' }}">
                    <span>{{ $item['label'] }}</span>
                    <span aria-hidden="true">←</span>
                </a>
            @endforeach
        </aside>

        <div class="space-y-6">
            <section class="overflow-hidden rounded-[1.75rem] border border-[var(--color-border)] bg-white shadow-[var(--shadow-xs)]">
                <header class="border-b border-[var(--color-border)] bg-[var(--color-earth-50)] px-6 py-6 sm:px-8">
                    <span class="text-[9px] font-black uppercase tracking-[0.2em] text-[var(--color-earth-700)]">PROFILE</span>
                    <div class="mt-2 flex items-center gap-3">
                        <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-[var(--color-brand-900)] text-lg font-black text-white">
                            {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <h2 class="text-xl font-black">اطلاعات شخصی</h2>
                            <p class="mt-1 text-[10px] text-[var(--color-text-muted)]">حساب فعال مشتری</p>
                        </div>
                    </div>
                </header>

                <form action="{{ route('customer.settings.update') }}" method="POST" class="p-6 sm:p-8">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="name" class="text-xs font-black text-[var(--color-text-secondary)]">نام و نام خانوادگی</label>
                            <input id="name" name="name" type="text" value="{{ old('name', auth()->user()->name) }}" maxlength="255" required class="mt-2 w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-earth-50)] px-4 py-3.5 text-sm outline-none transition focus:border-[var(--color-earth-400)] focus:bg-white focus:ring-4 focus:ring-[var(--color-earth-200)]/40">
                            @error('name')<p class="mt-2 text-xs font-bold text-[var(--color-danger-700)]">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="email" class="text-xs font-black text-[var(--color-text-secondary)]">ایمیل</label>
                            <input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" maxlength="255" required class="mt-2 w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-earth-50)] px-4 py-3.5 text-sm outline-none transition focus:border-[var(--color-earth-400)] focus:bg-white focus:ring-4 focus:ring-[var(--color-earth-200)]/40">
                            @error('email')<p class="mt-2 text-xs font-bold text-[var(--color-danger-700)]">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <span class="text-xs font-black text-[var(--color-text-secondary)]">نوع حساب</span>
                            <div class="mt-2 flex min-h-[52px] items-center justify-between rounded-xl border border-[var(--color-border)] bg-[var(--color-neutral-50)] px-4 text-sm font-black text-[var(--color-text-secondary)]">
                                <span>مشتری</span>
                                <span class="rounded-full bg-[var(--color-success-50)] px-2.5 py-1 text-[9px] text-[var(--color-success-700)]">فعال</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-7 flex flex-col gap-3 sm:flex-row sm:justify-end">
                        <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center justify-center rounded-xl border border-[var(--color-border)] px-5 py-3.5 text-xs font-black text-[var(--color-text-secondary)] transition hover:bg-[var(--color-earth-50)]">
                            انصراف
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-[var(--color-brand-900)] px-6 py-3.5 text-sm font-black text-white transition hover:bg-[var(--color-brand-950)]">
                            ذخیره تغییرات
                        </button>
                    </div>
                </form>
            </section>

            <section class="rounded-[1.75rem] border border-[var(--color-border)] bg-[var(--color-earth-50)] p-6 sm:p-8">
                <span class="text-[9px] font-black uppercase tracking-[0.2em] text-[var(--color-earth-700)]">SECURITY</span>
                <h2 class="mt-2 text-lg font-black">امنیت حساب</h2>
                <p class="mt-2 max-w-2xl text-xs leading-7 text-[var(--color-text-secondary)]">
                    ورود، خروج و مدیریت نشست از مسیر رسمی احراز هویت فرزین انجام می‌شود. این بخش فقط اطلاعات پروفایل را مدیریت می‌کند.
                </p>
                <form action="{{ route('logout') }}" method="POST" class="mt-5">
                    @csrf
                    <button type="submit" class="rounded-xl border border-[var(--color-danger-100)] bg-white px-5 py-3 text-xs font-black text-[var(--color-danger-700)] transition hover:bg-[var(--color-danger-50)]">
                        خروج از حساب
                    </button>
                </form>
            </section>
        </div>
    </div>
</section>
@endsection
