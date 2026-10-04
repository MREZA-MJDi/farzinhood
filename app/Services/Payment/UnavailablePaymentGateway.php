<?php

namespace App\Services\Payment;

use App\Models\Order;
use RuntimeException;

class UnavailablePaymentGateway implements PaymentGatewayInterface
{
    public function purchase(Order $order): array
    {
        throw new RuntimeException(
            'درگاه پرداخت در حال حاضر پیکربندی نشده است.'
        );
    }

    public function verify(Order $order, string $authority): array
    {
        throw new RuntimeException(
            'درگاه پرداخت در حال حاضر پیکربندی نشده است.'
        );
    }

    public function refund(Order $order, int $amount): array
    {
        throw new RuntimeException(
            'درگاه پرداخت در حال حاضر پیکربندی نشده است.'
        );
    }
}
