<?php

namespace App\Http\Controllers;


use App\Product;
use Illuminate\Support\Facades\Input;


class ProductController extends Controller
{
    public function add()
    {
        $attributes = \request()->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required',
            'image' => 'required|mimes:png,gif,jpeg,jpg'
        ]);

        $file = Input::file('image');
        $destinationPath = public_path() . '/storage/';
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
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
            'image' => 'sometimes|required|mimes:png,gif,jpeg,jpg'
        ]);

        if (\request()->exists('image')) {

            $model = new Product;
            $image_name = $model->newQuery()
                ->where('id', \request('id'))
                ->pluck('image');
            $image_path = public_path() . '/storage/' . $image_name->first();

            if (\File::exists($image_path)) {
                \File::delete($image_path);
            }

            $file = Input::file('image');
            $destinationPath = public_path() . '/storage/';
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
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
        $image_name = $model->newQuery()
            ->where('id', \request('id'))
            ->pluck('image');
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
}
