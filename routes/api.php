<?php

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/payment', function (Request $request) {

    if (!$request->status->code == "000") {
        return 'OK';
    }

    $transaction = Transaction::where('uuid', $request->partner_trx_id)->firstOrFail();

    $transaction->status = $request->status->code;

    $transaction->amount = $request->amount;

    $transaction->callback = $request->toArray();

    $transaction->save();
});
