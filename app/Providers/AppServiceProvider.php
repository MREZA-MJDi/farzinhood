<?php

namespace App\Providers;

use App\Services\Payment\PaymentGatewayInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            PaymentGatewayInterface::class,
            function () {
                return app(
//                    \App\Services\Payment\DummyPaymentGateway::class
                );
            }
        );
    }

    public function boot(): void
    {
        //
    }
}
