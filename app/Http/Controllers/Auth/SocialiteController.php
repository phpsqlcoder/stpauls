<?php

namespace App\Http\Controllers\Auth;

use App\EcommerceModel\Customer;
use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

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
        return view('auth.social-login');
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

        return redirect(route('customer-front.sign-up',[ 
            'provider' => $driver, 
            'fname' => $first_name, 
            'lname' => $last_name, 
            'email' => $providerCostomer->getEmail(),
            'provide_id' => $providerCostomer->getId(),
            'token' => $providerCostomer->token
        ]));
    }

    private function isProviderAllowed($driver)
    {
        return in_array($driver, $this->providers) && config()->has("services.{$driver}");
    }
}