<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function add()
    {
        $attributes = \request()->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required',
            'image' => 'required|image'
        ]);

        $file = Input::file('image');
        $destinationPath = public_path() . '/storage/';
        $filename = uniqid() . $file->getClientOriginalName();
        $file->move($destinationPath, $filename);

        $model = new Product;
        $model->newQuery()->create([
            'title' => $attributes['title'],
            'description' => $attributes['description'],
            'price' => $attributes['price'],
            'image' => $filename,
        ]);

        return redirect('/products');
    }

    public function update()
    {
        $attributes = \request()->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required',
            'image' => 'sometimes|required|image'
        ]);

        if (\request()->exists('image')) {

            $model = new Product;
            $result = $model->newQuery()
                ->select('image')
                ->where('id', \request('id'))
                ->get();

            $image_name = $result->pluck('image');
            $image_path = public_path() . '/storage/' . $image_name->first();

            if (\File::exists($image_path)) {
                \File::delete($image_path);
            }

            $file = Input::file('image');
            $destinationPath = public_path() . '/storage/';
            $filename = uniqid() . $file->getClientOriginalName();
            $file->move($destinationPath, $filename);

            Product::query()->where('id', \request('id'))
                ->update([
                    'title' => $attributes['title'],
                    'description' => $attributes['description'],
                    'price' => $attributes['price'],
                    'image' => $filename
                ]);
        } else {
            Product::query()->where('id', \request('id'))
                ->update([
                    'title' => $attributes['title'],
                    'description' => $attributes['description'],
                    'price' => $attributes['price'],
                ]);
        }

        return redirect('/products');
    }

    public function edit()
    {
        $model = new Product;
        $product = $model->newQuery()
            ->where('id', '=', \request('id'))
            ->get();

        return view('shop.product-edit', ['product' => $product]);
    }

    public function delete()
    {
        $model = new Product;
        $result = $model->newQuery()
            ->select('image')
            ->where('id', \request('id'))
            ->get();

        $image_name = $result->pluck('image');
        $image_path = public_path() . '/storage/' . $image_name->first();

        if (\File::exists($image_path)) {
            \File::delete($image_path);
        }

        $model->newQuery()
            ->where('id', '=', \request('id'))
            ->delete();

        return redirect('/products');
    }

    public function products()
    {
        return view('shop.products', ['products' => Product::all()]);
    }

    public function orders()
    {
        $orders = collect(DB::table('orders')
            ->select('orders.*', DB::raw('SUM(products.price) as total'))
            ->join('order_product', 'orders.id', '=', 'order_product.order_id')
            ->join('products', 'products.id', '=', 'order_product.product_id')
            ->groupBy('orders.id')
            ->get());

        return view('shop.orders', ['orders' => $orders]);
    }

    public function order()
    {
        $order = DB::table('orders')
            ->select('name', 'email', 'comment', 'image', 'title', 'description', 'price')
            ->join('order_product', 'orders.id', '=', 'order_product.order_id')
            ->join('products', 'products.id', '=', 'order_product.product_id')
            ->where('orders.id', \request('id'))
            ->get();

        return view('shop.order', ['order' => $order]);
    }
}
