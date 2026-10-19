<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#101112">
    <title>@yield('title', config('app.name', 'Farzin'))</title>
    <meta name="description" content="@yield('meta_description', 'فروشگاه فرزین؛ انتخاب حرفه‌ای هود و سینک آشپزخانه')">
    <link rel="icon" type="image/png" href="{{ asset('images/brand/logo.png') }}">
    @if (!app()->environment('testing'))
        @vite([
            'resources/css/app.css',
            'resources/js/app.js',
        ])
    @endif
    @stack('head')
</head>
<body class="farzin-storefront">
    <a class="sf-skip-link" href="#main-content">رفتن به محتوای اصلی</a>

    <header class="sf-header">
        <div class="sf-header__inner">
            <a class="sf-brand" href="{{ route('home') }}" aria-label="صفحه اصلی فرزین">
                <span class="sf-brand__mark" aria-hidden="true">F</span>
                <span class="sf-brand__word">FARZIN<span>HOME COLLECTION</span></span>
            </a>

            <nav class="sf-nav" aria-label="ناوبری اصلی">
                <a href="{{ route('home') }}" @class(['is-active' => request()->routeIs('home')])>خانه</a>
                <a href="{{ route('shop.index') }}" @class(['is-active' => request()->routeIs('shop.*')])>فروشگاه</a>
                <a href="{{ route('blog.index') }}" @class(['is-active' => request()->routeIs('blog.*')])>مجله</a>
                <a href="{{ route('contact.index') }}" @class(['is-active' => request()->routeIs('contact.*')])>تماس با ما</a>
            </nav>

            <div class="sf-header__actions">
                @if (auth()->check() && auth()->user()->isCustomer())
                    <a class="sf-icon-link" href="{{ route('customer.cart.index') }}" aria-label="سبد خرید">سبد خرید</a>
                    <a class="sf-account-link" href="{{ route('customer.dashboard') }}">حساب من</a>
                @else
                    <a class="sf-account-link" href="{{ route('login') }}">ورود / ثبت‌نام</a>
                @endif
            </div>
        </div>
    </header>

    <main id="main-content">
        @include('partials.flash')
        @yield('content')
    </main>

    <footer class="sf-footer">
        <div class="sf-footer__inner">
            <a class="sf-footer__brand" href="{{ route('home') }}">FARZIN</a>
            <p>انتخابی دقیق برای آشپزخانه‌ای ماندگار.</p>
            <nav aria-label="پیوندهای پایانی">
                <a href="{{ route('shop.index') }}">فروشگاه</a>
                <a href="{{ route('blog.index') }}">مجله</a>
                <a href="{{ route('contact.index') }}">پشتیبانی</a>
            </nav>
            <small>© {{ date('Y') }} Farzin. تمامی حقوق محفوظ است.</small>
        </div>
    </footer>
    @stack('scripts')
</body>
</html>
