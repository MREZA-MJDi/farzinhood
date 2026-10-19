@extends('layouts.app')

@section('title', 'ساخت حساب | فرزین')
@section('meta_description', 'ساخت حساب در فرزین برای مدیریت سفارش‌ها، آدرس‌های ارسال و محصولات علاقه‌مندی.')

@push('meta')
    <meta name="robots" content="noindex,nofollow">
@endpush

@section('content')
<section class="farzin-auth">
    <div class="farzin-auth__container">
        <aside class="farzin-auth__aside">
            <div>
                <p class="farzin-kicker">FARZIN / JOIN US</p>
                <h1>برای خانه‌ات،<br><span>آگاهانه‌تر انتخاب کن.</span></h1>
                <p>حساب کاربری بساز تا سفارش‌ها و آدرس‌ها همیشه در دسترس تو باشند و محصولات موردعلاقه‌ات را نگه داری.</p>
            </div>
            <div class="farzin-auth__aside-foot">
                <p class="!mb-1 text-xs font-bold uppercase tracking-[.18em]">A MORE THOUGHTFUL HOME</p>
                <p class="!m-0 text-xs">حسابی ساده برای خریدی با اطمینان بیشتر.</p>
            </div>
        </aside>

        <div class="farzin-auth__panel">
            <header class="farzin-auth__head">
                <p class="farzin-kicker">CREATE YOUR ACCOUNT</p>
                <h2>ثبت‌نام در فرزین</h2>
                <p>اطلاعاتت را وارد کن تا حساب مشتری ساخته شود.</p>
            </header>

            <form action="{{ route('register.store') }}" method="POST" class="farzin-auth__form">
                @csrf

                <div class="farzin-auth__field">
                    <label for="name">نام و نام خانوادگی</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" autocomplete="name" placeholder="نام شما" required autofocus maxlength="255" @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                    @error('name')
                        <p class="farzin-auth__error" id="name-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="farzin-auth__field">
                    <label for="email">ایمیل</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" inputmode="email" dir="ltr" placeholder="you@example.com" required maxlength="255" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                    @error('email')
                        <p class="farzin-auth__error" id="email-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="farzin-auth__field">
                    <label for="password">رمز عبور</label>
                    <input id="password" type="password" name="password" autocomplete="new-password" dir="ltr" placeholder="حداقل ۸ کاراکتر" minlength="8" required @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                    @error('password')
                        <p class="farzin-auth__error" id="password-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="farzin-auth__field">
                    <label for="password_confirmation">تکرار رمز عبور</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" dir="ltr" placeholder="رمز عبور را تکرار کن" minlength="8" required @error('password_confirmation') aria-invalid="true" aria-describedby="password-confirmation-error" @enderror>
                    @error('password_confirmation')
                        <p class="farzin-auth__error" id="password-confirmation-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="farzin-auth__notice">
                    این حساب با نقش مشتری ساخته می‌شود. اطلاعات مدیریتی از فرم عمومی قابل انتخاب نیست.
                </div>

                <button type="submit" class="farzin-auth__submit">
                    ساخت حساب کاربری
                    <span aria-hidden="true">↗</span>
                </button>
            </form>

            <div class="farzin-auth__switch">
                <span>قبلاً حساب ساخته‌ای؟</span>
                <a href="{{ route('login') }}">وارد شو</a>
            </div>

            <a class="farzin-auth__back" href="{{ route('home') }}">
                <span aria-hidden="true">←</span>
                بازگشت به فروشگاه
            </a>
        </div>
    </div>
</section>
@endsection
