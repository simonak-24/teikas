<?php

namespace App\Observers;

use App\Models\Narrator;

class NarratorObserver
{
    /**
     * Handle the Narrator "created" event.
     */
    public function created(Narrator $narrator): void
    {
        //
    }

    /**
     * Handle the Narrator "updated" event.
     */
    public function updated(Narrator $narrator): void
    {
        //
    }

    /**
     * Handle the Narrator "deleted" event.
     */
    public function deleted(Narrator $narrator): void
    {
        $narrator->legends()->update(['narrator_id' => 1]);
    }

    /**
     * Handle the Narrator "restored" event.
     */
    public function restored(Narrator $narrator): void
    {
        //
    }

    /**
     * Handle the Narrator "force deleted" event.
     */
    public function forceDeleted(Narrator $narrator): void
    {
        //
    }
}
