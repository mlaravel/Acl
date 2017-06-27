<?php

namespace iLaravel\Acl;

use iLaravel\Acl\Commands\ScanPermission;
use Illuminate\Support\ServiceProvider;

class AclServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config.php' => config_path('ilaravel-billing.php'),
        ]);

        $this->loadViewsFrom(__DIR__ . '/views', 'ilaravel-acl');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__ . '/routes.php';

        $this->mergeConfigFrom(
            __DIR__ . '/config.php', 'ilaravel-acl'
        );

        $this->loadMigrationsFrom(__DIR__ . '/migrations');

//        $this->app->make(\iLaravel\Acl\Controllers\BillingController::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                ScanPermission::class,
            ]);
        }
    }
}
