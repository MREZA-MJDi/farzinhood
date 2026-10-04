@props([
'number',
'title',
])

<section class="checkout-section">

    <header class="checkout-section__header">

        <span
            class="checkout-section__number"
            aria-hidden="true"
        >
            {{ $number }}
        </span>

        <h2 class="checkout-section__title">
            {{ $title }}
        </h2>

    </header>

    {{ $slot }}

</section>
