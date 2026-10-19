<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'فرزین'))</title>
    <meta name="description" content="@yield('meta_description', 'فروشگاه فرزین؛ انتخابی دقیق برای سبک زندگی شما.')">
    <meta name="theme-color" content="#171717">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    @stack('head')
</head>
<body class="farzin-store">
    <div class="store-announcement">انتخاب‌های خاص، تجربه‌ای ساده و مطمئن</div>
    <header class="store-header">
        <div class="store-header__inner">
            <a class="store-logo" href="{{ route('home') }}" aria-label="صفحه اصلی فرزین">
                <span class="store-logo__mark">ف</span><span>فرزین</span>
            </a>
            <nav class="store-nav" aria-label="ناوبری اصلی">
                <a href="{{ route('home') }}">خانه</a>
                <a href="{{ route('shop.index') }}">فروشگاه</a>
                <a href="{{ route('blog.index') }}">مجله</a>
                <a href="{{ route('contact.index') }}">تماس با ما</a>
            </nav>
            <div class="store-header__actions">
                <a class="store-header__link" href="{{ route('shop.index') }}">جست‌وجو <span aria-hidden="true">⌕</span></a>
                @auth
                    @if (auth()->user()->isCustomer())
                        <a class="store-icon-link" href="{{ route('customer.cart.index') }}" aria-label="سبد خرید">سبد خرید</a>
                        <a class="store-icon-link" href="{{ route('customer.dashboard') }}">حساب من</a>
                    @endif
                @else
                    <a class="store-icon-link" href="{{ route('login') }}">ورود</a>
                @endauth
            </div>
        </div>
    </header>
    @if (session('success'))
        <div class="store-flash" role="status">{{ session('success') }}</div>
    @endif
    <main>@yield('content')</main>
    <footer class="store-footer">
        <div class="store-footer__inner">
            <a class="store-logo store-logo--footer" href="{{ route('home') }}"><span class="store-logo__mark">ف</span><span>فرزین</span></a>
            <p>انتخابی ماندگار؛ از کشف محصول تا تجربه خرید.</p>
            <div class="store-footer__links"><a href="{{ route('shop.index') }}">فروشگاه</a><a href="{{ route('contact.index') }}">پشتیبانی</a><a href="{{ route('blog.index') }}">مجله</a></div>
            <small>© {{ now()->year }} فرزین — تمامی حقوق محفوظ است.</small>
        </div>
    </footer>
</body>
</html>