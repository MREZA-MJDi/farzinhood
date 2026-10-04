@props([
'variant' => 'neutral',
'size' => 'md',
'dot' => false,
])

@php
    $classes = [
        'badge',
        "badge--{$variant}",
        $size !== 'md' ? "badge--{$size}" : null,
    ];
@endphp

<span
    {{ $attributes->merge([
        'class' => implode(' ', array_filter($classes)),
    ]) }}
>
    @if($dot)
        <span
            class="badge__dot"
            aria-hidden="true"
        ></span>
    @endif

    {{ $slot }}
</span>
