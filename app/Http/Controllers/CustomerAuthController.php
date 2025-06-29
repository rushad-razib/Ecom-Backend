<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerEmailVerify;
use App\Models\Order;
use App\Notifications\customer_email_verify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class CustomerAuthController extends Controller
{
    function customer_login(){
        return view('frontend.login');
    }
    function customer_register(){
        return view('frontend.register');
    }
    function customer_store(Request $request){
        $request->validate([
            'fname'=>'required',
            'lname'=>'required',
            'email'=>'required|unique:customers',
            'password'=>['required','confirmed',
                Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()],
            'password_confirmation'=>'required',
            'captcha' => 'required|captcha',
        ], [
            'captcha'=>'Invalid Captcha',
        ]);

        $customer_info =  Customer::create([
            'fname'=>$request->fname,
            'lname'=>$request->lname,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
            'created_at'=>Carbon::now(),
        ]);
        $email_verify = CustomerEmailVerify::create([
            'customer_id'=>$customer_info->id,
            'token'=>uniqid(),
            'created_at'=>Carbon::now(),
        ]);
        Notification::send($customer_info, new customer_email_verify($email_verify));
        return redirect()->route('customer.login')->with('verify', "Email Verification link sent to $request->email");
    }
    function customer_logged(Request $request){
        $request->validate([
            'email'=>'required',
            'password'=>'required',
        ]);
        if(Customer::where('email', $request->email)->exists()){
            if(Auth::guard('customer')->attempt(['email'=>$request->email, 'password'=>$request->password])){
                if(Auth::guard('customer')->user()->email_verified_at != null){
                    return redirect()->route('index')->with('logged_in', 'You are logged in');
                }
                else{
                    return redirect()->route('customer.login')->with('verify_email', 'Please verify email first to login');
                }
            }
            else{
                return back()->with('pass_err','Incorrent credentials');
            }
        }
        else{
            return back()->with('email_err', 'Email doesnot exist');
        }
    }

    function customer_email_verify($token){
        if($customer_token_info = CustomerEmailVerify::where('token', $token)->exists()){
            $customer_token_info = CustomerEmailVerify::where('token', $token)->first();
            Customer::find($customer_token_info->customer_id)->update([
                'email_verified_at' => Carbon::now(),
            ]);
            $customer_token_info = CustomerEmailVerify::where('customer_id', $customer_token_info->customer_id)->delete();
            return redirect()->route('customer.login')->with('verify', 'Email Verification complete login now');
        }
        else{
            abort(404);
        }
        
    }

    function email_verify_resend(){
        return view('frontend.customer.resend_verification');
    }

    function customer_resend_verification(Request $request){
        if(Customer::where('email', $request->email)->exists()){
            $customer_info = Customer::where('email', $request->email)->first();
            $email_verify = CustomerEmailVerify::create([
                'customer_id'=>$customer_info->id,
                'token'=>uniqid(),
                'created_at'=>Carbon::now(),
            ]);
            Notification::send($customer_info, new customer_email_verify($email_verify));
            return redirect()->route('customer.login')->with('verify', "Email Verification link sent to $request->email. Please verify to continue");
        }
        else{
            return back()->with('invalid', 'Email does not exists. Please provide the email you registered with');
        }
    }
    public function refreshCaptcha(){
        return response()->json(['captcha'=> captcha_img()]);
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
        ->stateless()
        ->redirectUrl(env('GOOGLE_REDIRECT_CUSTOMER'))
        ->redirect();
    }
          
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleGoogleCallback()
    {
        try {
        
            $user = Socialite::driver('google')
            ->stateless()
            ->redirectUrl(env('GOOGLE_REDIRECT_CUSTOMER')) // Add this here too
            ->user();
            $finduser = Customer::where('google_id', $user->id)->first();
         
            if($finduser){
                Auth::guard('customer')->login($finduser);
                return redirect()->intended(route('index'));
         
            }
            else{
                $newUser = Customer::updateOrCreate(['email' => $user->email],[
                        'name' => $user->name,
                        'google_id'=> $user->id,
                        'password' => encrypt('123456dummy'),
                        'email_verified_at' => Carbon::now()
                    ]);
         
                Auth::guard('customer')->login($newUser);
        
                return redirect()->intended(route('index'));
            }
        
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
    
}
