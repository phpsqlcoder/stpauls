<?php

namespace App\Http\Controllers\EcommerceControllers;

use App\EcommerceModel\Cart;
use App\EcommerceModel\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Webfocus\Setting;
use Illuminate\Validation\Rule;
use Session;
use App\Page;
use App\EcommerceModel\Product;


use App\User;

use App\EcommerceModel\Customer;

use App\Deliverablecities;
use App\Provinces;
use App\Cities;

class CustomerFrontController extends Controller
{

    public function sign_up(Request $request) {

        $page = new Page();
        $page->name = 'Sign Up';
        
        $fbdata = $request;

        $provinces = Provinces::orderBy('province','asc')->get();

        return view('theme.stpaul.ecommerce.customer.sign-up',compact('page','fbdata','provinces'));

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


    public function customer_sign_up(Request $request) {

        Validator::make($request->all(), [
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email|max:191|unique:users',
            'password' => 'min:8|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:8',
            'address' => 'required',
            'brgy' => 'required',
            'province' => 'required',
            'city' => 'required',
            'mobileno' => 'required',
            'zipcode' => 'required',
        ])->validate();

       
        $customer = Customer::create([
            'email' => $request->email,
            'password' => \Hash::make($request->password),
            'firstname' => $request->fname,
            'lastname' => $request->lname,
            'telno' => $request->telno,
            'mobile' => $request->mobileno,
            'address' => $request->address,
            'barangay' => $request->brgy,
            'city' => $request->city,
            'province' => $request->province,
            'zipcode' => $request->zipcode,
            'is_active' => 1,
            'provider' => '',
            'fbId' => '',
            'googleId' => '',
            'is_subscriber' => $request->has('subscriber'),
            'remember_token' => str_random(10),
        ]);   

        Auth::loginUsingId($customer->id);

        return redirect(route('home'))->with('success','Registration Successful!');
    }

    public function get_random_code($length = 6)
    {
        $token = "";
        $codeAlphabet= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet); // edited

        $member = \App\EcommerceModel\Member::where('code', $token)->first();

        while($token == "" || $member) {
            $token = "";
            for ($i = 0; $i < $length; $i++) {
                $token .= $codeAlphabet[random_int(0, $max-1)];
            }
            $member = \App\EcommerceModel\Member::where('code', $token)->first();
        }

        return $token;
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

        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            unset($userCredentials['username']);
            $userCredentials['email'] = $request->email;
        }

        $cart = session('cart', []);
        
        if (Auth::guard('customer')->attempt($userCredentials)) {

            $customer = Customer::where('email',$request->email)->first();

            if($customer->is_active == 0){
                return back()->with('warning','account inactive');
            }

            Auth::loginUsingId($customer->id);

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
                        'paella_price' => $order['paella_price']
                    ]);
                } else {
                    Cart::create([
                        'product_id' => $order['product_id'],
                        'user_id' => Auth::id(),
                        'qty' => $order['qty'],
                        'price' => $product->price,
                        'paella_price' => $order['paella_price']
                    ]);
                }
            }

            session()->forget('cart');
            $cnt = Cart::where('user_id',Auth::id())->count();
            if($cnt > 0)
                return redirect(route('cart.front.show'));
            else
                // return redirect(route('product.front.list'));
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
