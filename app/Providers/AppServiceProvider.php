<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ConfigurationService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the ConfigurationService as a singleton
        $this->app->singleton(ConfigurationService::class, function ($app) {
            return new ConfigurationService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
