<?php

namespace App\Providers;
use App\Libraries\SHAHasher;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class SHAHashServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton('hash', function ($app) {
            return new SHAHasher;
        });

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return array('hash');
    }

}

?>