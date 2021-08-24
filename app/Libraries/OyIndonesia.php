<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class OyIndonesia
{

    public PendingRequest $client;
    public string $endPoint;

    public function __construct()
    {
        $this->client = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-oy-username' => 'damaralbaribin',
            'x-api-key' => '6975a446-2f3d-4062-8654-596c600a0a4c'
        ]);

        $this->endPoint = true ? 'https://api-stg.oyindonesia.com' : 'https://partner.oyindonesia.com';
    }

    public function makeInvoice(array $attr)
    {
        // $attr = [
        //     'partner_tx_id' => '',
        //     'description' => 'testdesc',
        //     'notes' => 'testdesc',
        //     'sender_name' => 'Mohamad Suryono',
        //     'full_name' => 'Mohamad Suryono',
        //     'amount' => 10000,
        //     'email' => 'yono@oyindonesia.com',
        //     'phone_number' => '085712163208',
        //     'is_open' => true,
        //     'step' => 'input-amount',
        //     'include_admin_fee' => false,
        //     'list_disabled_payment_methods' => '',
        //     'list_enabled_banks' => '002, 008, 009, 013, 022',
        //     'is_va_lifetime' => false,
        //     'due_date' => '',
        //     'partner_user_id' => 'OYON-CHECKOUT000003',
        //     'invoice_items' => [
        //         [
        //             'item' => 'test',
        //             'description' => 'test',
        //             'quantity' => 1,
        //             'price_per_item' => 10000
        //         ]
        //     ],
        // ];

        return $this->client->post($this->endPoint . '/api/payment-checkout/create-invoice', $attr)->json();
    }
}
