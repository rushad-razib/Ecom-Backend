<?php

namespace App\Http\Controllers;

use App\Mail\OrderCancelMail;
use App\Models\CancelledOrder;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderCancellationReq;
use App\Models\OrderProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    function order_view(){
        $orders = Order::all();
        return view('backend.order.order', [
            'orders'=>$orders,
        ]);
    }
    function order_cancel(Request $request, $id){
        Order::find($id)->update([
            'status'=>$request->action,
        ]);
        return back()->with('success', 'Order Status updated');
    }
    function cancel_manage($id){
        $order = Order::find($id);
        return view('backend.order.order_cancel', [
            'order'=>$order,
        ]);
    }
    function cancel_order(Request $request, $id){
        $request->validate([
            'message'=>'required',
        ]);
        $order_id = Order::find($id)->order_id;
        $products = OrderProduct::where('order_id', $order_id)->get();
        CancelledOrder::insert([
            'order_id' => $order_id,
            'message' => $request->message,
            'created_at' => Carbon::now(),
        ]);
        Order::find($id)->update([
            'status'=>5,
        ]);
        foreach($products as $product){
            Inventory::where('product_id', $product->product_id)->where('color_id', $product->color_id)->where('size_id', $product->size_id)->increment('quantity', $product->quantity);
        }
        Mail::to($request->user())->send(new OrderCancelMail($order_id));
        return redirect()->route('order.view')->with('success', 'Order Cancelled, Client notified via mail');
    }
    function cancel_requests(){
        $cancellation_reqs = OrderCancellationReq::all();
        return view('backend.order.cancel_requests', [
            'cancellation_reqs'=>$cancellation_reqs,
        ]);
    }
    function cancel_reason($id){
        $cancel_reason = OrderCancellationReq::find($id);
        $order = Order::where('order_id', $cancel_reason->order_id)->first();
        $ordered_products = OrderProduct::where('order_id', $cancel_reason->order_id)->get();
        $customer_info = Customer::find($order->customer_id);
        return view('backend.order.cancel_reason', [
            'cancel_reason'=>$cancel_reason,
            'customer_info'=>$customer_info,
            'order'=>$order,
            'ordered_products'=>$ordered_products,
        ]);
    }
    function cancel_deny($id){
        $order_id = OrderCancellationReq::find($id)->order_id;
        Order::where('order_id', $order_id)->update([
            'status'=>1,
        ]);
        OrderCancellationReq::find($id)->delete();
        return redirect()->route('cancel.requests')->with('success', 'Order cancellation request rejected');
    }
}
