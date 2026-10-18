<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    @php
        $seoSettings = \App\Models\SiteSetting::query()
            ->whereIn('key', [
                'site_name',
                'site_description',
                'default_meta_title',
                'default_meta_description',
            ])
            ->pluck('value', 'key');

        $siteName = trim((string) $seoSettings->get('site_name', 'فرزین')) ?: 'فرزین';
        $defaultMetaTitle = trim((string) $seoSettings->get('default_meta_title', ''))
            ?: $siteName . ' | فروشگاه آنلاین';
        $defaultMetaDescription = trim((string) $seoSettings->get('default_meta_description', ''))
            ?: trim((string) $seoSettings->get('site_description', ''))
            ?: $siteName . ' | فروشگاه آنلاین محصولات با کیفیت';
    @endphp

    {{-- =========================================================
        Primary SEO
    ========================================================== --}}

    <title>
        @yield('title', $defaultMetaTitle)
    </title>

    <meta
        name="description"
        content="@yield('meta_description', $defaultMetaDescription)"
    >

    <meta
        name="robots"
        content="@yield('meta_robots', 'index, follow')"
    >

    <link
        rel="canonical"
        href="@yield('canonical_url', url()->current())"
    >

    {{-- =========================================================
        Brand / Browser
    ========================================================== --}}

    <meta
        name="theme-color"
        content="#0d1b3d"
    >

    <meta
        name="application-name"
        content="{{ $siteName }}"
    >

    {{-- Favicon --}}
    <link
        rel="icon"
        type="image/png"
        href="{{ asset('images/brand/logo.png') }}"
    >

    <link
        rel="apple-touch-icon"
        href="{{ asset('images/brand/logo.png') }}"
    >

    {{-- =========================================================
        Open Graph
    ========================================================== --}}

    <meta
        property="og:type"
        content="@yield('og_type', 'website')"
    >

    <meta
        property="og:site_name"
        content="{{ $siteName }}"
    >

    <meta
        property="og:title"
        content="@yield('og_title', trim($__env->yieldContent('title', $defaultMetaTitle)))"
    >

    <meta
        property="og:description"
        content="@yield('og_description', trim($__env->yieldContent('meta_description', $defaultMetaDescription)))"
    >

    <meta
        property="og:url"
        content="@yield('canonical_url', url()->current())"
    >

    <meta
        property="og:locale"
        content="fa_IR"
    >

    <meta
        property="og:image"
        content="@yield('og_image', asset('images/brand/logo.png'))"
    >

    {{-- =========================================================
        Twitter / Social
    ========================================================== --}}

    <meta
        name="twitter:card"
        content="summary_large_image"
    >

    <meta
        name="twitter:title"
        content="@yield('twitter_title', trim($__env->yieldContent('title', 'فرزین | فروشگاه آنلاین')))"
    >

    <meta
        name="twitter:description"
        content="@yield('twitter_description', trim($__env->yieldContent('meta_description', 'فرزین | فروشگاه آنلاین محصولات با کیفیت')))"
    >

    <meta
        name="twitter:image"
        content="@yield('twitter_image', asset('images/brand/logo.png'))"
    >

    {{-- =========================================================
        Assets
    ========================================================== --}}

    @if (!app()->environment('testing'))
        @vite([
            'resources/css/app.css',
            'resources/js/app.js'
        ])
    @endif

    @stack('styles')
</head>

<body class="min-h-screen bg-[#f7f8fa] text-gray-900 antialiased">

{{-- =========================================================
    Navbar
========================================================== --}}

@include('partials.navbar')


{{-- =========================================================
    Flash Messages
========================================================== --}}

@include('partials.flash')


{{-- =========================================================
    Main Content
========================================================== --}}

<main class="min-h-[70vh]">
    @yield('content')
</main>


{{-- =========================================================
    Footer
========================================================== --}}

@include('partials.footer')


{{-- =========================================================
    Page Scripts
========================================================== --}}

@stack('scripts')

</body>

</html>
