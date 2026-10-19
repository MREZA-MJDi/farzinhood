@extends('layouts.storefront')

@section('title', 'نشانی‌های من | فرزین')

@section('content')
    <section class="fz-section">
        <div class="fz-container">
            <nav class="fz-breadcrumb" aria-label="مسیر صفحه">
                <a href="{{ route('home') }}">خانه</a><span aria-hidden="true">/</span>
                <a href="{{ route('customer.dashboard') }}">حساب من</a><span aria-hidden="true">/</span>
                <span aria-current="page">نشانی‌ها</span>
            </nav>
            <header class="fz-page-heading">
                <span class="fz-kicker">FARZIN / ADDRESSES</span>
                <h1 class="fz-h2">نشانی‌های من</h1>
                <p class="fz-desc">نشانی‌های ارسال را مدیریت کن؛ یک نشانی را به‌عنوان پیش‌فرض انتخاب کن تا در پرداخت سریع‌تر باشد.</p>
            </header>

            @if ($addresses->isNotEmpty())
                <div class="fz-address-grid">
                    @foreach ($addresses as $address)
                        <article class="fz-address-card">
                            <div class="fz-address-card__head">
                                <h2>{{ $address->title ?: 'نشانی ارسال' }}</h2>
                                @if ($address->is_default)<span class="fz-neutral-badge">پیش‌فرض</span>@endif
                            </div>
                            <p><strong>{{ $address->full_name }}</strong> · <span dir="ltr">{{ $address->phone }}</span></p>
                            <p>{{ $address->country }}، {{ $address->province }}، {{ $address->city }}</p>
                            <p>{{ $address->address }}</p>
                            @if ($address->postal_code)<p>کد پستی: {{ $address->postal_code }}</p>@endif
                            <div class="fz-address-actions">
                                <a class="fz-btn light" href="{{ route('customer.addresses.edit', $address) }}">ویرایش</a>
                                @unless ($address->is_default)
                                    <form method="POST" action="{{ route('customer.addresses.default', $address) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="fz-btn light" type="submit">انتخاب پیش‌فرض</button>
                                    </form>
                                @endunless
                                <form method="POST" action="{{ route('customer.addresses.destroy', $address) }}" onsubmit="return confirm('این نشانی حذف شود؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="fz-text-button" type="submit">حذف</button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="fz-empty"><p>هنوز نشانی‌ای ذخیره نکرده‌ای.</p></div>
            @endif

            <section class="fz-panel fz-address-create">
                <h2 class="fz-h2">افزودن نشانی</h2>
                <form method="POST" action="{{ route('customer.addresses.store') }}" class="fz-form-grid">
                    @csrf
                    <label class="fz-form-field" for="address-title"><span>عنوان نشانی (اختیاری)</span><input class="fz-input" id="address-title" name="title" value="{{ old('title') }}" maxlength="100" placeholder="خانه، محل کار"></label>
                    <label class="fz-form-field" for="address-full-name"><span>نام تحویل‌گیرنده *</span><input class="fz-input" id="address-full-name" name="full_name" value="{{ old('full_name', auth()->user()->name) }}" maxlength="255" required></label>
                    <label class="fz-form-field" for="address-phone"><span>شماره تماس *</span><input class="fz-input" id="address-phone" name="phone" value="{{ old('phone') }}" dir="ltr" maxlength="30" required></label>
                    <label class="fz-form-field" for="address-country"><span>کشور *</span><input class="fz-input" id="address-country" name="country" value="{{ old('country', 'ایران') }}" maxlength="100" required></label>
                    <label class="fz-form-field" for="address-province"><span>استان *</span><input class="fz-input" id="address-province" name="province" value="{{ old('province') }}" maxlength="100" required></label>
                    <label class="fz-form-field" for="address-city"><span>شهر *</span><input class="fz-input" id="address-city" name="city" value="{{ old('city') }}" maxlength="100" required></label>
                    <label class="fz-form-field" for="address-postal"><span>کد پستی</span><input class="fz-input" id="address-postal" name="postal_code" value="{{ old('postal_code') }}" maxlength="20" dir="ltr"></label>
                    <label class="fz-form-field fz-form-field--full" for="address-full"><span>نشانی کامل *</span><textarea class="fz-textarea" id="address-full" name="address" rows="3" maxlength="2000" required>{{ old('address') }}</textarea></label>
                    <label class="fz-check-label fz-form-field--full"><input type="checkbox" name="is_default" value="1" @checked(old('is_default'))> این نشانی، نشانی پیش‌فرض من باشد.</label>
                    @foreach (['title','full_name','phone','country','province','city','postal_code','address','is_default'] as $field)
                        @error($field)<p class="fz-field-error fz-form-field--full">{{ $message }}</p>@enderror
                    @endforeach
                    <div class="fz-form-field fz-form-field--full"><button class="fz-btn" type="submit">ذخیره نشانی</button></div>
                </form>
            </section>
        </div>
    </section>
@endsection
