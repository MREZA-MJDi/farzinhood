@extends('layouts.storefront')

@section('title', 'تماس با ما | فرزین')
@section('meta_description', 'برای پرسش درباره محصولات، سفارش و پشتیبانی با فرزین در ارتباط باش. پیام شما در سیستم پشتیبانی ثبت می‌شود.')

@section('content')
    <section class="fz-section fz-contact-page">
        <div class="fz-container">
            <nav class="fz-breadcrumb" aria-label="مسیر صفحه">
                <a href="{{ route('home') }}">خانه</a>
                <span aria-hidden="true">/</span>
                <span aria-current="page">تماس با ما</span>
            </nav>

            <div class="fz-contact-intro">
                <span class="fz-kicker">پشتیبانی فرزین</span>
                <h1 class="fz-h2">در تماس باشیم.</h1>
                <p class="fz-desc">جزئیات پرسشت را بنویس تا پیام در سیستم پشتیبانی ثبت شود و تیم فرزین بتواند آن را پیگیری کند.</p>
            </div>

            <div class="fz-contact-grid">
                <aside class="fz-panel fz-contact-aside">
                    <span class="fz-kicker">قبل از ارسال</span>
                    <h2 class="fz-h2">چطور بهتر راهنمایی‌ات کنیم؟</h2>
                    <p class="fz-desc">اگر درباره یک محصول مشخص پرسش داری، نام یا کد آن را در موضوع پیام بنویس. برای پیگیری سفارش هم شماره سفارش را در متن پیام قرار بده.</p>
                    <div class="fz-contact-note">
                        <strong>پیام‌های پشتیبانی</strong>
                        <p>بعد از ثبت، پیام در پنل مدیریت ذخیره می‌شود تا قابل بررسی و پیگیری باشد.</p>
                    </div>
                    <a class="fz-btn light" href="{{ route('shop.index') }}">مرور محصولات</a>
                </aside>

                <section class="fz-panel" aria-labelledby="contact-form-title">
                    <h2 class="fz-h2" id="contact-form-title">ارسال پیام</h2>
                    <p class="fz-desc">فیلدهای ستاره‌دار الزامی هستند.</p>

                    <form action="{{ route('contact.store') }}" method="POST" class="fz-contact-form">
                        @csrf
                        <div class="fz-form-grid">
                            <label class="fz-form-field" for="contact-name">
                                <span>نام کامل *</span>
                                <input class="fz-input" id="contact-name" name="name" value="{{ old('name') }}" autocomplete="name" maxlength="255" required>
                                @error('name')<span class="fz-field-error">{{ $message }}</span>@enderror
                            </label>
                            <label class="fz-form-field" for="contact-email">
                                <span>ایمیل *</span>
                                <input class="fz-input" id="contact-email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" maxlength="255" required>
                                @error('email')<span class="fz-field-error">{{ $message }}</span>@enderror
                            </label>
                            <label class="fz-form-field" for="contact-phone">
                                <span>شماره تماس (اختیاری)</span>
                                <input class="fz-input" id="contact-phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" maxlength="30" dir="ltr">
                                @error('phone')<span class="fz-field-error">{{ $message }}</span>@enderror
                            </label>
                            <label class="fz-form-field" for="contact-subject">
                                <span>موضوع پیام *</span>
                                <input class="fz-input" id="contact-subject" name="subject" value="{{ old('subject') }}" maxlength="255" required>
                                @error('subject')<span class="fz-field-error">{{ $message }}</span>@enderror
                            </label>
                            <label class="fz-form-field fz-form-field--full" for="contact-message">
                                <span>متن پیام *</span>
                                <textarea class="fz-textarea" id="contact-message" name="message" rows="6" minlength="10" maxlength="5000" required>{{ old('message') }}</textarea>
                                @error('message')<span class="fz-field-error">{{ $message }}</span>@enderror
                            </label>
                            <div class="fz-form-field fz-form-field--full">
                                <button class="fz-btn" type="submit">ثبت پیام <span aria-hidden="true">←</span></button>
                                <p class="fz-form-help">اطلاعات تماس فقط برای پاسخ‌گویی به همین پیام درخواست شده‌اند.</p>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </section>
@endsection
