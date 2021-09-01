<?php

namespace App\GraphQL\Queries;

use App\Libraries\Tripay;

class GetTax
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        // $tripay = new Tripay();
        // $tripay->setChannel('QRIS');
        // return ['tax' => $tripay->calculateTax($args['price'])];

        return ['tax' => 0];
    }
}
