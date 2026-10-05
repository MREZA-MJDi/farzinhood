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
                    href="{{ $paginator->previousPageUrl() }}#shop-products"
                    rel="prev"
                    aria-label="صفحه قبلی"
                >←</a>
            @endif

            @php
                $lastPage = $paginator->lastPage();
                $currentPage = $paginator->currentPage();
            @endphp

            @for($page = 1; $page <= $lastPage; $page++)
                @if(
                    $page === 1
                    || $page === $lastPage
                    || abs($page - $currentPage) <= 1
                )
                    @if($page === $currentPage)
                        <span class="shop-pagination__page is-active" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="shop-pagination__page" href="{{ $paginator->url($page) }}#shop-products">{{ $page }}</a>
                    @endif
                @elseif(
                    $page === 2 && $currentPage > 3
                    || $page === $lastPage - 1 && $currentPage < $lastPage - 2
                )
                    <span class="shop-pagination__ellipsis" aria-hidden="true">…</span>
                @endif
            @endfor

            @if($paginator->hasMorePages())
                <a
                    class="shop-pagination__arrow"
                    href="{{ $paginator->nextPageUrl() }}#shop-products"
                    rel="next"
                    aria-label="صفحه بعدی"
                >→</a>
            @else
                <span class="shop-pagination__arrow is-disabled" aria-disabled="true">→</span>
            @endif
        </div>
    </nav>
@endif
