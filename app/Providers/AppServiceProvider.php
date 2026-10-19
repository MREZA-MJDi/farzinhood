<?php

namespace App\Providers;

use App\Models\SiteSetting;
use App\Services\Payment\PaymentGatewayInterface;
use App\Services\Payment\UnavailablePaymentGateway;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        View::composer('layouts.app', function ($view): void {
            $settings = SiteSetting::query()
                ->whereIn('key', [
                    'site_name',
                    'site_description',
                    'default_meta_title',
                    'default_meta_description',
                ])
                ->pluck('value', 'key')
                ->all();

            $view->with('siteSettings', $settings);
        });
    }
}
