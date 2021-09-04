<?php

use App\Models\Picture;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

Route::get('/files/{context}/{id}', function ($context, $id) {

    switch ($context) {
        case 'picture':
            $model = Picture::findOrFail($id);
            $response = Http::get($model->real_path);
            return response($response->body(), 200, ['content-type' => 'image/png']);
    }
});
