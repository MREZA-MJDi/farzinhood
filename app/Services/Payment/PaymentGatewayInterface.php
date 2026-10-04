<?php

namespace App\Services\Payment;

use App\Models\Order;

interface PaymentGatewayInterface
{
    public function purchase(
        Order $order
    ): array;

    public function verify(
        Order $order,
        string $authority
    ): array;

    public function refund(
        Order $order,
        int $amount
    ): array;
}
