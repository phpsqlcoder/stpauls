<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

use App\EcommerceModel\Customer;
use App\EcommerceModel\Product;
use App\EcommerceModel\Cart;
use App\Page;
use App\User;

use Session;

class SocialiteController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected $providers = [
        'facebook','google'
    ];

    public function show()
    {
        return redirect(route('customer-front.sign-up'));
    }

    public function redirectToProvider($driver)
    {   
        session::put('ptype','signup');
        if( ! $this->isProviderAllowed($driver) ) {
            return $this->sendFailedResponse("{$driver} is not currently supported");
        }

        try {
            return Socialite::driver($driver)->redirect();
        } catch (Exception $e) {
            // You should show something simple fail message
            return $this->sendFailedResponse($e->getMessage());
        }
    }

    public function loginRedirectToProvider($driver)
    {
        session::put('ptype','login');
        if( ! $this->isProviderAllowed($driver) ) {
            return $this->sendFailedResponse("{$driver} is not currently supported");
        }

        try {
            return Socialite::driver($driver)->redirect();
        } catch (Exception $e) {
            // You should show something simple fail message
            return $this->sendFailedResponse($e->getMessage());
        }
    }

  
    public function handleProviderCallback( $driver )
    {
        try {
            if($driver == 'google'){
                $user = Socialite::driver($driver)->user();
            } else {
                $user = Socialite::driver($driver)->fields(['first_name','last_name','email','id'])->user();
            }
            
        } catch (Exception $e) {
            return $this->sendFailedResponse($e->getMessage());
        }

        // check for email in returned user
        if(empty($user->email )){
            $this->sendFailedResponse("No email id returned from {$driver} provider.");
        } else {
            if(session::get('ptype') == 'login'){
                $customer = Customer::where('provider_id',$user->getId())->where('provider',$driver);

                if($customer->exists()){

                    $customer = $customer->first();

                    $user = User::find($customer->customer_id);

                    if($user->is_active == 0){
                        Auth::logout();
                        return redirect(route('customer-front.login'))->with('warning','account inactive');
                    }
                    
                    if($user->role_id != 3){ // block admin from using this login form
                        Auth::logout();
                        return back()->with('error', 'Administrative account are not allowed to login in this portal.'); 
                    }

                    Auth::login($user);

                    $cart = session('cart', []);

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
                    session::forget('ptype');
                    $cnt = Cart::where('user_id',Auth::id())->count();
                    if($cnt > 0)
                        return redirect(route('cart.front.show'));
                    else
                        return redirect(route('home'));

                } else {

                    return redirect(route('customer-front.login'))->with('error',"Sorry, we can't find this account. Please make sure that you have an existing social media account.");
                }
            } else {
                switch($driver){
                    case 'facebook':
                      $first_name = $user['first_name'];
                      $last_name = $user['last_name'];
                      break;

                    case 'google':
                      $first_name = $user->offsetGet('given_name');
                      $last_name = $user->offsetGet('family_name');
                      break;

                    default:
                      $first_name = $user->getName();
                      $last_name = $user->getName();
                }

                $userQry = User::where('email',$user->email);

                if($userQry->exists()){

                    $customer = $userQry->update([
                        'name' => $first_name.' '.$last_name,
                        'email' => $user->email,
                        'firstname' => $first_name,
                        'lastname' => $last_name,
                        'email_verified_at' => now(),
                        'password' => \Hash::make(str_random(8)),
                        'is_active' => 1,
                    ]);

                    $customerData = $userQry->first();
                    if($customer){
                        Customer::where('customer_id',$customerData->id)->update([
                            'firstname' => $first_name,
                            'lastname' => $last_name,
                            'email' => $user->email,
                            'is_active' => 1,
                            'provider' => $driver,
                            'provider_id' => $user->id,
                            'user_id' => 1,
                            'is_subscriber' => 0,
                            'reactivate_request' => 0,
                        ]);
                    }

                    Auth::login($customerData);

                } else {

                    $customer = User::create([
                        'name' => $first_name.' '.$last_name,
                        'email' => $user->email,
                        'firstname' => $first_name,
                        'lastname' => $last_name,
                        'email_verified_at' => now(),
                        'password' => \Hash::make(str_random(8)),
                        'role_id' => 3,
                        'is_active' => 1,
                        'user_id' => 0,
                        'remember_token' => str_random(60),
                        'fromMigration' => 0,
                    ]);

                    if($customer){
                        Customer::create([
                            'customer_id' => $customer->id,
                            'firstname' => $first_name,
                            'lastname' => $last_name,
                            'email' => $user->email,
                            'is_active' => 1,
                            'provider' => $driver,
                            'provider_id' => $user->id,
                            'user_id' => 1,
                            'is_subscriber' => 0,
                            'reactivate_request' => 0,
                        ]);
                    }
                    Auth::login($customer);
                }

                 $cart = session('cart', []);

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
                session::forget('ptype');
                return redirect(route('my-account.manage-account'));
            }
        }
    }

    protected function sendFailedResponse($msg = null)
    {
        return redirect()->route('customer-front.login')
            ->withErrors(['msg' => $msgUnable ?: ' to login, try with another provider to login.']);
    }

    private function isProviderAllowed($driver)
    {
        return in_array($driver, $this->providers) && config()->has("services.{$driver}");
    }
}