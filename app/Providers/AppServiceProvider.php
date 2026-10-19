<?php

namespace App\Providers;

use App\Services\Payment\PaymentGatewayInterface;
use App\Services\Payment\UnavailablePaymentGateway;
use Illuminate\Support\Facades\View;
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
        View::composer('layouts.storefront', function ($view): void {
            $user = auth()->user();
            $itemCount = 0;

            if ($user && $user->isCustomer()) {
                $cart = $user->cart()
                    ->withSum('items as header_item_count', 'quantity')
                    ->first();

                $itemCount = (int) ($cart?->header_item_count ?? 0);
            }

            $view->with('headerCartCount', $itemCount);
        });
    }
}
