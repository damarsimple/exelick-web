<?php

namespace App\Libraries;

use Illuminate\Support\Str;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class Tripay
{

    private $privateKey;
    private $merchantCode;
    private $apiKey;

    private string $signature;

    private string $channel;
    private string $merchantRef;

    private Http $httpClient;

    public function __construct()
    {
        $this->privateKey = env('TRIPAY_PRIVATE_KEY');
        $this->privateKey = env('TRIPAY_PRIVATE_KEY');
        $this->merchantCode = env('TRIPAY_MERCHANT_CODE');
        $this->apiKey = env('TRIPAY_API_KEY');

        $this->httpClient =
            Http::acceptJson()->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ]);
    }
    public function setChannel(string $channel)
    {
        $this->channel = $channel;
        return $this;
    }
    private function generateMerchantRef()
    {
        $this->merchantRef = Str::uuid();
        return $this;
    }
    private function generateSignature()
    {
        $this->signature = hash_hmac('sha256', $this->merchantCode . $this->channel . $this->merchantRef, $this->privateKey);
        return $this;
    }

    public function makeTransaction(string $channel, string $name)
    {
        $this->setChannel($channel);
        $this->generateMerchantRef();
        $this->generateSignature();

        return $this->httpClient->post('https://tripay.co.id/api/open-payment/create', [
            'name' => $this->channel,
            'merchant_ref' => $this->merchantRef,
            'customer_name' => $name,
            'signature' => $this->signature
        ])->json();
    }

    public function getTransaction(string $uuid)
    {
        return $this->httpClient->get('https://tripay.co.id/api/open-payment/' .  $uuid . '/detail')->json();
    }
}
