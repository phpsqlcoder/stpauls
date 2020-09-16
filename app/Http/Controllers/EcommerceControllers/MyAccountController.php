<?php

namespace App\Http\Controllers\EcommerceControllers;

use App\EcommerceModel\Cart;
use App\EcommerceModel\SalesHeader;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Page;

use App\EcommerceModel\Customer;
use App\Provinces;

use Auth;

class MyAccountController extends Controller
{

    public function manage_account(Request $request)
    {
        $customer  = Customer::find(Auth::id());
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
      
        $personalInfo = $request->validate([
            'firstname' => 'required|max:150|regex:/^[\pL\s\-]+$/u',
            'lastname' => 'required|max:150|regex:/^[\pL\s\-]+$/u',
          
        ]);

        Customer::find(Auth::id())->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname
        ]);

        return back()->with('success-personal', 'Personal information has been updated.');
    }

    public function update_contact_info(Request $request)
    {
        $contactInfo = $request->only(['mobile', 'telno']);

        $validateData = Validator::make($contactInfo, [     
            "mobile" => "required|max:150",
        ]);

        if ($validateData->fails()) {
            return redirect($route)
                ->withErrors($validateData)
                ->withInput();
        }

        Customer::find(Auth::id())->update($contactInfo);

        return back()->with([
            'tabname' => 'contact-information',
            'success-contact' =>  'Personal information has been updated.'
        ]);
    }

    public function update_address_info(Request $request)
    {
        $addressInfo = $request->only(['address', 'barangay','province', 'city', 'zipcode']);

        $attributeNames = [
            "address" => "Street",
            "barangay" => "Barangay",
            "province" => "Province",
            "city" => "City",
            "zipcode" => "Zip Code",          
        ];

        $validateData = Validator::make($addressInfo, [            
            "address" => "required|max:150",
            "barangay" => "required|max:150",
            "province" => "required|max:150",
            "city" => "required|max:150",
            "zipcode" => "required|max:150"           
        ])->setAttributeNames($attributeNames);

        if ($validateData->fails()) {
            return redirect($route)
                ->withErrors($validateData)
                ->withInput();
        }

        Customer::find(Auth::id())->update($addressInfo);

        return back()->with([
            'tabname' => 'my-address',
            'success-address' =>  'Delivery address has been updated.'
        ]);
    }

    public function change_password()
    {
        $page = new Page();
        $page->name = 'Manage Account';
        return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.my-account.change-password',compact('page'));
    }

    public function update_password(Request $request)
    {
        $personalInfo = $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) {
                if (!\Hash::check($value, auth()->user()->password)) {
                    return $fail(__('The current password is incorrect.'));
                }
            }],
            'password' => [
                'required',
                'min:8',
                'max:150',               
            ],
            'confirm_password' => 'required|same:password',
        ]);

        auth()->user()->update(['password' => bcrypt($personalInfo['password'])]);

        return back()->with('success', 'Password has been updated');
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
