<?php

namespace App\Observers;

use App\Events\AttributeOverlayUpdate;
use App\Models\Overlay;

class OverlayObserver
{
    /**
     * Handle the Overlay "created" event.
     *
     * @param  \App\Models\Overlay  $overlay
     * @return void
     */
    public function created(Overlay $overlay)
    {
        //
    }

    /**
     * Handle the Overlay "updated" event.
     *
     * @param  \App\Models\Overlay  $overlay
     * @return void
     */
    public function updated(Overlay $overlay)
    {
        AttributeOverlayUpdate::dispatch($overlay);
    }

    /**
     * Handle the Overlay "deleted" event.
     *
     * @param  \App\Models\Overlay  $overlay
     * @return void
     */
    public function deleted(Overlay $overlay)
    {
        //
    }

    /**
     * Handle the Overlay "restored" event.
     *
     * @param  \App\Models\Overlay  $overlay
     * @return void
     */
    public function restored(Overlay $overlay)
    {
        //
    }

    /**
     * Handle the Overlay "force deleted" event.
     *
     * @param  \App\Models\Overlay  $overlay
     * @return void
     */
    public function forceDeleted(Overlay $overlay)
    {
        //
    }
}
