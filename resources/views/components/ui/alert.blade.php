@props([
'type' => 'info',
'title' => null,
'icon' => null,
])

@php
    $iconMap = [
        'success' => '✓',
        'danger' => '×',
        'warning' => '!',
        'info' => 'i',
    ];
@endphp

<div
    {{ $attributes->merge([
        'class' => "alert alert--{$type}",
    ]) }}
    role="alert"
>
    <span
        class="alert__icon"
        aria-hidden="true"
    >
        {!! $icon ?? ($iconMap[$type] ?? 'i') !!}
    </span>

    <div class="alert__content">

        @if($title)
            <div class="alert__title">
                {{ $title }}
            </div>
        @endif

        <div class="alert__text">
            {{ $slot }}
        </div>

    </div>
</div>
