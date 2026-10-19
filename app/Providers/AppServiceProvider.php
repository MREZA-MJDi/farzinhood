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
            $settings = \App\Models\SiteSetting::query()
                ->whereIn('key', ['site_name', 'default_meta_title', 'default_meta_description'])
                ->pluck('value', 'key');

            $user = auth()->user();
            $itemCount = 0;

            if ($user && $user->isCustomer()) {
                $cart = $user->cart()
                    ->withSum('items as header_item_count', 'quantity')
                    ->first();

                $itemCount = (int) ($cart?->header_item_count ?? 0);
            }

            $view->with([
                'siteName' => $settings->get('site_name') ?: 'فرزین',
                'defaultMetaTitle' => $settings->get('default_meta_title') ?: 'فرزین | تجهیزات مدرن آشپزخانه',
                'defaultMetaDescription' => $settings->get('default_meta_description') ?: 'خرید محصولات مدرن آشپزخانه از فروشگاه فرزین؛ اطلاعات شفاف و انتخاب مطمئن.',
                'headerCartCount' => $itemCount,
            ]);
        });
    }
}
