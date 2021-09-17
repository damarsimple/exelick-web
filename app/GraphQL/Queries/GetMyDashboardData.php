<?php

namespace App\GraphQL\Queries;

use Illuminate\Support\Facades\Cache;

class GetMyDashboardData
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {

        $seconds = 86400; // oneday

        /** @var App\Models\User $user */
        $user = auth()->user();


        $productTotal = Cache::remember('user-' . $user->id . 'productTotal', $seconds, function () use ($user) {
            return  $user->products()->count();
        });


        $analyticSentiment = Cache::remember('user-' . $user->id . 'analyticSentiment', $seconds, function () use ($user) {
            return  0.0;
        });

        $transactionTotal = Cache::remember('user-' . $user->id . 'transactionTotal', $seconds, function () use ($user) {
            return  $user->transactions()
                ->where('status', 'settlement')->orWhere('status', 'capture')
                ->sum('amount');
        });

        $transactionTotalMonth = Cache::remember('user-' . $user->id . 'transactionTotalMonth', $seconds, function () use ($user) {
            return  $user->transactions()
                ->where('status', 'settlement')->orWhere('status', 'capture')
                ->whereMonth('created_at', now())->sum('amount');
        });


        $purchaseTotal = Cache::remember('user-' . $user->id . 'purchaseTotal', $seconds, function () use ($user) {
            return  $user->purchases()->count();
        });


        $purchaseTotalMonth = Cache::remember('user-' . $user->id . 'purchaseTotalMonth', $seconds, function () use ($user) {
            return  $user
                ->purchases()
                ->whereMonth('created_at', now())->count();
        });

        return [
            'total_product' => $productTotal,
            'analytics_sentiment' => $analyticSentiment,
            'transaction_total' => $transactionTotal,
            'transaction_total_month' => $transactionTotalMonth,
            'purchase_total' => $purchaseTotal,
            'purchase_total_month' => $purchaseTotalMonth,
        ];
    }
}
