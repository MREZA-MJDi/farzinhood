@extends('layouts.app')

@section('title', 'تنظیمات حساب | فرزین')

@section('content')
<section class="fh-page">
    <header class="fh-page__hero"><p class="fh-eyebrow fh-eyebrow--dark">FARZIN / SETTINGS</p><h1>تنظیمات حساب</h1><p>اطلاعات اصلی حساب کاربری خود را مدیریت کنید.</p></header>
    <div class="fh-page__inner fh-form-page">
        <form action="{{ route('customer.settings.update') }}" method="POST" class="fh-form-panel">
            @csrf
            @method('PUT')
            <div class="fh-form-panel__heading"><h2>اطلاعات شخصی</h2><p>برای حفظ امنیت حساب، ایمیل معتبر وارد کنید.</p></div>
            <label for="name">نام و نام خانوادگی</label>
            <input id="name" name="name" value="{{ old('name', $user->name ?? auth()->user()->name) }}" required maxlength="255" autocomplete="name">
            @error('name')<p class="fh-form-error">{{ $message }}</p>@enderror
            <label for="email">ایمیل</label>
            <input id="email" type="email" name="email" value="{{ old('email', $user->email ?? auth()->user()->email) }}" required maxlength="255" autocomplete="email">
            @error('email')<p class="fh-form-error">{{ $message }}</p>@enderror
            <button class="fh-button fh-button--dark" type="submit">ذخیره تغییرات <span aria-hidden="true">↗</span></button>
        </form>
    </div>
</section>
@endsection
