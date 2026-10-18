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

        $seoPageTitle = trim((string) $__env->yieldContent('title', $defaultMetaTitle)) ?: $defaultMetaTitle;
        $seoPageDescription = trim((string) $__env->yieldContent('meta_description', $defaultMetaDescription)) ?: $defaultMetaDescription;
        $defaultRobots = request()->routeIs('login', 'register', 'customer.*')
            ? 'noindex, follow'
            : 'index, follow';
        $seoRobots = trim((string) $__env->yieldContent('meta_robots', $defaultRobots)) ?: $defaultRobots;
        $seoCanonical = trim((string) $__env->yieldContent('canonical_url', url()->current())) ?: url()->current();
        $seoOgType = trim((string) $__env->yieldContent('og_type', 'website')) ?: 'website';
        $seoOgTitle = trim((string) $__env->yieldContent('og_title', $seoPageTitle)) ?: $seoPageTitle;
        $seoOgDescription = trim((string) $__env->yieldContent('og_description', $seoPageDescription)) ?: $seoPageDescription;
        $seoOgImage = trim((string) $__env->yieldContent('og_image', asset('images/brand/logo.png'))) ?: asset('images/brand/logo.png');
        $seoTwitterTitle = trim((string) $__env->yieldContent('twitter_title', $seoPageTitle)) ?: $seoPageTitle;
        $seoTwitterDescription = trim((string) $__env->yieldContent('twitter_description', $seoPageDescription)) ?: $seoPageDescription;
        $seoTwitterImage = trim((string) $__env->yieldContent('twitter_image', $seoOgImage)) ?: $seoOgImage;
    @endphp

    {{-- =========================================================
        Primary SEO
    ========================================================== --}}

    <title>
        {{ $seoPageTitle }}
    </title>

    <meta
        name="description"
        content="{{ $seoPageDescription }}"
    >

    <meta
        name="robots"
        content="{{ $seoRobots }}"
    >

    <link
        rel="canonical"
        href="{{ $seoCanonical }}"
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
        content="{{ $seoOgType }}"
    >

    <meta
        property="og:site_name"
        content="{{ $siteName }}"
    >

    <meta
        property="og:title"
        content="{{ $seoOgTitle }}"
    >

    <meta
        property="og:description"
        content="{{ $seoOgDescription }}"
    >

    <meta
        property="og:url"
        content="{{ $seoCanonical }}"
    >

    <meta
        property="og:locale"
        content="fa_IR"
    >

    <meta
        property="og:image"
        content="{{ $seoOgImage }}"
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
        content="{{ $seoTwitterTitle }}"
    >

    <meta
        name="twitter:description"
        content="{{ $seoTwitterDescription }}"
    >

    <meta
        name="twitter:image"
        content="{{ $seoTwitterImage }}"
    >

    {{-- =========================================================
        Assets
    ========================================================== --}}

    @if (!app()->environment('testing'))
        <script
            defer
            src="https://cdn.jsdelivr.net/npm/alpinejs@3.17.4/dist/cdn.min.js"
        ></script>

        @vite([
            'resources/css/app.css',
            'resources/js/app.js'
        ])
    @endif

    @stack('head')
    @stack('styles')
</head>

<body class="min-h-screen bg-[var(--color-surface-muted)] text-[var(--color-text-primary)] antialiased">

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
