@props([
'value',
'title',
'description' => null,
'checked' => false,
])

<label class="payment-method">

    <input
        type="radio"
        name="payment_method"
        value="{{ $value }}"
        class="payment-method__input"
        @checked($checked)
    >

    <span class="payment-method__content">

        <span
            class="payment-method__radio"
            aria-hidden="true"
        ></span>

        <span class="payment-method__info">

            <span class="payment-method__title">
                {{ $title }}
            </span>

            @if($description)
                <span class="payment-method__description">
                    {{ $description }}
                </span>
            @endif

        </span>

    </span>

</label>
