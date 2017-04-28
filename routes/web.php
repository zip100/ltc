<?php

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

Auth::routes();

Route::get('/home', 'HomeController@index');

//Route::controller('datatables', 'DatatablesController', [
//    'anyData'  => 'datatables.data',
//    'getIndex' => 'datatables',
//]);


Route::get('/datatables/data', 'HomeController@getData');
Route::get('/buy', 'HomeController@getBuy');
Route::post('/buy/btc', 'HomeController@postBuyBtc');

