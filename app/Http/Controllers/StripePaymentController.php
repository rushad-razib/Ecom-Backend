<?php
      
namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\Billing;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\StripeOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stripe;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class StripePaymentController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripe(): View
    {
        // $data = StripeOrder::where('order_id', $order_id)->first();
        return view('stripe');
    }
      
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripePost(Request $request, $order_id): RedirectResponse
    {
        $data = StripeOrder::where('order_id', $order_id)->first();
        Order::insert([
            'order_id'=>$order_id,
            'customer_id'=>$data->customer_id,
            'subtotal'=>$data->subtotal,
            'coupon_discount'=>$data->discount,
            'charge'=>$data->charge,
            'total'=>$data->total,
            'payment_method'=>$data->payment_method,
            'created_at'=>Carbon::now(),
        ]);

        $carts = Cart::with('rel_to_product')->where('customer_id', $data->customer_id)->get();
        $carts->each(function($cart){
            $cart->inventory = $cart->inventory;
        });
        foreach($carts as $cart){
            OrderProduct::insert([
                'order_id'=>$order_id,
                'customer_id'=>$data->customer_id,
                'product_id'=>$cart->product_id,
                'color_id'=>$cart->color_id,
                'size_id'=>$cart->size_id,
                'quantity'=>$cart->quantity,
                'price'=>$cart->inventory->after_discount,
                'created_at'=>Carbon::now(),
            ]);
            // Inventory::where('product_id', $cart->product_id)->where('color_id', $cart->color_id)->where('size_id', $cart->size_id)->decrement('quantity', $cart->quantity);
        }
        // Cart::where('customer_id', $data->customer_id)->delete();
        $customer = Customer::find($data->customer_id);
        Billing::insert([
            'order_id'=>$order_id,
            'customer_name'=>$customer->name,
            'company_name'=>$data->company_name,
            'street_address'=>$data->street_address,
            'floor'=>$data->floor,
            'city'=>$data->city,
            'phone'=>$data->phone,
            'email'=>$customer->email,
            'created_at'=>Carbon::now(),
        ]);

        Mail::to($customer->email)->send(new InvoiceMail($order_id));
            

        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
      
        Stripe\Charge::create ([
                "amount" => $data->total * 100,
                "currency" => "bdt",
                "source" => $request->stripeToken,
                "description" => "VueCommerce Stripe Payment Test" 
        ]);
                
        return redirect('http://localhost:5173/checkout/success');
    }
}