<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
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
    }
}
