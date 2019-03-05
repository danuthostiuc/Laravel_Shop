<?php

namespace App\Http\Controllers;


use App\Product;
use Illuminate\Support\Facades\Input;


class ProductController extends Controller
{
    public function save()
    {
        $attributes = \request()->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required|numeric|min:1|max:1000000',
            'image' => (!\request('id') ? '' : 'sometimes|') . 'required|mimes:png,gif,jpeg,jpg',
        ]);

        $product = !\request('id') ? new Product : Product::query()->findOrFail(\request('id'));

        if (\request()->exists('image')) {
            $file = Input::file('image');
            $destinationPath = public_path() . '/storage/';
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($destinationPath, $filename);

            $product->image = $filename;
        }

        $product->title = $attributes['title'];
        $product->description = $attributes['description'];
        $product->price = $attributes['price'];

        $product->save();

        return redirect('/products');
    }

    public function form()
    {
        $product = !\request('id') ? new Product : Product::query()->findOrFail(\request('id'));

        return view('shop.product', compact('product'));
    }

    public function delete()
    {
        $product = Product::query()->findOrFail(\request('id'));

        $image_path = public_path() . '/storage/' . $product->image;

        if (\File::exists($image_path)) {
            \File::delete($image_path);
        }

        $product->delete();

        return redirect('/products');
    }

    public function products()
    {
        if (\request()->ajax()) {
            return Product::all();
        }
        return view('shop.products', ['products' => Product::all()]);
    }
}
