@php
    $href = $href ?? route('home');
    $label = $label ?? 'بازگشت';
@endphp

<a href="{{ $href }}" class="store-page-back">
    <span aria-hidden="true">→</span>
    <span>{{ $label }}</span>
</a>
