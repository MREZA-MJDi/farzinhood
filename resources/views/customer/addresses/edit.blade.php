@extends('layouts.app')

@section('title', 'ویرایش آدرس | فرزین')

@section('content')
<section class="fh-page">
    <header class="fh-page__hero"><p class="fh-eyebrow fh-eyebrow--dark">FARZIN / ADDRESSES</p><h1>ویرایش آدرس</h1><p>اطلاعات این آدرس را بررسی و ذخیره کنید.</p></header>
    <div class="fh-page__inner fh-form-page">
        <form action="{{ route('customer.addresses.update', ['address' => $address->id]) }}" method="POST" class="fh-form-panel">
            @csrf
            @method('PUT')
            <label for="title">عنوان آدرس</label><input id="title" name="title" value="{{ old('title', $address->title) }}" maxlength="100">
            <label for="full_name">نام تحویل‌گیرنده *</label><input id="full_name" name="full_name" value="{{ old('full_name', $address->full_name) }}" required maxlength="255">
            <label for="phone">شماره تماس *</label><input id="phone" name="phone" value="{{ old('phone', $address->phone) }}" required maxlength="30">
            <label for="country">کشور *</label><input id="country" name="country" value="{{ old('country', $address->country) }}" required maxlength="100">
            <div class="fh-form-grid"><div><label for="province">استان *</label><input id="province" name="province" value="{{ old('province', $address->province) }}" required maxlength="100"></div><div><label for="city">شهر *</label><input id="city" name="city" value="{{ old('city', $address->city) }}" required maxlength="100"></div></div>
            <label for="postal_code">کد پستی</label><input id="postal_code" name="postal_code" value="{{ old('postal_code', $address->postal_code) }}" maxlength="20">
            <label for="address">آدرس کامل *</label><textarea id="address" name="address" rows="4" required maxlength="2000">{{ old('address', $address->address) }}</textarea>
            <label class="fh-checkbox"><input type="checkbox" name="is_default" value="1" @checked(old('is_default', $address->is_default))> آدرس پیش‌فرض باشد</label>
            @foreach (['title','full_name','phone','country','province','city','postal_code','address'] as $field) @error($field)<p class="fh-form-error">{{ $message }}</p>@enderror @endforeach
            <button class="fh-button fh-button--dark" type="submit">ذخیره تغییرات <span aria-hidden="true">↗</span></button>
            <a class="fh-text-link" href="{{ route('customer.addresses.index') }}">بازگشت به آدرس‌ها</a>
        </form>
    </div>
</section>
@endsection
