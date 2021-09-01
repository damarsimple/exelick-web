<?php

namespace App\Libraries;

use Exception;
use Midtrans\Config;
use Midtrans\Snap;
// Set your Merchant Server Key
Config::$serverKey = 'SB-Mid-server-NpgiDy4WiucZgpHg7VYeH5bj';
// Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
Config::$isProduction = false;
// Set sanitization on (default)
Config::$isSanitized = true;
// Set 3DS transaction for credit card to true
Config::$is3ds = true;

class Midtrans
{
    public static function makeTransaction($uuid, int $amount)
    {

        $params = array(
            'transaction_details' => array(
                'order_id' => $uuid,
                'gross_amount' => $amount,
            )
        );

        // Get Snap Payment Page URL
        $paymentData = (array) Snap::createTransaction($params);

        return array_merge($paymentData, ['uuid' => $uuid]);
    }
}
