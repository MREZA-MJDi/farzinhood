<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function show(): Response
    {
        $sitemapPath = route('sitemap', [], false);
        $sitemapUrl = rtrim((string) config('app.url'), '/') . '/' . ltrim($sitemapPath, '/');

        $contents = implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin/',
            'Sitemap: ' . $sitemapUrl,
            '',
        ]);

        return response($contents, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
