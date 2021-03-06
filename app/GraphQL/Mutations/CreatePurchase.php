<?php

namespace App\GraphQL\Mutations;

use App\Libraries\Midtrans;
use App\Libraries\OyIndonesia;
use App\Libraries\Tripay;
use App\Models\Purchase;
use App\Models\Transaction;
use Illuminate\Support\Str;

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
                'price_per_item' => $product->price,
                'description' => $product->description,
                'quantity' => $product->pivot->qty,
                'item' => $product->name
            ]);
            $subTotal += $product->price * $product->pivot->qty;
        }

        $transactionRef = Str::uuid();

        // $attr = [
        //     'partner_tx_id' => $transactionRef,
        //     'description' => 'Donasi untuk ' . $purchase->receiver->name,
        //     'sender_name' => $args['anonymous_name'],
        //     'full_name' => $args['anonymous_name'],
        //     'amount' => $purchase->subtotal,
        //     'email' => 'yono@oyindonesia.com',
        //     'phone_number' => '085712163208',
        //     'is_open' => true,
        //     'step' => 'input-amount',
        //     'include_admin_fee' => true,
        //     'list_disabled_payment_methods' => '',
        //     'list_enabled_banks' => '002, 008, 009, 013, 022',
        //     'is_va_lifetime' => false,
        //     'due_date' => now()->addDay(1)->format('yyyy-MM-dd HH:mm:ss'),
        //     // 'partner_user_id' => 'OYON-CHECKOUT000003',
        //     'invoice_items' => $items
        // ];



        $midTransaction = Midtrans::makeTransaction($transactionRef, $subTotal);

        $transaction = new Transaction();

        $transaction->payment_method = 'Midtrans';

        $transaction->uuid = $transactionRef;

        $transaction->status = 'PENDING';

        $transaction->amount = $purchase->subtotal;

        $transaction->user_id = $purchase->receiver_id;

        $transaction->request = $midTransaction;

        $purchase->transaction()->save($transaction);

        $purchase->tax = 0;

        $purchase->total = $subTotal + $purchase->tax;

        $purchase->save();

        return [
            'purchase' => $purchase,
            'transaction' => $transaction,
            'payment' => $midTransaction,
            'success' => true,
            'message' => 'Berhasil'
        ];
    }
}
