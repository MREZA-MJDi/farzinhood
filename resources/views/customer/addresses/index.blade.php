@extends('layouts.app')

@section('title', 'آدرس‌های من | فرزین')

@section('content')
<section class="fh-page">
    <header class="fh-page__hero"><p class="fh-eyebrow fh-eyebrow--dark">FARZIN / ADDRESSES</p><h1>آدرس‌های من</h1><p>نشانی‌های ارسال را ذخیره کنید تا ثبت سفارش ساده‌تر شود.</p></header>
    <div class="fh-page__inner fh-address-layout">
        <div class="fh-address-list">
            @forelse ($addresses as $address)
                <article class="fh-address-card">
                    <div class="fh-address-card__heading"><h2>{{ $address->title ?: 'آدرس ارسال' }}</h2>@if($address->is_default)<span>پیش‌فرض</span>@endif</div>
                    <p>{{ $address->full_name }} · {{ $address->phone }}</p>
                    <p>{{ $address->country }}، {{ $address->province }}، {{ $address->city }}، {{ $address->address }}</p>
                    @if($address->postal_code)<small>کد پستی: {{ $address->postal_code }}</small>@endif
                    <div class="fh-address-card__actions">
                        <a href="{{ route('customer.addresses.edit', ['address' => $address->id]) }}">ویرایش آدرس</a>
                        @unless($address->is_default)
                            <form action="{{ route('customer.addresses.default', ['address' => $address->id]) }}" method="POST">@csrf @method('PATCH')<button type="submit">انتخاب به‌عنوان پیش‌فرض</button></form>
                        @endunless
                        <form action="{{ route('customer.addresses.destroy', ['address' => $address->id]) }}" method="POST" onsubmit="return confirm('این آدرس حذف شود؟')">@csrf @method('DELETE')<button type="submit">حذف</button></form>
                    </div>
                </article>
            @empty
                <div class="fh-empty">هنوز آدرسی ذخیره نکرده‌اید. فرم را تکمیل کنید تا اولین آدرس شما ثبت شود.</div>
            @endforelse
        </div>
        <form action="{{ route('customer.addresses.store') }}" method="POST" class="fh-form-panel">
            @csrf
            <div class="fh-form-panel__heading"><h2>افزودن آدرس</h2><p>فیلدهای ستاره‌دار الزامی هستند.</p></div>
            <label for="title">عنوان آدرس</label><input id="title" name="title" value="{{ old('title') }}" placeholder="مثلاً خانه یا محل کار" maxlength="100">
            <label for="full_name">نام تحویل‌گیرنده *</label><input id="full_name" name="full_name" value="{{ old('full_name', auth()->user()->name) }}" required maxlength="255">
            <label for="phone">شماره تماس *</label><input id="phone" name="phone" value="{{ old('phone') }}" required maxlength="30" autocomplete="tel">
            <label for="country">کشور *</label><input id="country" name="country" value="{{ old('country', 'ایران') }}" required maxlength="100">
            <div class="fh-form-grid"><div><label for="province">استان *</label><input id="province" name="province" value="{{ old('province') }}" required maxlength="100"></div><div><label for="city">شهر *</label><input id="city" name="city" value="{{ old('city') }}" required maxlength="100"></div></div>
            <label for="postal_code">کد پستی</label><input id="postal_code" name="postal_code" value="{{ old('postal_code') }}" maxlength="20">
            <label for="address">آدرس کامل *</label><textarea id="address" name="address" rows="4" required maxlength="2000">{{ old('address') }}</textarea>
            <label class="fh-checkbox"><input type="checkbox" name="is_default" value="1" @checked(old('is_default'))> آدرس پیش‌فرض باشد</label>
            @foreach (['title','full_name','phone','country','province','city','postal_code','address'] as $field) @error($field)<p class="fh-form-error">{{ $message }}</p>@enderror @endforeach
            <button class="fh-button fh-button--dark" type="submit">ذخیره آدرس <span aria-hidden="true">↗</span></button>
        </form>
    </div>
</section>
@endsection
