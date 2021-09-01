<?php

use Midtrans\Config;

// Set your Merchant Server Key
Config::$serverKey = '<your server key>';
// Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
Config::$isProduction = false;
// Set sanitization on (default)
Config::$isSanitized = true;
// Set 3DS transaction for credit card to true
Config::$is3ds = true;
