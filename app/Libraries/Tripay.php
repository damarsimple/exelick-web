<?php

namespace App\Libraries;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Client\PendingRequest;

class Tripay
{

    private string $privateKey;
    private string $merchantCode;
    private string $apiKey;

    private string $signature;
    private float|int $amount;

    private string $channel;
    private string $merchantRef;

    private array $taxMap = [
        'MYBVA' => [
            'BASE' => 4250,
        ],
        'PERMATAVA' => [
            'BASE' => 4250,
        ],
        'BNIVA' => [
            'BASE' => 4250,
        ],
        'BRIVA' => [
            'BASE' => 4250,
        ],
        'MANDIRIVA' => [
            'BASE' => 4250,
        ],
        'BCAVA' => [
            'BASE' => 4250,
        ],
        'SMSVA' => [
            'BASE' => 4250,
        ],
        'MUAMALATVA' => [
            'BASE' => 4250,
        ],
        'CIMBVA' => [
            'BASE' => 4250,
        ],
        'BRIVAOP' => [
            'BASE' => 4250,
        ],
        'CIMBVAOP' => [
            'BASE' => 4250,
        ],
        'BCAVAOP' => [
            'BASE' => 4250,
        ],
        'BNIVAOP' => [
            'BASE' => 4250,
        ],
        'ALFAMART' => [
            'BASE' => 1500,
        ],
        'ALFAMIDI' => [
            'BASE' => 1500,
        ],
        'QRIS' => [
            'BASE' => 4250,
            'ADDED' => 0.007
        ],
        'QRISC' => [
            'BASE' => 4250,
            'ADDED' => 0.007
        ],
        'QRISOP' => [
            'BASE' => 4250,
            'ADDED' => 0.0007
        ],
        'QRISCOP' => [
            'BASE' => 4250,
            'ADDED' => 0.007
        ],
    ];

    private string $endPoint = 'https://tripay.co.id/api';

    private PendingRequest $httpClient;

    public function setAmount(float|int $amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function __construct()
    {
        $this->privateKey = env('TRIPAY_PRIVATE_KEY', '');
        $this->merchantCode = env('TRIPAY_MERCHANT_CODE', '');
        $this->apiKey = env('TRIPAY_API_KEY', '');

        $this->httpClient =
            Http::acceptJson()->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ]);

        $this->endPoint = env('APP_DEBUG') ? 'https://tripay.co.id/api-sandbox' : 'https://tripay.co.id/api';
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
        $this->signature = hash_hmac('sha256', $this->merchantCode .  $this->merchantRef . $this->amount, $this->privateKey);
        return $this;
    }

    /** 
     * customer_name, customer_email, customer_phone, 
     * order_items [ name, price , quantity ]
     */
    public function makeTransaction(array $data)
    {
        if (!$this->amount) {
            throw new Exception('Amount not set');
        }

        $this->generateMerchantRef();
        $this->generateSignature();

        return (object) $this->httpClient->post($this->endPoint . '/transaction/create', array_merge($data, [
            'method' => $this->channel,
            'amount' => $this->amount,
            'merchant_ref' => $this->merchantRef,
            'merchant_ref' => $this->merchantRef,
            'signature' => $this->signature,

        ]))->json()['data'];
    }

    public function getChannels()
    {
        return $this->httpClient->get($this->endPoint . '/merchant/payment-channel')->json();
    }

    public function calculateTax(int|float $amount)
    {
        $map =  $this->getTaxMap();

        $baseAmount = $amount;

        $baseAmount += $map['BASE'];

        if (array_key_exists('ADDED', $map)) {
            $baseAmount *= $map['ADDED'];
        }

        return $baseAmount;
    }

    public function getTaxMap()
    {
        if (!$this->channel) {
            throw new Exception('Channel not set !');
        }
        if (!array_key_exists($this->channel, $this->taxMap)) {
            throw new Exception('Channel not found !');
        }

        return $this->taxMap[$this->channel];
    }

    public function getTransaction(string $uuid)
    {
        return $this->httpClient->get($this->endPoint . '/transaction/' .  $uuid . '/detail')->json();
    }
}
