<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    function coupon_view(Request $request){
        $coupons = Coupon::all();
        return view('backend.coupon.coupon', [
            'coupons'=>$coupons,
        ]);
    }
    function coupon_store(Request $request){
        $request->validate([
            'coupon'=>'required',
            'type'=>'required',
            'amount'=>'required',
            'validity'=>'required',
            'limit'=>'required',
        ]);
        Coupon::insert([
            'coupon'=>$request->coupon,
            'type'=>$request->type,
            'amount'=>$request->amount,
            'validity'=>$request->validity,
            'limit'=>$request->limit,
            'status'=>0,
            'created_at'=>Carbon::now(),
        ]);
        return back()->with('success', 'Coupon Added');
    }
    function coupon_del($id){
        Coupon::find($id)->delete();
        return back()->with('success', 'Coupon Removed');
    }
    function coupon_status($id){
        $status = Coupon::find($id)->status;
        if($status == 0){
            Coupon::find($id)->update([
                'status'=> 1,
            ]);
        }
        else{
            Coupon::find($id)->update([
                'status'=> 0,
            ]);
        }
        return back();
    }
}
