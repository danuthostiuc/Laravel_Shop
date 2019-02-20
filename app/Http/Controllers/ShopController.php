<?php

namespace App\Http\Controllers;

use App\Mail\OrderCreated;
use App\Order;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

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
        if ($attributes['username'] === env('ADMIN_USERNAME') && $attributes['password'] === env('ADMIN_PASSWORD')) {
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
        return redirect('order-created');
    }

    public function add()
    {
        $attributes = \request()->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required',
            'image' => 'required'
        ]);

        $file = Input::file('image');
        $destinationPath = public_path() . '/storage/';
        $filename = uniqid() . $file->getClientOriginalName();
        $file->move($destinationPath, $filename);

        Product::create([
            'title' => $attributes['title'],
            'description' => $attributes['description'],
            'price' => $attributes['price'],
            'image' => $filename,
        ]);
        return redirect('/products');
    }

    public function delete()
    {
        $product = Product::select('image')->where('id', \request('id'))->get();
        $product = $product->map(function ($p) {
            return $p->only(['image']);
        });
        $image_path = public_path() . '/storage/' . $product->first()['image'];
        if (\File::exists($image_path)) {
            \File::delete($image_path);
        }
        Product::where('id', '=', \request('id'))->delete();
        return redirect('/products');
    }

    public function edit()
    {
        $attributes = \request()->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required'
        ]);
        Product::where('id', '=', \request('id'))
            ->update([
                'title' => $attributes['title'],
                'description' => $attributes['description'],
                'price' => $attributes['price']
            ]);
        return redirect('/products');
    }
}
