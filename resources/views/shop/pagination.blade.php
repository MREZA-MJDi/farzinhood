@props([
    'paginator',
])

@if($paginator->hasPages())
    <nav class="shop-pagination" aria-label="صفحه‌بندی محصولات">
        <div class="shop-pagination__meta">
            نمایش {{ number_format($paginator->firstItem()) }} تا {{ number_format($paginator->lastItem()) }} از {{ number_format($paginator->total()) }} محصول
        </div>

        <div class="shop-pagination__controls">
            @if($paginator->onFirstPage())
                <span class="shop-pagination__arrow is-disabled" aria-disabled="true">←</span>
            @else
                <a
                    class="shop-pagination__arrow"
                    href="{{ $paginator->previousPageUrl() }}"
                    rel="prev"
                    aria-label="صفحه قبلی"
                >←</a>
            @endif

            @foreach($paginator->getUrlRange(max(1, $paginator->currentPage() - 1), min($paginator->lastPage(), $paginator->currentPage() + 1)) as $page => $url)
                @if($page === $paginator->currentPage())
                    <span class="shop-pagination__page is-active" aria-current="page">{{ $page }}</span>
                @else
                    <a class="shop-pagination__page" href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            @if($paginator->hasMorePages())
                <a
                    class="shop-pagination__arrow"
                    href="{{ $paginator->nextPageUrl() }}"
                    rel="next"
                    aria-label="صفحه بعدی"
                >→</a>
            @else
                <span class="shop-pagination__arrow is-disabled" aria-disabled="true">→</span>
            @endif
        </div>
    </nav>
@endif
