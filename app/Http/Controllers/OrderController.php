<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    function order_view(){
        $orders = Order::latest()->get();
        return view('backend.order.order', [
            'orders'=>$orders,
        ]);
    }

    function order_status(Request $request, $id){
        $status = $request->status;
        Order::find($id)->update([
            'status'=>$status,
        ]);
        return back()->with('success', 'Status updated');
    }

    function order_info($order_id){
        $products = OrderProduct::where('order_id', $order_id)->get();
        $customer_id = OrderProduct::where('order_id', $order_id)->first()->customer_id;
        $customer = Customer::find($customer_id);
        return view('backend.order.info', [
            'products'=> $products,
            'customer'=> $customer,
        ]);
    }

}
