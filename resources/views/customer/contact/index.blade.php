@extends('layouts.app')

@section('title', 'تماس با فرزین | پشتیبانی')

@section('content')
<section class="fh-page">
    <header class="fh-page__hero"><p class="fh-eyebrow fh-eyebrow--dark">FARZIN / CONTACT</p><h1>در تماس باشیم</h1><p>سؤال یا پیشنهادی دارید؟ پیام خود را برای تیم فرزین ارسال کنید.</p></header>
    <div class="fh-page__inner fh-form-page">
        <form action="{{ route('contact.store') }}" method="POST" class="fh-form-panel">
            @csrf
            <label for="name">نام و نام خانوادگی *</label><input id="name" name="name" value="{{ old('name') }}" autocomplete="name" required maxlength="255">
            <label for="email">ایمیل *</label><input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required maxlength="255">
            <label for="phone">شماره تماس</label><input id="phone" name="phone" value="{{ old('phone') }}" autocomplete="tel" maxlength="30">
            <label for="subject">موضوع *</label><input id="subject" name="subject" value="{{ old('subject') }}" required maxlength="255">
            <label for="message">پیام شما *</label><textarea id="message" name="message" rows="6" required minlength="10" maxlength="5000">{{ old('message') }}</textarea>
            @foreach (['name','email','phone','subject','message'] as $field) @error($field)<p class="fh-form-error">{{ $message }}</p>@enderror @endforeach
            <button class="fh-button fh-button--dark" type="submit">ارسال پیام <span aria-hidden="true">↗</span></button>
        </form>
    </div>
</section>
@endsection
