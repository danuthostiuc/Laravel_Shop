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
Route::post('/', 'ShopController@checkout');
Route::post('/products', 'ShopController@add');
Route::post('/products/{id}', 'ShopController@edit');

Route::middleware(['admin'])->group(function () {

    Route::get('/products', 'ShopController@products');

    Route::get('/product', function () {
        return view('shop.product-add');
    });

    Route::get('/product/{id}', function () {
        return view('shop.product-edit');
    });

    Route::get('/products/{id}', 'ShopController@delete');

    Route::get('/orders', 'ShopController@orders');

    Route::get('/order/{id}', 'ShopController@order');
});
