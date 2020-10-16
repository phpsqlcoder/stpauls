<?php

namespace App\Http\Controllers\EcommerceControllers;


use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use App\Helpers\Webfocus\Setting;
use Illuminate\Validation\Rule;

use App\Page;
use Session;
use App\EcommerceModel\Customer;
use App\User;

use App\EcommerceModel\Product;
use App\EcommerceModel\Cart;

use App\Deliverablecities;
use App\Provinces;
use App\Cities;

class CustomerFrontController extends Controller
{

    public function sign_up(Request $request) {

        $page = new Page();
        $page->name = 'Sign Up';
        
        $socialData = $request;

        return view('theme.stpaul.ecommerce.customer.sign-up',compact('page','socialData'));

    }

    public function ajax_cities($id)
    {

        $data = Cities::where('province',$id)->get();

        return response($data);
    }

    public function ajax_deliverable_cities($id)
    {
        $data = Deliverablecities::where('province',$id)->get();

        return response($data);
    }

    public function customer_sign_up(Request $request)
    {   
        Validator::make($request->all(),[
            'firstname' => 'required|max:150|regex:/^[\pL\s\-]+$/u',
            'lastname' => 'required|max:150|regex:/^[\pL\s\-]+$/u',
            'email' => 'required|email|max:191|unique:users,email',
            'password' => [
                'required',
                'max:150',
                'min:8',
                'regex:/[a-z]/', // must contain at least one lowercase letter
                'regex:/[A-Z]/', // must contain at least one uppercase letter
                'regex:/[0-9]/', // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
            'password_confirmation' => 'required|same:password',
        ])->validate();

        
        // Login Credetials
        $customer = User::create([
            'email' => $request->email,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email_verified_at' => now(),
            'password' => \Hash::make($request->password),
            'role_id' => 3, // customer
            'is_active' => 1,
            'user_id' => 0,
            'remember_token' => str_random(60)
        ]);

        if($customer){
            Customer::create([
                'customer_id' => $customer->id,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'telno' => $request->telno,
                'mobile' => $request->mobileno,
                'country' => $request->country,
                'address' => $request->address,
                'barangay' => $request->brgy,
                'city' => $request->city,
                'province' => $request->province,
                'zipcode' => $request->zipcode,
                'is_active' => 1,
                'provider' => $request->provider,
                'provider_id' => $request->provider_id,
                'reactivate_request' => 0
            ]);
        }
            
        return redirect(route('customer-front.login'))->with('success','Registration Successful!');

    }


    public function login(Request $request) {

        $page = new Page();
        $page->name = 'Login';

        return view('theme.stpaul.ecommerce.customer.login',compact('page'));

    }

    public function customer_login(Request $request)
    {
        $userCredentials = [
            'email'    => $request->email,
            'password' => $request->password
        ];

        $cart = session('cart', []);
        
        if (Auth::attempt($userCredentials)) {

            if(auth::user()->role_id != 3){ // block admin from using this login form
                Auth::logout();
                return back()->with('error', 'Administrative account are not allowed to login in this portal.'); 
            }

            if(Auth()->user()->is_active == 0){
                Auth::logout();
                return back()->with('warning','account inactive');
            }

            foreach ($cart as $order) {
                $product = Product::find($order['product_id']);
                $cart = Cart::where('product_id', $order['product_id'])
                    ->where('user_id', Auth::id())
                    ->first();

                if (!empty($cart)) {
                    $newQty = $cart->qty + $order['qty'];
                    $cart->update([
                        'qty' => $newQty,
                        'price' => $product->price,
                    ]);
                } else {
                    Cart::create([
                        'product_id' => $order['product_id'],
                        'user_id' => Auth::id(),
                        'qty' => $order['qty'],
                        'price' => $product->price,
                    ]);
                }
            }

            session()->forget('cart');
            $cnt = Cart::where('user_id',Auth::id())->count();
            if($cnt > 0)
                return redirect(route('cart.front.show'));
            else
                return redirect(route('home'));
        } else {
            Auth::logout();
            return back()->with('error', __('auth.login.incorrect_input'));    
        }

    }

    public function logout()
    {
        Auth::logout();

        return redirect(route('customer-front.login'));
    }

    public function forgot_password(Request $request) {

        $page = new Page();
        $page->name = 'Forgot Password';

        return view('theme.sysu.ecommerce.customer.forgot-password');

    }

    public function customer_forgot_password(Request $request) {

        return back();

    }

    public function register_guest(Request $request) {

        $page = new Page();
        $page->name = 'Forgot Password';

        return view('theme.sysu.ecommerce.customer.register-guest');
    }
}
