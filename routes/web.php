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

Route::get('/', 'ShopController@index');
Route::get('/cart', 'ShopController@cart');
Route::get('/login', function () {
    return view('shop.login');
});
Route::get('/logout', 'ShopController@logout');
Route::post('/products', 'ShopController@auth');
Route::get('/products', 'ShopController@products')->middleware('admin');
Route::post('/', 'ShopController@checkout');
