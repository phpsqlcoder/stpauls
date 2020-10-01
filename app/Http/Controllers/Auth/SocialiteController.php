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
        return empty( $user->email )
            ? $this->sendFailedResponse("No email id returned from {$driver} provider.")
            : $this->createAccount($user, $driver);
    }

    protected function sendSuccessResponse()
    {
        return redirect()->intended('home');
    }

    protected function sendFailedResponse($msg = null)
    {
        return redirect()->route('social.login')
            ->withErrors(['msg' => $msg ?: 'Unable to login, try with another provider to login.']);
    }

    protected function createAccount($providerCostomer, $driver)
    {
        switch($driver){
           case 'facebook':
              $first_name = $providerCostomer['first_name'];
              $last_name = $providerCostomer['last_name'];
              break;

           case 'google':
              $first_name = $providerCostomer->offsetGet('given_name');
              $last_name = $providerCostomer->offsetGet('family_name');
              break;

           default:
              $first_name = $providerCostomer->getName();
              $last_name = $providerCostomer->getName();
        }

        $user = User::create([
            'firstname' => $first_name,
            'lastname' => $last_name,
            'email' => $providerCostomer->getEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // temporary password : password
            'role_id' => 3,
            'is_active' => 1,
            'remember_token' => str_random(60),
        ]);

        if($user){
            $customer = Customer::create([
                'customer_id' => $user->id,
                'firstname' => $first_name,
                'lastname' => $last_name,
                'email' => $providerCostomer->getEmail(),
                'is_active' => 1,
                'provider' => $driver,
                'provider_id' => $providerCostomer->getId(),
                'reactivate_request' => 0
            ]);

            return redirect(route('customer.socialite-set-password',[ 
                'email' => $providerCostomer->getEmail(),
            ]));
        }

    }

    private function isProviderAllowed($driver)
    {
        return in_array($driver, $this->providers) && config()->has("services.{$driver}");
    }
}