<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#090909">
    @php
        $siteName = $siteSettings['site_name'] ?? config('app.name', 'Farzin');
        $defaultMetaTitle = $siteSettings['default_meta_title'] ?? null;
        $defaultMetaDescription = $siteSettings['default_meta_description']
            ?? $siteSettings['site_description']
            ?? 'فرزین؛ انتخابی دقیق برای خانه و آشپزخانه‌ی امروز.';

        $pageTitle = request()->routeIs('home') && filled($defaultMetaTitle)
            ? $defaultMetaTitle
            : trim($__env->yieldContent('title', $siteName));

        $usesGlobalDescription = request()->routeIs('home', 'shop.index');
        $pageDescription = $usesGlobalDescription
            ? $defaultMetaDescription
            : trim($__env->yieldContent('meta_description', $defaultMetaDescription));
    @endphp
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    @hasSection('canonical')
        <link rel="canonical" href="@yield('canonical')">
    @endif
    @stack('meta')
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/js/app.js'])
        @if (request()->routeIs('home'))
            @vite(['resources/js/home.js'])
        @endif
        @if (request()->routeIs(
            'home',
            'shop.*',
            'products.*',
            'categories.*',
            'blog.*',
            'contact.*',
            'customer.*'
        ))
            @vite(['resources/css/home.css'])
        @endif
    @endif
</head>
<body class="{{ request()->routeIs('home') ? 'farzin-home-page' : 'farzin-page' }}">
    <a class="farzin-skip-link" href="#main-content">رفتن به محتوای اصلی</a>
    @include('partials.navbar')
    <main id="main-content">
        @include('partials.flash')
        @yield('content')
    </main>
    @include('partials.footer')
    @stack('scripts')
</body>
</html>
