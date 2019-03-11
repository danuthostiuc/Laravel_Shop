<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function orders()
    {
        $orders = DB::table('orders')
            ->select('orders.*', DB::raw('SUM(products.price) as total'))
            ->join('order_product', 'orders.id', '=', 'order_product.order_id')
            ->join('products', 'products.id', '=', 'order_product.product_id')
            ->groupBy('orders.id')
            ->get();

        if(\request()->ajax()) {
            return $orders;
        }

        return view('shop.orders', compact('orders'));
    }

    public function order()
    {
        $order = Order::with('products')->findOrFail(\request('id'));

        if(\request()->ajax()) {
            return $order;
        }

        return view('shop.order', compact('order'));
    }
}
