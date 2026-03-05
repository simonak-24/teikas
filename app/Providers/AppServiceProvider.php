<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Collector;
use App\Models\Narrator;
use App\Models\Place;
use App\Observers\CollectorObserver;
use App\Observers\NarratorObserver;
use App\Observers\PlaceObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('items_per_page', function() {
            return 20;
        });
        $this->app->singleton('number_of_pages', function() {
            return 9;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Collector::observe(CollectorObserver::class);
        Narrator::observe(NarratorObserver::class);
        Place::observe(PlaceObserver::class);
    }
}
