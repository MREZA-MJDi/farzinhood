@props([
'name' => 'quantity',
'value' => 1,
'min' => 1,
'max' => 99,
])

<div
    class="quantity"
    data-quantity
>
    <button
        type="button"
        class="quantity__btn"
        data-quantity-decrease
        aria-label="کاهش تعداد"
        @disabled($value <= $min)
    >
    −
    </button>

    <input
        type="number"
        name="{{ $name }}"
        value="{{ $value }}"
        min="{{ $min }}"
        max="{{ $max }}"
        class="quantity__value"
        data-quantity-input
        inputmode="numeric"
        aria-label="تعداد"
    >

    <button
        type="button"
        class="quantity__btn"
        data-quantity-increase
        aria-label="افزایش تعداد"
        @disabled($value >= $max)
        >
        +
    </button>
</div>
