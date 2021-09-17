<?php

namespace App\GraphQL\Mutations;

use App\Events\PurchasePaidTest;

class TestDonation
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        if ($args['stream_key'] != auth()->user()->stream_key) {
            return ['status' => false, 'message' => 'you dont have authorization'];
        }

        PurchasePaidTest::dispatch($args['stream_key']);

        return ['status' => true, 'message' => 'success'];
    }
}
