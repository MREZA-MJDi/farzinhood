@props([
'columns' => 4,
])

@php
    $columns = in_array((int) $columns, [1, 2, 3, 4], true)
        ? (int) $columns
        : 4;
@endphp

<div
    {{ $attributes->merge([
        'class' => 'product-grid product-grid--' . $columns,
    ]) }}
>
    {{ $slot }}
</div>
