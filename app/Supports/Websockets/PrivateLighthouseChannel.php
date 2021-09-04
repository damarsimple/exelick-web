<?php

namespace App\Supports\Websockets;

use Ratchet\ConnectionInterface;
use Nuwave\Lighthouse\Subscriptions\Contracts\StoresSubscriptions;
use BeyondCode\LaravelWebSockets\WebSockets\Channels\PrivateChannel;
use Illuminate\Support\Str;

class PrivateLighthouseChannel extends PrivateChannel
{
    public function unsubscribe(ConnectionInterface $connection): void
    {
        parent::unsubscribe($connection);

        if (Str::startsWith($this->channelName, 'private-lighthouse-') && !$this->hasConnections()) {
            static::lighthouseSubscriptionsStorage()->deleteSubscriber($this->channelName);
        }
    }

    private static function lighthouseSubscriptionsStorage(): StoresSubscriptions
    {
        return app(StoresSubscriptions::class);
    }
}
