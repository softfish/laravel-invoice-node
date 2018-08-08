<?php

namespace Feikwok\InvoiceNode;

use Feikwok\InvoiceNode\Providers\EventServiceProvider;
use Illuminate\Support\ServiceProvider;

class InvoiceNodeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'invoice-node');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->defineAssetPublishing();
        $this->mergeConfigFrom(__DIR__ . '/../config/invoice-node.php', 'invoice-node');

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
                __DIR__ . '/../resources/publish' => public_path('vendor/feikwok/laravel-invoice-node'),
            ], 'invoice-node');
        }
    }
}
