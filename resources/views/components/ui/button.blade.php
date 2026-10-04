@props([
'type' => 'button',
'variant' => 'primary',
'size' => 'md',
'full' => false,
'loading' => false,
'disabled' => false,
'icon' => null,
'iconPosition' => 'start',
])

@php
    $classes = [
        'btn',
        "btn--{$variant}",
        "btn--{$size}",
        $full ? 'btn--full' : null,
    ];
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => implode(' ', array_filter($classes)),
    ]) }}
    @disabled($disabled || $loading)
    @if($loading)
    aria-busy="true"
    @endif
>
    @if($loading)

        <span
            class="btn__icon"
            aria-hidden="true"
        >
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >
                <circle
                    cx="12"
                    cy="12"
                    r="8"
                    stroke-opacity=".25"
                />
                <path d="M20 12A8 8 0 0 0 12 4" />
            </svg>
        </span>

        <span>
            در حال انجام...
        </span>

    @else

        @if($icon && $iconPosition === 'start')
            <span
                class="btn__icon"
                aria-hidden="true"
            >
                {!! $icon !!}
            </span>
        @endif

        <span>
            {{ $slot }}
        </span>

        @if($icon && $iconPosition === 'end')
            <span
                class="btn__icon"
                aria-hidden="true"
            >
                {!! $icon !!}
            </span>
        @endif

    @endif
</button>
