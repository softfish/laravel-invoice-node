<?php

namespace Feikwok\InvoiceNova;

use Feikwok\InvoiceNova\Providers\EventServiceProvider;
use Illuminate\Support\ServiceProvider;

class InvoiceNovaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'invoice-nova');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->defineAssetPublishing();
        $this->mergeConfigFrom(__DIR__.'/../config/invoice-nova.php', 'invoice-nova');

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/../routes/api.php';
        include __DIR__.'/../routes/web.php';

        $this->app->register(EventServiceProvider::class);

    }

    /**
     * Define the asset publishing configuration.
     *
     * @return void
     */
    private function defineAssetPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/publish' => public_path('vendor/feikwok/laravel-invoice-nova'),
            ], 'invoice-nova');
        }
    }
}
