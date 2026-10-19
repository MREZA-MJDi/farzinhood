@extends('layouts.storefront')

@section('title', 'ویرایش آدرس | فرزین')

@section('content')
    <section class="fz-section">
        <div class="fz-container">
            <nav class="fz-breadcrumb" aria-label="مسیر صفحه">
                <a href="{{ route('home') }}">خانه</a><span aria-hidden="true">/</span>
                <a href="{{ route('customer.addresses.index') }}">نشانی‌های من</a><span aria-hidden="true">/</span>
                <span aria-current="page">ویرایش</span>
            </nav>
            <header class="fz-page-heading"><span class="fz-kicker">FARZIN / ADDRESS</span><h1 class="fz-h2">ویرایش آدرس</h1></header>
            <div class="fz-panel">
                <form method="POST" action="{{ route('customer.addresses.update', $address) }}" class="fz-form-grid">
                    @csrf
                    @method('PUT')
                    @foreach ([
                        'title' => ['عنوان نشانی', $address->title, 100],
                        'full_name' => ['نام تحویل‌گیرنده *', $address->full_name, 255],
                        'phone' => ['شماره تماس *', $address->phone, 30],
                        'country' => ['کشور *', $address->country, 100],
                        'province' => ['استان *', $address->province, 100],
                        'city' => ['شهر *', $address->city, 100],
                        'postal_code' => ['کد پستی', $address->postal_code, 20],
                    ] as $field => [$label, $value, $limit])
                        <label class="fz-form-field" for="address-{{ $field }}">
                            <span>{{ $label }}</span>
                            <input class="fz-input" id="address-{{ $field }}" name="{{ $field }}" value="{{ old($field, $value) }}" maxlength="{{ $limit }}" @if(in_array($field, ['full_name','phone','country','province','city'], true)) required @endif @if($field === 'phone' || $field === 'postal_code') dir="ltr" @endif>
                            @error($field)<span class="fz-field-error">{{ $message }}</span>@enderror
                        </label>
                    @endforeach
                    <label class="fz-form-field fz-form-field--full" for="address-full"><span>نشانی کامل *</span><textarea class="fz-textarea" id="address-full" name="address" rows="4" maxlength="2000" required>{{ old('address', $address->address) }}</textarea>@error('address')<span class="fz-field-error">{{ $message }}</span>@enderror</label>
                    <label class="fz-check-label fz-form-field--full"><input type="checkbox" name="is_default" value="1" @checked(old('is_default', $address->is_default))> این نشانی، نشانی پیش‌فرض من باشد.</label>
                    <div class="fz-form-field fz-form-field--full"><button class="fz-btn" type="submit">ذخیره تغییرات</button> <a class="fz-btn light" href="{{ route('customer.addresses.index') }}">انصراف</a></div>
                </form>
            </div>
        </div>
    </section>
@endsection
