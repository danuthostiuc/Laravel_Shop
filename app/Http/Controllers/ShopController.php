<?php

namespace App\Http\Controllers;

use App\Mail\OrderCreated;
use App\Order;
use App\Product;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\request()->has('id')) {
            if (!\request()->session()->has('cart')) {
                \request()->session()->put('cart', []);
            }
            \request()->session()->push('cart', \request()->get('id'));
        }

        $model = new Product;
        $products = $model->newQuery()
            ->whereNotIn($model->getKeyName(), \request()->session()->get('cart', []))
            ->get();

        return view('shop.index', ['products' => $products]);
    }

    public function cart()
    {
        if (\request()->has('id')) {
            $products = \request()->session()->pull('cart', []);
            if (($key = array_search(\request()->get('id'), $products)) !== false) {
                array_splice($products, $key, 1);
            }
            \request()->session()->put('cart', $products);
        }

        $model = new Product;
        $products = $model->newQuery()
            ->whereIn($model->getKeyName(), \request()->session()->get('cart', []))
            ->get();

        return view('shop.cart', ['products' => $products]);
    }

    public function login()
    {
        $attributes = \request()->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        if ($attributes['username'] === env('ADMIN_USERNAME') && $attributes['password'] === env('ADMIN_PASSWORD')) {
            \request()->session()->push('admin', true);
            return redirect('/products');
        } else {
            return redirect('/login');
        }
    }

    public function logout()
    {
        \request()->session()->forget('admin');

        return view('shop.login');
    }

    public function checkout()
    {
        $attributes = \request()->validate([
            'name' => 'required',
            'email' => 'required',
            'comment' => 'sometimes'
        ]);

        $order = new Order;
        $order->name = $attributes['name'];
        $order->email = $attributes['email'];
        $order->comment = $attributes['comment'];
        $order->save();
        $product_ids = \request()->session()->get('cart');

        /**
         * @var Order $order
         */
        if ($product_ids) {
            $order->products()->attach($product_ids);
        }

        $model = new Product;
        $products = $model->newQuery()
            ->whereIn($model->getKeyName(), $product_ids)
            ->get();

        \Mail::to('example@laravel.com')->send(
            new OrderCreated($order, $products)
        );

        \request()->session()->forget('cart');

        return redirect('/');
    }
}
