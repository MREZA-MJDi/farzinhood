@extends('layouts.app')
@section('title', 'تماس با فرزین')
@section('meta_description', 'از طریق فرم تماس، پیام خود را برای پشتیبانی فرزین ارسال کنید.')
@section('content')
<section class="farzin-catalog">
    <div class="farzin-container">
        <header class="farzin-page-head">
            <p class="farzin-kicker">FARZIN / CONTACT</p>
            <h1>در تماس <em>باشیم</em></h1>
            <p>پرسش یا پیشنهادی داری؟ پیام بگذار تا تیم فرزین بررسی کند.</p>
        </header>
        <div class="mt-8 grid gap-8 lg:grid-cols-[.8fr_1.2fr]">
            <aside class="rounded-2xl bg-[#090909] p-6 text-white sm:p-8">
                <p class="text-[10px] font-bold uppercase tracking-[.2em] text-white/50">WE'RE HERE TO HELP</p>
                <h2 class="mt-4 text-2xl font-black">پاسخ‌گویی روشن، تجربه‌ی بهتر</h2>
                <p class="mt-4 text-sm leading-8 text-white/65">برای پیگیری سفارش، پرسش درباره محصول یا ارسال پیشنهاد از فرم استفاده کن.</p>
            </aside>
            <form action="{{ route('contact.store') }}" method="POST" class="rounded-2xl border border-[var(--color-border)] bg-white p-5 sm:p-8">
                @csrf
                <div class="grid gap-4 sm:grid-cols-2">
                    <div><label for="contact-name" class="mb-2 block text-sm font-bold">نام</label><input id="contact-name" name="name" value="{{ old('name') }}" required maxlength="255" autocomplete="name" class="w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-soft)] px-4 py-3 text-sm">@error('name')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror</div>
                    <div><label for="contact-email" class="mb-2 block text-sm font-bold">ایمیل</label><input id="contact-email" type="email" name="email" value="{{ old('email') }}" required maxlength="255" autocomplete="email" dir="ltr" class="w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-soft)] px-4 py-3 text-sm">@error('email')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror</div>
                    <div><label for="contact-phone" class="mb-2 block text-sm font-bold">شماره تماس (اختیاری)</label><input id="contact-phone" name="phone" value="{{ old('phone') }}" maxlength="30" inputmode="tel" class="w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-soft)] px-4 py-3 text-sm">@error('phone')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror</div>
                    <div><label for="contact-subject" class="mb-2 block text-sm font-bold">موضوع</label><input id="contact-subject" name="subject" value="{{ old('subject') }}" required maxlength="255" class="w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-soft)] px-4 py-3 text-sm">@error('subject')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror</div>
                </div>
                <div class="mt-4"><label for="contact-message" class="mb-2 block text-sm font-bold">پیام</label><textarea id="contact-message" name="message" rows="5" required minlength="10" maxlength="5000" class="w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-soft)] px-4 py-3 text-sm">{{ old('message') }}</textarea>@error('message')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror</div>
                <button class="farzin-button mt-5" type="submit">ارسال پیام <span aria-hidden="true">↗</span></button>
            </form>
        </div>
    </div>
</section>
@endsection
