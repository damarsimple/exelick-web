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

Route::prefix('midtrans')->group(function () {
    //     {
    //   "transaction_time": "2021-09-01 14:11:50",
    //   "transaction_status": "pending",
    //   "transaction_id": "7da8aa8c-ad36-4401-ba2d-380ddf2d07e4",
    //   "store": "indomaret",
    //   "status_message": "midtrans payment notification",
    //   "status_code": "201",
    //   "signature_key": "5402915ded3d5b05ca8fb9a4afe65188b3e1e4d029405aa8e3e314c98e18b8c59039230dce8f2d59f521fbc3f063705c591edca6c1d13238e268a75a97588f55",
    //   "payment_type": "cstore",
    //   "payment_code": "300321403214",
    //   "order_id": "83aadf40-cb8a-48ee-b2dd-ebaf7b4c42a0",
    //   "merchant_id": "G876405845",
    //   "gross_amount": "11328.00",
    //   "currency": "IDR"
    // }

    Route::get('notification', function () {
        return 'OK';
    });

    Route::post('notification', function (Request $request) {
        $transaction = Transaction::where('uuid', $request->order_id)->firstOrFail();
        $transaction->status = $request->transaction_status;
        $transaction->amount = $request->gross_amount;
        $transaction->callback = $request->toArray();
        $transaction->save();

        return 'OK';
    });
});


// Route::post('/payment', function (Request $request) {

//     if (!$request->status->code == "000") {
//         return 'OK';
//     }

//     $transaction = Transaction::where('uuid', $request->partner_trx_id)->firstOrFail();

//     $transaction->status = $request->status->code;

//     $transaction->amount = $request->amount;

//     $transaction->callback = $request->toArray();

//     $transaction->save();
// });
