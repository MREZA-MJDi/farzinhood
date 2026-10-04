@props([
'paginator',
])

@if($paginator->hasPages())

    <nav
        class="pagination"
        aria-label="صفحه‌بندی"
    >

        <div class="pagination__list">

            @if($paginator->onFirstPage())

                <span
                    class="pagination__item is-disabled"
                    aria-disabled="true"
                >
                    ←
                </span>

            @else

                <a
                    href="{{ $paginator->previousPageUrl() }}"
                    class="pagination__item"
                    rel="prev"
                    aria-label="صفحه قبلی"
                >
                    ←
                </a>

            @endif


            @foreach($elements as $element)

                @if(is_string($element))

                    <span class="pagination__item">
                        {{ $element }}
                    </span>

                @elseif(is_array($element))

                    @foreach($element as $page => $url)

                        @if($page == $paginator->currentPage())

                            <span
                                class="pagination__item is-active"
                                aria-current="page"
                            >
                                {{ $page }}
                            </span>

                        @else

                            <a
                                href="{{ $url }}"
                                class="pagination__item"
                            >
                                {{ $page }}
                            </a>

                        @endif

                    @endforeach

                @endif

            @endforeach


            @if($paginator->hasMorePages())

                <a
                    href="{{ $paginator->nextPageUrl() }}"
                    class="pagination__item"
                    rel="next"
                    aria-label="صفحه بعدی"
                >
                    →
                </a>

            @else

                <span
                    class="pagination__item is-disabled"
                    aria-disabled="true"
                >
                    →
                </span>

            @endif

        </div>

    </nav>

@endif
