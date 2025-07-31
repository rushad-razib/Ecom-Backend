<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\InvoiceMail;
use App\Models\Billing;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    function coupon_apply(Request $request){
        
        $request->validate([
            'coupon'=>'required',
        ]);
        if(Coupon::where('coupon', $request->coupon)->exists()){
            $coupon = Coupon::where('coupon', $request->coupon)->first();
            if($coupon->status == 1){
                if($coupon->limit && $coupon->limit > $request->subtotal){
                    return response()->json([
                        'coupon_err'=>"Total Purchase must be atleast: $coupon->limit BDT to apply this coupon"
                    ]);
                }
                if(Carbon::now()->format('Y-m-d') <= $coupon->validity){
                    return response()->json([
                        'success'=>'Coupon Valid',
                        'type'=>$coupon->type,
                        'amount'=>$coupon->amount,
                    ]);
                }
                else{
                    return response()->json([
                        'coupon_err'=>"Coupon expired"
                    ]);
                }
            }
            else{
                return response()->json([
                    'coupon_err'=>"Coupon is no longer valid"
                ]);
            }
            
        }
        else{
            return response()->json([
                'coupon_err'=>'Coupon Invalid'
            ]);    
        }    
    }

    function order(Request $request){
        
        $request->validate([
            'address'=>'required',
            'city'=>'required',
            'phone'=>'required',
        ]);
        
        
        $order_id = uniqid();
        $customer_id = $request->customer_id;
        $order = Order::create([
            'order_id'=>$order_id,
            'customer_id'=>$customer_id,
            'subtotal'=>$request->subtotal,
            'coupon_discount'=>$request->discount,
            'charge'=>$request->charge,
            'total'=>$request->subtotal-$request->discount+$request->charge,
            'payment_method'=>$request->payment_method,
            'created_at'=>Carbon::now(),
        ]);

        $carts = Cart::with('rel_to_product')->where('customer_id', $request->customer_id)->get();
        $carts->each(function($cart){
            $cart->inventory = $cart->inventory;
        });
        foreach($carts as $cart){
            OrderProduct::insert([
                'order_id'=>$order_id,
                'customer_id'=>$customer_id,
                'product_id'=>$cart->product_id,
                'color_id'=>$cart->color_id,
                'size_id'=>$cart->size_id,
                'quantity'=>$cart->quantity,
                'price'=>$cart->inventory->after_discount,
                'created_at'=>Carbon::now(),
            ]);
            // Inventory::where('product_id', $cart->product_id)->where('color_id', $cart->color_id)->where('size_id', $cart->size_id)->decrement('quantity', $cart->quantity);
        }
        // Cart::where('customer_id', $request->customer_id)->delete();
        $customer = Customer::find($customer_id);
        Billing::insert([
            'order_id'=>$order_id,
            'customer_name'=>$customer->name,
            'company_name'=>$request->company,
            'street_address'=>$request->address,
            'floor'=>$request->floor,
            'city'=>$request->city,
            'phone'=>$request->phone,
            'email'=>$customer->email,
            'created_at'=>Carbon::now(),
        ]);

        Mail::to($customer->email)->send(new InvoiceMail($order_id));
        return response()->json([
            'success'=>'Order placed successfully',
        ]);
    }

}
