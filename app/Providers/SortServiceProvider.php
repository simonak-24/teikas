<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\SortService;

class SortServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(SortService::class, function ($app) {
            return new SortService();
        });
    }
}
