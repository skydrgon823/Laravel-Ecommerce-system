<?php

namespace Botble\SslCommerz\Providers;

use Botble\Base\Supports\Helper;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;

class SslCommerzServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * @return void
     * @throws BindingResolutionException
     */
    public function boot()
    {
        if (is_plugin_active('payment')) {
            $this->setNamespace('plugins/sslcommerz')
                ->loadAndPublishConfigurations(['sslcommerz'])
                ->loadRoutes(['web'])
                ->loadAndPublishViews()
                ->publishAssets();

            $this->app->register(HookServiceProvider::class);

            $storeID = get_payment_setting('store_id', SSLCOMMERZ_PAYMENT_METHOD_NAME);
            $storePassword = get_payment_setting('store_password', SSLCOMMERZ_PAYMENT_METHOD_NAME);
            $isSandbox = get_payment_setting('mode', SSLCOMMERZ_PAYMENT_METHOD_NAME) == 0;
            $apiURL = $isSandbox ? 'https://sandbox.sslcommerz.com' : 'https://securepay.sslcommerz.com';

            $this->app->make('config')->set([
                'plugins.sslcommerz.sslcommerz.apiCredentials.store_id'       => $storeID,
                'plugins.sslcommerz.sslcommerz.apiCredentials.store_password' => $storePassword,
                'plugins.sslcommerz.sslcommerz.connect_from_localhost'        => $isSandbox,
                'plugins.sslcommerz.sslcommerz.apiDomain'                     => $apiURL,
            ]);
        }
    }
}
