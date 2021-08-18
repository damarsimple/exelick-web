<?php

namespace App\Observers;

use App\Models\Picture;

class PictureObserver
{
    /**
     * Handle the Picture "created" event.
     *
     * @param  \App\Models\Picture  $picture
     * @return void
     */
    public function created(Picture $picture)
    {
        //
    }

    /**
     * Handle the Picture "updated" event.
     *
     * @param  \App\Models\Picture  $picture
     * @return void
     */
    public function updated(Picture $picture)
    {
        //
    }

    /**
     * Handle the Picture "deleted" event.
     *
     * @param  \App\Models\Picture  $picture
     * @return void
     */
    public function deleted(Picture $picture)
    {
        //
    }

    /**
     * Handle the Picture "restored" event.
     *
     * @param  \App\Models\Picture  $picture
     * @return void
     */
    public function restored(Picture $picture)
    {
        //
    }

    /**
     * Handle the Picture "force deleted" event.
     *
     * @param  \App\Models\Picture  $picture
     * @return void
     */
    public function forceDeleted(Picture $picture)
    {
        //
    }
}
