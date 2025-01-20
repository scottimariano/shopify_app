<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use PHPShopify\ShopifySDK;

class ShopifyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ShopifySDK::class, function ($app) {
            $config = [
                'ShopUrl' => env('SHOPIFY_SHOP_URL'),
                'ApiKey' => env('SHOPIFY_API_KEY'),
                'Password' => env('SHOPIFY_API_PASSWORD'),
            ];
            return new ShopifySDK($config);
        });
    }

    public function boot()
    {
      
    }
}