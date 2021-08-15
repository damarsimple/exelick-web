<?php

namespace App\GraphQL\Mutations;

use App\Libraries\Tripay;
use App\Models\Purchase;
use App\Models\Transaction;

class CreatePurchase
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $pData = $args;

        $purchase = new Purchase();

        $purchase->receiver_id = $pData['receiver_id'];
        $purchase->anonymous_name = $pData['anonymous_name'];
        $purchase->message = $pData['message'];

        $purchase->save();

        foreach ($pData['products'] as $product) {
            $purchase->products()->attach($product['id'], ['qty' => $product['qty']]);
        }

        $subTotal = 0;

        $items = [];

        // [
        //     [
        //         'price' => 10000,
        //         'quantity' => 1,
        //         'name' => 'test'
        //     ]
        // ]

        foreach ($purchase->products as $product) {
            array_push($items, [
                'price' => $product->price,
                'sku' => $product->id,
                'quantity' => $product->pivot->qty,
                'name' => $product->name
            ]);
            $subTotal += $product->price * $product->pivot->qty;
        }

        $tripay = new Tripay();

        $tripay->setChannel('QRISC');

        $purchase->extra = $tripay->getTaxMap();

        $purchase->subtotal = $subTotal;

        $purchase->tax =  $tripay->calculateTax($subTotal);

        $purchase->total = $subTotal + $purchase->tax;


        $purchase->save();

        $tripay->setAmount($purchase->subtotal);



        $data = [
            'customer_name' => $args['anonymous_name'],
            'customer_email' =>  $args['anonymous_email'],
            'customer_phone' => $args['anonymous_phone'],
            'order_items' => $items,
        ];

        $x = $tripay->makeTransaction($data);

        $transaction = new Transaction();

        $transaction->payment_method = $x->payment_method;

        $transaction->uuid = $x->merchant_ref;

        $transaction->status = $x->status;


        $transaction->amount = $x->amount_received;

        $transaction->user_id = $purchase->receiver_id;

        $transaction->request = $x;

        $purchase->transaction()->save($transaction);

        $purchase->tax = $x->fee_merchant + $x->fee_customer;

        $purchase->total = $subTotal + $purchase->tax;

        $purchase->save();

        //  (
        //     [reference] => DEV-T554018614Z3ZCH
        //     [merchant_ref] => 89e939e6-1f6d-40b4-b030-23cbedf6f47b
        //     [payment_selection_type] => static
        //     [payment_method] => QRISC
        //     [payment_name] => QRIS (Customizable)
        //     [customer_name] => test ppl
        //     [customer_email] => test@ppl.com
        //     [customer_phone] => 08987181017
        //     [callback_url] => 
        //     [return_url] => 
        //     [amount] => 10000
        //     [fee_merchant] => 820
        //     [fee_customer] => 0
        //     [total_fee] => 820
        //     [amount_received] => 9180
        //     [pay_code] => 
        //     [pay_url] => 
        //     [checkout_url] => https://tripay.co.id/checkout/DEV-T554018614Z3ZCH
        //     [status] => UNPAID
        //     [expired_time] => 1629115703
        //     [order_items] => Array
        //         (
        //             [0] => Array
        //                 (
        //                     [sku] => 
        //                     [name] => test
        //                     [price] => 10000
        //                     [quantity] => 1
        //                     [subtotal] => 10000
        //                 )

        //         )

        //     [instructions] => Array
        //         (
        //             [0] => Array
        //                 (
        //                     [title] => Pembayaran via QRIS
        //                     [steps] => Array
        //                         (
        //                             [0] => Masuk ke aplikasi dompet digital Anda yang telah mendukung QRIS
        //                             [1] => Pindai/Scan QR Code yang tersedia
        //                             [2] => Akan muncul detail transaksi. Pastikan data transaksi sudah sesuai
        //                             [3] => Selesaikan proses pembayaran Anda
        //                             [4] => Transaksi selesai. Simpan bukti pembayaran Anda
        //                         )

        //                 )

        //         )

        //     [qr_string] => SANDBOX MODE
        //     [qr_url] => https://tripay.co.id/qr/DEV-T554018614Z3ZCH
        // )

        return [
            'purchase' => $purchase,
            'transaction' => $transaction,
            'payment' => $x,
            'success' => true,
            'message' => 'Berhasil'
        ];
    }
}
