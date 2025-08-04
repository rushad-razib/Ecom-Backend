<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\HttpFoundation\RequestStack;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerController extends Controller
{
    function register(Request $request){
        $request->validate([
            'name'=>'required',
            'email'=>'required',
            'password'=>'required',
        ]);
        
        Customer::insert([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
            'created_at'=>Carbon::now(),
        ]);

        return response()->json([
            'success'=>'Registration successful',
        ]);
    }
    function login(Request $request){

        $request->validate([
            'email'=>'required',
            'password'=>'required',
        ]);
        $customer = Customer::where('email', $request->email)->first();
 
        if (! $customer || ! Hash::check($request->password, $customer->password)) {
            return response()->json([
                'error'=>'Invalid credentials'
            ], 401);
        }
    
        $token = $customer->createToken("token")->plainTextToken;

        return response()->json([
            'token'=>$token
        ]);


    }
    public function logout(Request $request){
        
        return response()->json(['message' => 'Logged out successfully']);
    }

    function customer_update(Request $request, $id){

        $request->validate([
            'name'=>'required'
        ]);
        $customer = Customer::find($id);
        if(!$customer){
            return response()->json([
                'error'=>'User not found'
            ],404);
        }
        if(!$request->filled('password')){
            try {
                $customer->update([
                    'name'=>$request->name,
                    'address'=>$request->address,
                ]);
                return response()->json([
                    'success'=>'Information Updated',
                    'name'=>$request->name,
                    'address'=>$request->address,
                ]);
            } 
            catch (\Throwable $th) {
                return response()->json([
                    'error'=>'Something went wrong'
                ]);
            }
        }
        else{
            $request->validate([
                'curpass'=>'required',
                'password'=>'required|confirmed',
                'password_confirmation'=>'required',
            ], [
              'curpass.required'=>'Current password is required'  
            ]);
            try {
                if(!Hash::check($request->curpass, $customer->password)){
                    return response()->json([
                        'passerror'=>'Current password did not match'
                    ], 422);
                }
                $customer->update([
                    'name'=>$request->name,
                    'address'=>$request->address,
                    'password'=>bcrypt($request->password),
                ]);
                return response()->json([
                    'success'=>'Information Updated',
                    'name'=>$request->name,
                    'address'=>$request->address,
                ]);
            } 
            catch (\Throwable $th) {
                return response()->json([
                    'error'=>'Something went wrong'
                ]);
            }
        }
        
    }

    function myorders($id){
        $orders = Order::where('customer_id', $id)->get();
        return response()->json([
            'orders'=>$orders,
        ]);
    }

    // Download invoice PDF customers
    function order_invoice_download($order_id){
        $orders = Order::where('order_id', $order_id)->first();
        $pdf = PDF::loadView('invoice', [
            'order_id'=>$order_id,
        ]);
        return $pdf->download('invoice.pdf');
    }

}
