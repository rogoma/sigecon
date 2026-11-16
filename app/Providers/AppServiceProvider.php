<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        Schema::defaultStringLength(191);

        /**
         * Agregamos la clase que maneja el menu para todas las vistas
         * Todas las veces que se ejecute una vista el metodo MenuComposer@composer
         * se ejecutará
         */
        View::composer(
            '*', 'App\Http\View\Composer\MenuComposer'
        );

        $this->configureRateLimiting();
    }

    /**
     * Configure the rate limiters for the application.
     * (antes estaba en RouteServiceProvider, que ya no existe en Laravel 11+)
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });
    }
}
