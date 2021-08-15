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

    $transaction = Transaction::where('uuid', $request->merchant_ref)->firstOrFail();

    $transaction->status = $request->status;

    $transaction->callback = $request->toArray();

    $transaction->save();
});
