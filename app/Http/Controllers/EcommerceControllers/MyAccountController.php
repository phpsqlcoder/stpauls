<?php

namespace App\Http\Controllers\EcommerceControllers;

use App\EcommerceModel\Cart;
use App\EcommerceModel\SalesHeader;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Page;

use App\EcommerceModel\Customer;
use App\User;
use App\Provinces;
use App\Setting;

use Auth;

class MyAccountController extends Controller
{

    public function manage_account(Request $request)
    {
        $customer  = User::find(Auth::id());
        $provinces = Provinces::orderBy('province','asc')->get();

        $selectedTab = 0;

        if ($request->has('tab')) {
            $selectedTab = ($request->tab == 'contact-information') ? 1 : 0;
            $selectedTab = ($request->tab == 'my-address') ? 2 : $selectedTab;
        }

        $page = new Page();
        $page->name = 'Manage Account';
        return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.my-account.manage-account', compact('customer', 'provinces', 'selectedTab','page'));
    }

    public function update_personal_info(Request $request)
    { 
        $personalInfo = $request->only(['firstname', 'lastname']);

        $this->validate(
            $request,[
                'firstname' => 'required',
                'lastname' => 'required'
            ],
            [
                'firstname.required' => 'The first name field is required.',
                'lastname.required' => 'The last name field is required.'
            ]  
        );

        Customer::where('customer_id',Auth::id())->update($personalInfo);
        User::find(Auth::id())->update($personalInfo);

        return back()->with('success-personal', 'Personal information has been updated.');
    }

    public function update_contact_info(Request $request)
    {
        $contactInfo = $request->only(['mobile', 'telno']);

        $validateData = Validator::make(
            $contactInfo, 
            ['mobile' => 'required'],
            ['mobile.required' => 'The mobile number field is required.']
        );

        if ($validateData->fails()) {
            return back()->with([
                'tabname' => 'contact-information',
            ])->withErrors($validateData)->withInput();
        }

        Customer::where('customer_id',Auth::id())->update($contactInfo);

        return back()->with([
            'tabname' => 'contact-information',
            'success-contact' =>  'Contact information has been updated.'
        ]);
    }

    public function update_address_info(Request $request)
    {
        $addressInfo = $request->only(['address', 'barangay', 'province', 'city','zipcode']);

        $validateData = Validator::make(
            $addressInfo, 
            [
                'address' => 'required',
                'barangay' => 'required',
                'province' => 'required',
                'city' => 'required',
                'zipcode' => 'required'
            ],
            [
                'address.required' => 'The address line 1 field is required.',
                'barangay.required' => 'The address line 2 field is required.',
                'zipcode.required' => 'The zip code field is required.'
            ]
        );

        if ($validateData->fails()) {
           return back()->with([
                'tabname' => 'my-address',
            ])->withErrors($validateData)->withInput();
        }

        Customer::where('customer_id',Auth::id())->update($addressInfo);

        return back()->with([
            'tabname' => 'my-address',
            'success-address' =>  'Delivery address has been updated.'
        ]);
    }

    public function change_password()
    {
        $page = new Page();
        $page->name = 'Change Password';
        return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.my-account.change-password',compact('page'));
    }

    public function update_password(Request $request)
    {
        $customerInfo = $request->validate([
            'password' => 'required|max:150|min:8',
            'confirm_password' => 'required|same:password',

        ]);

        $customer = User::find(Auth::id());
        
        if(\Hash::check($request->current_password, $customer->password)){
            $customer->update(['password' => bcrypt($request->password)]);

            return back()->with('success-change-password', 'Password has been updated.');
        } else {
            return back()->with('error-change-password', 'Incorrect password.');
        }
    }

    public function pay_now(Request $request, $orderNumber)
    {
        $sale = SalesHeader::where('user_id', auth()->id())->where('order_number', $orderNumber)->first();

        if (empty($sale)) {
            abort(404);
        }

        $requestId = $orderNumber;
        $member = auth()->user()->profile;
        $products = $sale->items;

        return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.cart.paynamics-sender', compact('products','requestId', 'member'));

    }

    public function reorder(Request $request, $orderNumber)
    {
        $sale = SalesHeader::where('user_id', auth()->id())->where('order_number', $orderNumber)->first();

        if (empty($sale)) {
            abort(404);
        }

        foreach ($sale->items as $item) {
            Cart::create([
                'product_id' => $item->product_id,
                'user_id' => $sale->user_id,
                'qty' => $item->qty,
                'price' => $item->product->price,
                'with_installation' => $item->product->with_installation,
                'installation_fee' => $item->product->installation_fee
            ]);
        }

        $sale->delete();

        return redirect(route('cart.front.show'));
//        return redirect()->back()->with('success', 'Reorder has been successful');
    }
}
