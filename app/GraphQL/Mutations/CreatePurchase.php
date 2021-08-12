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

        foreach ($purchase->products as $product) {
            $subTotal += $product->price * $product->pivot->qty;
        }

        $tripay = new Tripay();
        $tripay->setChannel('QRIS');

        $purchase->extra = $tripay->getTaxMap();

        $purchase->subtotal = $subTotal;
        $purchase->tax =  $tripay->calculateTax($subTotal);

        $purchase->total = $subTotal + $purchase->tax;


        $purchase->save();

        $transaction = new Transaction();

        $transaction->payment_method = Transaction::QRIS;

        $transaction->amount = $purchase->subtotal;

        $transaction->user_id = $purchase->receiver_id;

        $purchase->transaction()->save($transaction);

        return $purchase;
    }
}
