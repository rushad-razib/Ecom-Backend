<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CartController extends Controller
{
    function cart_store(Request $request){
        
        $request->validate([
            'color_id'=>'required',
            'size_id'=>'required',
            'quantity'=>'required',
        ],[
            'color_id.required'=>'Please select color',
            'size_id.required'=>'Please select size',
        ]);

        Cart::insert([
            'customer_id'=>$request->customer_id,
            'product_id'=>$request->product_id,
            'color_id'=>$request->color_id,
            'size_id'=>$request->size_id,
            'quantity'=>$request->quantity,
            'created_at'=>Carbon::now(),
        ]);


        return response()->json([
            'success'=>'Product added to cart',
        ]);
    }

    function cart($id){
        $carts = Cart::with('rel_to_product')->where('customer_id', $id)->get();
        $carts->each(function($cart){
            $cart->inventory = $cart->inventory;
        });
        return response()->json([
            'carts'=>$carts,
        ]);
    }
}
