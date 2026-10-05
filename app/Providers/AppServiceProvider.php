<?php

namespace App\Providers;

use App\Services\Payment\PaymentGatewayInterface;
use App\Services\Payment\UnavailablePaymentGateway;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            PaymentGatewayInterface::class,
            function () {
                return match (
                    config('services.payment.default', 'unavailable')
                ) {
                    'unavailable' => app(
                        UnavailablePaymentGateway::class
                    ),

                    default => throw new InvalidArgumentException(
                        'درگاه پرداخت انتخاب‌شده پشتیبانی نمی‌شود.'
                    ),
                };
            }
        );
    }

    public function boot(): void
    {
        //
    }
}
