<?php

namespace App\Http\Controllers\EcommerceControllers;

use App\Helpers\Webfocus\Setting;
use App\Mail\UpdatePasswordMail;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

use App\EcommerceModel\Customer;
use App\Page;
use App\User;

class EcommerceFrontController extends Controller
{
    public function forgot_password(Request $request)
    {
        $page = new Page();
        $page->name = 'Forgot Password';

        return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.customer.forgot-password', compact('page'));
    }

    public function sendResetLinkEmail(Request $request)
    {
        Validator::make($request->all(),[
            'email' => 'required|email',
        ])->validate();

        $qry = User::where('email', $request->email);
        $exist = $qry->exists();

        if($exist){
            $user = $qry->first();
            $user->customer_send_reset_password_email();

            if (Mail::failures()) {
                return back()->withInput($request->only('email'))->withErrors(['email' => trans('passwords.user')]);
            }

            return back()->with('status', trans('passwords.sent'));
        } else {
            return back()->with('error','Email does not match on our record.');
        }
        
    }

    use ResetsPasswords;

    public function showResetForm(Request $request, $token = null)
    {
        $page = new Page();
        $page->name = 'Reset Password';

        $credentials =  $request->only('email');


        if (is_null($user = $this->broker()->getUser($credentials))) {
            return abort(401);
        }

        if (!$this->broker()->tokenExists($user, $token)) {
            return redirect()->route('ecommerce.forgot_password')->with('error','Your link is expired. Please reset your password again.');
        }
        
        $user = User::where('email',$request->email)->first();

        return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.customer.reset-password',compact('page','user'))->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function reset(Request $request)
    {
        $credentials = $request->validate([
            'token' => 'required',
            'email' => 'required|email',
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
        ]);

        if (is_null($user = $this->broker()->getUser($request->only('email')))) {
            return abort(401);
        }

        if (!$this->broker()->tokenExists($user, $credentials['token'])) {
            return redirect()->route('ecommerce.forgot_password')->with('error','Your link is expired. Please reset your password again.');
        }

        $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        return redirect()->route('customer-front.login')->with('success', 'Your password has been changed! To login, please use your new password.');
    }

    public function showReactivateForm()
    {
        $page = new Page();
        $page->name = 'Reactivate Account';

       return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.customer.reactivate',compact('page'));
    }

    public function sendReactivateRequest(Request $request)
    {
        $request->validate(
            ['email' => ['required', 'email'] ]
        );

        $qry = User::where('email', $request->email);

        if($qry->count() > 0){
            $customer = $qry->first();

            Customer::where('customer_id',$customer->id)->update(['reactivate_request' => 1]);

            return back()->with('success','Account reactivation request has been sent to administrator.');
        } else {
            return back()->with('error','These email do not match our records.');
        }
    }
}
