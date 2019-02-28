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

Route::post('/orders', 'ShopController@login');
Route::get('/logout', 'ShopController@logout');
Route::post('checkout/', 'ShopController@checkout');

Route::middleware(['admin'])->group(function () {

    Route::get('/products', 'ProductController@products');

    Route::get('/product', function () {
        return view('shop.product');
    });

    Route::get('/products/{id}', 'ProductController@delete');

    Route::get('/orders', 'OrderController@orders');

    Route::get('/order/{id}', 'OrderController@order');

    Route::post('/products', 'ProductController@add');

    Route::post('/products/{id}', 'ProductController@add');

    Route::get('/product/{id}', 'ProductController@edit');

});
