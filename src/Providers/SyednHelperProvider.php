<?php

namespace Syedn\Helper\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Syedn\Helper\Middlewares\PreventBackHistory;

class PreventBackHistoryServiceProvider extends ServiceProvider
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
    public function boot(Router $router): void
    {
        // Programmatically register the middleware alias globally
        $router->aliasMiddleware('no.back', PreventBackHistory::class);
    }
}