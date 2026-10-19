@extends('layouts.app')

@section('title', 'ورود به حساب | فرزین')
@section('meta_description', 'ورود امن به حساب فرزین برای پیگیری سفارش‌ها، آدرس‌ها و محصولات ذخیره‌شده.')

@push('meta')
    <meta name="robots" content="noindex,nofollow">
@endpush

@section('content')
<section class="farzin-auth">
    <div class="farzin-auth__container">
        <aside class="farzin-auth__aside">
            <div>
                <p class="farzin-kicker">FARZIN / ACCOUNT</p>
                <h1>انتخاب خوب،<br><span>از تجربه‌ای بهتر</span> شروع می‌شود.</h1>
                <p>وارد حساب خودت شو و خرید، سفارش‌ها و اطلاعات ارسال را از یک جای مشخص مدیریت کن.</p>
            </div>
            <div class="farzin-auth__aside-foot">
                <p class="!mb-1 text-xs font-bold uppercase tracking-[.18em]">A MORE THOUGHTFUL HOME</p>
                <p class="!m-0 text-xs">طراحی دقیق، مسیر روشن و خریدی مطمئن.</p>
            </div>
        </aside>

        <div class="farzin-auth__panel">
            <header class="farzin-auth__head">
                <p class="farzin-kicker">WELCOME BACK</p>
                <h2>خوش برگشتی</h2>
                <p>اطلاعات حساب را وارد کن تا ادامه بدهی.</p>
            </header>

            <form action="{{ route('login.store') }}" method="POST" class="farzin-auth__form">
                @csrf

                <div class="farzin-auth__field">
                    <label for="email">ایمیل</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" inputmode="email" dir="ltr" placeholder="you@example.com" required autofocus @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                    @error('email')
                        <p class="farzin-auth__error" id="email-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="farzin-auth__field">
                    <label for="password">رمز عبور</label>
                    <input id="password" type="password" name="password" autocomplete="current-password" dir="ltr" placeholder="••••••••" required @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                    @error('password')
                        <p class="farzin-auth__error" id="password-error">{{ $message }}</p>
                    @enderror
                </div>

                <label class="inline-flex items-center gap-2.5 text-sm font-semibold text-[var(--color-text-secondary)]">
                    <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                    <span>مرا به خاطر بسپار</span>
                </label>

                <button type="submit" class="farzin-auth__submit">
                    ورود به حساب
                    <span aria-hidden="true">↗</span>
                </button>
            </form>

            <div class="farzin-auth__switch">
                <span>هنوز حساب کاربری نداری؟</span>
                <a href="{{ route('register') }}">ثبت‌نام کن</a>
            </div>

            <a class="farzin-auth__back" href="{{ route('home') }}">
                <span aria-hidden="true">←</span>
                بازگشت به فروشگاه
            </a>
        </div>
    </div>
</section>
@endsection
