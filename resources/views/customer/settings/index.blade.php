@extends('layouts.storefront')

@section('title', 'اطلاعات حساب | فرزین')

@section('content')
    <section class="fz-section">
        <div class="fz-container">
            <nav class="fz-breadcrumb" aria-label="مسیر صفحه">
                <a href="{{ route('home') }}">خانه</a><span aria-hidden="true">/</span>
                <a href="{{ route('customer.dashboard') }}">حساب من</a><span aria-hidden="true">/</span>
                <span aria-current="page">اطلاعات حساب</span>
            </nav>

            <header class="fz-page-heading">
                <span class="fz-kicker">FARZIN / SETTINGS</span>
                <h1 class="fz-h2">اطلاعات حساب</h1>
                <p class="fz-desc">نام و ایمیل حساب را به‌روزرسانی کن.</p>
            </header>

            <div class="fz-panel fz-account-form-panel">
                <form method="POST" action="{{ route('customer.settings.update') }}" class="fz-form-grid">
                    @csrf
                    @method('PUT')
                    <label class="fz-form-field" for="customer-name">
                        <span>نام و نام خانوادگی</span>
                        <input class="fz-input" id="customer-name" name="name" value="{{ old('name', $user->name) }}" autocomplete="name" minlength="2" maxlength="255" required>
                        @error('name')<span class="fz-field-error">{{ $message }}</span>@enderror
                    </label>
                    <label class="fz-form-field" for="customer-email">
                        <span>ایمیل</span>
                        <input class="fz-input" id="customer-email" name="email" type="email" value="{{ old('email', $user->email) }}" autocomplete="email" maxlength="255" required>
                        @error('email')<span class="fz-field-error">{{ $message }}</span>@enderror
                    </label>
                    <div class="fz-form-field fz-form-field--full">
                        <button class="fz-btn" type="submit">ذخیره تغییرات</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
