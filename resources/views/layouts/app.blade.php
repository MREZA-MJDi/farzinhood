<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}"><meta name="theme-color" content="#090909">
<title>@yield('title', config('app.name', 'فرزین'))</title>
<meta name="description" content="@yield('meta_description', 'فرزین؛ انتخابی دقیق برای خانه و آشپزخانه‌ی امروز.')">
@hasSection('canonical')<link rel="canonical" href="@yield('canonical')">@endif
@stack('meta')
@vite(['resources/js/app.js'])
</head>
<body class="{{ request()->routeIs('home') ? 'farzin-home-page' : 'farzin-page' }}">
<a class="farzin-skip-link" href="#main-content">رفتن به محتوای اصلی</a>
@include('partials.navbar')
<main id="main-content">@include('partials.flash')@yield('content')</main>
@include('partials.footer')
@stack('scripts')
</body>
</html>
