<?php

namespace App\Http\Controllers;

use App\Mail\OrderCreated;
use App\Order;
use App\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\request()->session()->has('id')) {
            if (\request()->has('id')) {
                \request()->session()->push('id', \request()->get('id'));
            }
        } else {
            \request()->session()->put('id', []);
        }
        $query = Product::query();
        $query->whereNotIn($query->getModel()->getKeyName(), \request()->session()->get('id'));
        return view('shop.index', ['products' => $query->get()]);
    }

    public function cart()
    {
        if (\request()->has('id')) {
            $products = \request()->session()->pull('id', []);
            if (($key = array_search(\request()->get('id'), $products)) !== false) {
                array_splice($products, $key, 1);
            }
            \request()->session()->put('id', $products);
        }
        $query = Product::query();
        $query->whereIn($query->getModel()->getKeyName(), \request()->session()->get('id'));
        return view('shop.cart', ['products' => $query->get()]);
    }

    public function auth()
    {
        $attributes = \request()->validate([
           'username' => 'required',
           'password' => 'required'
        ]);
        if ( $attributes['username'] === env('ADMIN_USERNAME') && $attributes['password'] === env('ADMIN_PASSWORD')) {
            \request()->session()->push('admin', []);
            return view('shop.products', ['products' => Product::all()]);
        } else {
            return redirect('login');
        }
    }

    public function logout()
    {
        \request()->session()->pull('admin', []);
        return view('shop.login');
    }

    public function products()
    {
        return view('shop.products', ['products' => Product::all()]);
    }

    public function checkout()
    {
        $attributes = \request()->validate([
            'name' => 'required',
            'email' => 'required',
            'comment' => 'required'
        ]);

        $order = Order::create($attributes);


        \Mail::to('example@laravel.com')->send(
            new OrderCreated($order)
        );
    }
}
