@props([
'subtotal' => 0,
'shipping' => 0,
'discount' => 0,
'total' => 0,
])

<div class="cart-summary">

    <div class="cart-summary__row">
        <span>جمع محصولات</span>

        <strong>
            {{ number_format($subtotal) }}
            تومان
        </strong>
    </div>

    <div class="cart-summary__row">
        <span>ارسال</span>

        <strong>
            @if($shipping > 0)
                {{ number_format($shipping) }}
                تومان
            @else
                رایگان
            @endif
        </strong>
    </div>

    @if($discount > 0)

        <div class="cart-summary__row cart-summary__row--discount">

            <span>
                تخفیف
            </span>

            <strong>
                -{{ number_format($discount) }}
                تومان
            </strong>

        </div>

    @endif

    <div class="cart-summary__row cart-summary__row--total">

        <span>
            مبلغ نهایی
        </span>

        <strong>
            {{ number_format($total) }}
            تومان
        </strong>

    </div>

</div>
