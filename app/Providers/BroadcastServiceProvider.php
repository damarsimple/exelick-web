<?php

namespace App\Providers;

use App\Broadcasting\Channels\LighthouseSubscriptionChannel;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes(['middleware' => 'auth:sanctum']);
        Broadcast::channel('lighthouse-{id}-{time}', LighthouseSubscriptionChannel::class);

        require base_path('routes/channels.php');
    }
}
