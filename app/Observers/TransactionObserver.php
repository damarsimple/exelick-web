<?php

namespace App\Observers;

use App\Events\PurchasePaid;
use App\Events\TransactionPaidEvent;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function created(Transaction $transaction)
    {
        //
    }

    /**
     * Handle the Transaction "updated" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function updated(Transaction $transaction)
    {
        if ($transaction->status == "settlement" || $transaction->status == "capture") {
            Cache::increment('user-' . $transaction->user_id . 'transactionTotalMonth', $transaction->amount);
            Cache::increment('user-' . $transaction->user_id . 'transactionTotal', $transaction->amount);
            PurchasePaid::dispatch($transaction->purchase);
            $user = $transaction->user;

            $user->balance += $transaction->amount;

            $purchase = $transaction->purchase;


            Cache::increment('user-' . $transaction->user_id . 'purchaseTotal');
            Cache::increment('user-' . $transaction->user_id . 'purchaseTotalMonth');

            $products = $purchase->products;

            $user = $purchase->receiver;

            $variables = [];
            foreach ($user->variables as $var) {
                $variables[$var['name']] = $var['value'];
            }
            $subathonTime = $user->subathon_time_end;

            foreach ($products as $product) {
                $subathonTime = $subathonTime->addMinutes($product->subathon_time);

                foreach ($product->commands as $command) {
                    $words = explode(" ", $command);
                    $newWords = [];
                    foreach ($words as $word) {
                        try {
                            if ($word[0] == "$") {
                                $word = str_replace("$", "", $word);
                                $word = $variables[$word];
                            }
                            $newWords[] = $word;
                        } catch (\Throwable $th) {
                            continue;
                        }
                    }
                    Redis::publish('mc_' . $user->stream_key, implode(" ", $newWords));
                }
            }

            $user->subathon_time_end = $subathonTime;

            $user->save();

            broadcast(new TransactionPaidEvent($transaction));
        }
    }

    /**
     * Handle the Transaction "deleted" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function deleted(Transaction $transaction)
    {
        //
    }

    /**
     * Handle the Transaction "restored" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function restored(Transaction $transaction)
    {
        //
    }

    /**
     * Handle the Transaction "force deleted" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function forceDeleted(Transaction $transaction)
    {
        //
    }
}
