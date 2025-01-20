<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Shopify\Context;
use Shopify\Auth\FileSessionStorage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
