<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [
            ['loc' => $this->absoluteRoute('home'), 'lastmod' => null],
            ['loc' => $this->absoluteRoute('shop.index'), 'lastmod' => null],
            ['loc' => $this->absoluteRoute('blog.index'), 'lastmod' => null],
            ['loc' => $this->absoluteRoute('contact.index'), 'lastmod' => null],
        ];

        Category::query()
            ->where('is_active', true)
            ->where(fn (Builder $query) => $query->whereNull('noindex')->orWhere('noindex', false))
            ->select(['id', 'slug', 'canonical_url', 'updated_at'])
            ->orderBy('id')
            ->chunkById(250, function ($categories) use (&$urls): void {
                foreach ($categories as $category) {
                    $fallback = $this->absoluteRoute('categories.show', ['category' => $category->slug]);
                    $location = $this->canonicalLocation($category->canonical_url, $fallback);

                    if ($location) {
                        $urls[] = ['loc' => $location, 'lastmod' => $category->updated_at?->toAtomString()];
                    }
                }
            });

        Product::query()
            ->where('is_active', true)
            ->whereHas('category', fn (Builder $query) => $query->where('is_active', true))
            ->where(fn (Builder $query) => $query->whereNull('noindex')->orWhere('noindex', false))
            ->select(['id', 'slug', 'canonical_url', 'updated_at'])
            ->orderBy('id')
            ->chunkById(250, function ($products) use (&$urls): void {
                foreach ($products as $product) {
                    $fallback = $this->absoluteRoute('products.show', ['product' => $product->slug]);
                    $location = $this->canonicalLocation($product->canonical_url, $fallback);

                    if ($location) {
                        $urls[] = ['loc' => $location, 'lastmod' => $product->updated_at?->toAtomString()];
                    }
                }
            });

        BlogCategory::query()
            ->where('is_active', true)
            ->where(fn (Builder $query) => $query->whereNull('noindex')->orWhere('noindex', false))
            ->select(['id', 'slug', 'canonical_url', 'updated_at'])
            ->orderBy('id')
            ->chunkById(250, function ($categories) use (&$urls): void {
                foreach ($categories as $category) {
                    $fallback = $this->absoluteRoute('blog.index', ['category' => $category->slug]);
                    $location = $this->canonicalLocation($category->canonical_url, $fallback);

                    if ($location) {
                        $urls[] = ['loc' => $location, 'lastmod' => $category->updated_at?->toAtomString()];
                    }
                }
            });

        Post::query()
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->whereHas('category', fn (Builder $query) => $query->where('is_active', true))
            ->where(fn (Builder $query) => $query->whereNull('noindex')->orWhere('noindex', false))
            ->select(['id', 'slug', 'canonical_url', 'updated_at'])
            ->orderBy('id')
            ->chunkById(250, function ($posts) use (&$urls): void {
                foreach ($posts as $post) {
                    $fallback = $this->absoluteRoute('blog.show', ['post' => $post->slug]);
                    $location = $this->canonicalLocation($post->canonical_url, $fallback);

                    if ($location) {
                        $urls[] = ['loc' => $location, 'lastmod' => $post->updated_at?->toAtomString()];
                    }
                }
            });

        $urls = collect($urls)->unique('loc')->values()->all();

        return response()
            ->view('seo.sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml; charset=UTF-8')
            ->header('X-Content-Type-Options', 'nosniff');
    }

    private function absoluteRoute(string $name, array $parameters = []): string
    {
        $baseUrl = rtrim((string) config('app.url'), '/');
        $path = route($name, $parameters, false);

        return $baseUrl . '/' . ltrim($path, '/');
    }

    private function canonicalLocation(?string $canonicalUrl, string $fallback): ?string
    {
        if (! filled($canonicalUrl)) {
            return $fallback;
        }

        $canonicalHost = parse_url($canonicalUrl, PHP_URL_HOST);
        $siteHost = parse_url((string) config('app.url'), PHP_URL_HOST);

        if (! is_string($canonicalHost) || ! is_string($siteHost)) {
            return null;
        }

        return strcasecmp($canonicalHost, $siteHost) === 0
            ? $canonicalUrl
            : null;
    }
}
