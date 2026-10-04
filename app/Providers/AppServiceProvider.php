<?php

namespace App\Providers;

use App\Services\Payment\PaymentGatewayInterface;
use App\Services\Payment\UnavailablePaymentGateway;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            PaymentGatewayInterface::class,
            function ($app) {
                $gateway = config(
                    'services.payment.gateway',
                    UnavailablePaymentGateway::class
                );

                return $app->make($gateway);
            }
        );
    }

    public function boot(): void
    {
        //
    }
}
