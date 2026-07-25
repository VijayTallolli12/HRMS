<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

use App\Http\Middleware\IdentifyTenant;

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
        // Register middleware alias for tenant identification
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('identify.tenant', IdentifyTenant::class);
    }
}
