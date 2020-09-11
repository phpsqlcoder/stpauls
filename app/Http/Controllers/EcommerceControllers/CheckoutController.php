<?php

namespace App\Http\Controllers\EcommerceControllers;


use App\EcommerceModel\Cart;
use App\EcommerceModel\SalesDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\EcommerceModel\SalesHeader;
use App\Page;
use Auth;
use App\Deliverablecities;

use App\EcommerceModel\Customer;
use App\EcommerceModel\CheckoutOption;
use App\EcommerceModel\Branch;
use App\Provinces;

use App\StPaulModel\LoyalCustomer;
use App\StPaulModel\Discount;
use App\Setting;

class CheckoutController extends Controller
{

    public function checkout()
    {
        $page = new Page();
        $page->name = 'Checkout';
        
        $customer = Customer::find(Auth::id());
        $user = Auth::user();
        $products = Cart::where('user_id',Auth::id())->get();   

        $locations = Deliverablecities::where('status','PUBLISHED')->orderBy('city_name')->get();
        $provinces = Deliverablecities::where('status','PUBLISHED')->distinct()->get(['province']);
        $branches  = Branch::where('is_active',1)->get();
        

        $cod = CheckoutOption::find(1); // cash on delivery details
        $stp = CheckoutOption::find(2); // store pick up details
        $dtd = CheckoutOption::find(3); // same day delivery details
        $sdd = CheckoutOption::find(4); // same day delivery details

        ## Loyalty ##
        $qry_loyalty = LoyalCustomer::where('customer_id',Auth::id());
        $data_loyalty = $qry_loyalty->first();

        $settings = Setting::find(1);

        if($qry_loyalty->exists()){
            if($data_loyalty->status == 'APPROVED'){

                $discount = Discount::find($data_loyalty->discount_id);
                $loyalty_discount = $discount->discount;
            } else {
                $loyalty_discount = 0;
            }
        } else {
            $loyalty_discount = 0;
        }
        ## Loyalty ##
        
        
        if ($products->count() == 0) {
            return redirect()->route('product.front.list');
        }

        return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.cart.checkout', compact('customer','products','user','locations','provinces','page','cod','stp','sdd','dtd','branches','loyalty_discount','settings'));
    }

    public function ajax_deliverable_cities($id)
    {

        $data = Deliverablecities::where('province',$id)->get();

        return response($data);
    }
}
