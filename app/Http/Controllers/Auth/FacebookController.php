<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

use App\EcommerceModel\Customer;
use App\Http\Controllers\Controller;
use Socialite;
use Exception;
use Auth;
use App\User;
use Cookie;

class FacebookController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();           
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function handleFacebookCallback()
    {

        $fbuser = Socialite::driver('facebook')->fields(['first_name','middle_name','last_name','email','id'])->user();


        return redirect(route('customer-front.sign-up',[ 'fname' => $fbuser['first_name'], 'mname' => $fbuser['middle_name'], 'lname' => $fbuser['last_name'], 'email' => $fbuser['email'], 'fb_id' => $fbuser['id'] ]));
        
    }


    protected function loggedOut()
    {
        Auth::logout();

        return redirect(route('fb'));
    }
}
