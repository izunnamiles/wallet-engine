<?php

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

Route::post('/register', 'Auth\AuthController@register');
Route::get('login', 'Auth\AuthController@login')->name('login');
Route::post('/login', 'Auth\AuthController@login');
Route::middleware('auth:api')->get('/logout', 'Auth\AuthController@logout');

Route::group(['middleware'=> 'auth:api','prefix' => 'user'],function(){
    Route::get('/{user}/wallet','WalletController@index');
    Route::post('/{user}/credit-wallet','WalletController@credit');
    Route::post('/{user}/debit-wallet','WalletController@debit');
    Route::patch('/{user}/activate','WalletController@activate');
    Route::patch('/{user}/deactivate','WalletController@deactivate');
    
});