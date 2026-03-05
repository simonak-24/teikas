<?php

namespace App\Observers;

use App\Models\Collector;

class CollectorObserver
{
    /**
     * Handle the Collector "created" event.
     */
    public function created(Collector $collector): void
    {
        //
    }

    /**
     * Handle the Collector "updated" event.
     */
    public function updated(Collector $collector): void
    {
        //
    }

    /**
     * Handle the Collector "deleted" event.
     */
    public function deleted(Collector $collector): void
    {
        $collector->legends()->update(['collector_id' => 1]);
    }

    /**
     * Handle the Collector "restored" event.
     */
    public function restored(Collector $collector): void
    {
        //
    }

    /**
     * Handle the Collector "force deleted" event.
     */
    public function forceDeleted(Collector $collector): void
    {
        //
    }
}
