<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class userController extends Controller
{
    function profile_edit(){
        return view('backend.user.user_edit');
    }
    function profile_update(Request $request){
        User::find(Auth::id())->update([
            'name'=>$request->name,
            'email'=>$request->email,
        ]);

        return back()->with('user_updated', 'Profile Updated Successfully');
    }
    
    function user_pass(Request $request){
        $request->validate([
            'cur_pass'=>'required',
            'password'=>['required','confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()],
            'password_confirmation'=>'required',
        ], [
            'cur_pass.required'=>'This field is required',
            'password.required'=>'This field is required',
            'password_confirmation.required'=>'This field is required',
        ]);


        if (password_verify($request->cur_pass, User::find(Auth::id())->password)) {
            User::find(Auth::id())->update([
                'password'=>bcrypt($request->password),
            ]);

            return back()->with('user_updated', 'Password Updated Successfully');
        }
        else{
            return back()->with('cur_pass_err', 'Current password does not match');
        }
    }
    function user_photo(Request $request){
        if(User::find(Auth::id())->photo){
            unlink(public_path('uploads/user/'.User::find(Auth::id())->photo));
        }
        $photo = $request->photo;
        $extension = $photo->extension();
        $filename = uniqid().'.'.$extension;
        $manager = new ImageManager(new Driver());
        $image = $manager->read($photo);
        $image->scale(width: 300);
        $image->toPng()->save(public_path('uploads/user/').$filename);

        User::find(Auth::id())->update([
            'photo'=>$filename,
        ]);
        return back()->with('user_updated', 'Profile Picture Updated');
    }
    function user_view(){
        $users = User::all();
        return view('backend.user.user_view', [
            'users'=>$users,
        ]);
    }
    function user_del($id){
        User::find($id)->delete();
        return back()->with('success', 'User removed');
    }
    function user_store(Request $request){
        $request->validate([
            'name'=>'bail|required|unique:users,name',
            'email'=>'bail|required|unique:users,email',
            'password'=>['required','confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()],
        ]);
        if($request->photo != ''){
            $photo = $request->photo;
            $extension = $photo->extension();
            $filename = uniqid().'.'.$extension;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($photo);
            $image->scale(width: 300);
            $image->toPng()->save(public_path('uploads/user/').$filename);
            User::insert([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>bcrypt($request->password),
                'photo'=>$filename,
            ]);
        }
        else{
            User::insert([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>bcrypt($request->password),
            ]);
        }
        return back()->with('success', 'New user added');
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
        ->redirectUrl(env('GOOGLE_REDIRECT_ADMIN'))
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
            ->redirectUrl(env('GOOGLE_REDIRECT_ADMIN'))
            ->user();
            $finduser = User::where('google_id', $user->id)->first();
         
            if($finduser){
                Auth::login($finduser);
                return redirect()->intended(route('dashboard'));
         
            }
            else{
                $newUser = User::updateOrCreate(['email' => $user->email],[
                        'name' => $user->name,
                        'google_id'=> $user->id,
                        'password' => encrypt('123456dummy')
                    ]);
         
                Auth::login($newUser);
        
                return redirect()->intended(route('dashboard'));
            }
        
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
