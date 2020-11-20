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

                    return redirect(route('customer-front.login'))->with('error',"Sorry, we can't find this account. Please make sure that you have an existing social account.");
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

                if(User::where('email',$user->email)->exists()){

                    return redirect(route('customer-front.sign-up'))->with('error','This email address is already existing in the system. You may use other email address or login to your account.');

                } else {

                    session::forget('ptype');
                    return redirect(route('customer-front.sign-up',[ 
                        'email' => $user->email,
                        'firstname' => $first_name,
                        'lastname' => $last_name,
                        'provider' => $driver,
                        'provider_id' => $user->id
                    ]));
                }
            }
        }
    }

    protected function sendFailedResponse($msg = null)
    {
        return redirect()->route('social.login')
            ->withErrors(['msg' => $msg ?: 'Unable to login, try with another provider to login.']);
    }

    private function isProviderAllowed($driver)
    {
        return in_array($driver, $this->providers) && config()->has("services.{$driver}");
    }
}