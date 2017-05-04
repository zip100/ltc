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
Route::get('/buy/ltc', 'HomeController@getBuyLtc');
Route::post('/buy/btc', 'HomeController@postBuyBtc');
Route::post('/buy/ltc', 'HomeController@postBuyLtc');
Route::get('/orders', 'HomeController@getOrders');
Route::get('/orders/list', 'HomeController@getOrderData');
Route::get('/order/info/{order}', 'HomeController@getOrderInfo');
Route::get('/notice', 'HomeController@getNotice');
Route::get('/notice-list', 'HomeController@getNoticeList');
Route::post('/add-notice', 'HomeController@postAddNotice');
Route::get('/notice/delete/{id}', function($id){
    \App\Model\Notice::findOrFail($id)->delete();
    return redirect()->back();
});

