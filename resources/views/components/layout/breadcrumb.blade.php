@props([
'items' => [],
'homeLabel' => 'خانه',
'homeHref' => null,
])

@php
    $homeHref ??= route('home');
@endphp

@if(count($items) > 0)

    <nav
        {{ $attributes->merge([
            'class' => 'breadcrumb',
        ]) }}
        aria-label="مسیر صفحه"
    >

        <ol class="breadcrumb__list">

            {{-- Home --}}
            <li class="breadcrumb__item">

                <a
                    href="{{ $homeHref }}"
                    class="breadcrumb__link"
                >
                    {{ $homeLabel }}
                </a>

            </li>


            @foreach($items as $index => $item)

                <li
                    class="breadcrumb__item"
                    aria-hidden="true"
                >
                    <span class="breadcrumb__separator">
                        /
                    </span>
                </li>


                <li class="breadcrumb__item">

                    @if(
                        is_array($item)
                        && !empty($item['href'])
                        && $index !== array_key_last($items)
                    )

                        <a
                            href="{{ $item['href'] }}"
                            class="breadcrumb__link"
                        >
                            {{ $item['label'] ?? '' }}
                        </a>

                    @else

                        <span
                            class="breadcrumb__current"
                            aria-current="page"
                        >
                            {{ is_array($item)
                                ? ($item['label'] ?? '')
                                : $item
                            }}
                        </span>

                    @endif

                </li>

            @endforeach

        </ol>

    </nav>

@endif
