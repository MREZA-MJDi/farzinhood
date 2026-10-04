@props([
'price',
'oldPrice' => null,
'discount' => null,
])

<div
    {{ $attributes->merge([
        'class' => 'price',
    ]) }}
>

    @if($oldPrice)
        <span class="price__old">
            {{ number_format($oldPrice) }}
            تومان
        </span>
    @endif

    <span class="price__current">
        {{ number_format($price) }}
        <small>تومان</small>
    </span>

    @if($discount)
        <span class="price__discount">
            {{ $discount }}٪
        </span>
    @endif

</div>
