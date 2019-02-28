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
            'price' => 'required|numeric|min:1|max:1000000',
            'image' => ((!\request('id')) ? 'required|mimes:png,gif,jpeg,jpg' : 'sometimes|required|mimes:png,gif,jpeg,jpg')
        ]);

        if (!\request('id')) {

            $file = Input::file('image');
            $destinationPath = public_path() . '/storage/';
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($destinationPath, $filename);

            $product = new Product;
            $product->title = $attributes['title'];
            $product->description = $attributes['description'];
            $product->price = $attributes['price'];
            $product->image = $filename;
            $product->save();

            return redirect('/products');

        } else {

            $model = new Product;
            $product = $model->newQuery()
                ->findOrFail(\request('id'));

            if (\request()->exists('image')) {


                $image_name = $product->image;
                $image_path = public_path() . '/storage/' . $image_name;

                if (\File::exists($image_path)) {
                    \File::delete($image_path);
                }

                $file = Input::file('image');
                $destinationPath = public_path() . '/storage/';
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($destinationPath, $filename);

                $product->title = $attributes['title'];
                $product->description = $attributes['description'];
                $product->price = $attributes['price'];
                $product->image = $filename;
                $product->save();

            } else {

                $product->title = $attributes['title'];
                $product->description = $attributes['description'];
                $product->price = $attributes['price'];
                $product->save();

            }
            return redirect('/products');
        }
    }

    public function edit()
    {
        $model = new Product;
        $product = $model->newQuery()
            ->findOrFail(\request('id'));

        return view('shop.product', ['product' => $product]);
    }

    public function delete()
    {
        $model = new Product;
        $product = $model->newQuery()
            ->findOrFail(\request('id'));
        $image_path = public_path() . '/storage/' . $product->image;

        if (\File::exists($image_path)) {
            \File::delete($image_path);
        }

        $product->delete();

        return redirect('/products');
    }

    public function products()
    {
        return view('shop.products', ['products' => Product::all()]);
    }
}
