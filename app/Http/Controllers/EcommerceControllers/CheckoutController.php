<?php

namespace App\Http\Controllers\EcommerceControllers;


use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


use App\EcommerceModel\CheckoutOption;
use App\EcommerceModel\PaymentList;
use App\EcommerceModel\SalesHeader;
use App\EcommerceModel\SalesDetail;
use App\StPaulModel\LoyalCustomer;
use App\EcommerceModel\Branch;
use App\StPaulModel\Discount;
use App\EcommerceModel\Cart;
use App\Deliverablecities;

use App\Provinces;
use App\Countries;
use App\Cities;

use App\Setting;
use App\User;
use App\Page;
use Auth;


use App\ShippingfeeLocations;
use App\ShippingfeeWeight;
use App\Shippingfee;

class CheckoutController extends Controller
{

    public function checkout()
    {
        $page = new Page();
        $page->name = 'Checkout';
        
        $customer  = User::find(Auth::id());


        $products  = Cart::where('user_id',Auth::id())->get();   
        $amount = 0;
        $weight = 0;
        foreach($products as $product){
            $amount += $product->price*$product->qty;
            $weight += ($product->product->weight*$product->qty);
        }


        $cod = CheckoutOption::find(1); // cash on delivery details
        $stp = CheckoutOption::find(2); // store pick up details
        $dtd = CheckoutOption::find(3); // same day delivery details
        $sdd = CheckoutOption::find(4); // same day delivery details
        $payment_method = PaymentList::where('is_active',1)->get();

        ## Loyalty ##
        $qry_loyalty = LoyalCustomer::where('customer_id',Auth::id());

        

        if($qry_loyalty->exists()){
            $data_loyalty = $qry_loyalty->first();

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


        return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.cart.checkout', compact('customer','products','amount','weight','page','cod','stp','sdd','dtd','loyalty_discount','payment_method'));

        // return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.cart.checkout', compact('customer','products','amount','user','locations','provinces','cities','page','cod','stp','sdd','dtd','branches','loyalty_discount','settings','payment_method'));
    }

    public function ajax_city_rates(Request $request)
    {   
        if($request->country == 259){
            $city = Cities::find($request->city);
            $location = $city->city;
            
        } else {
            $country  = Countries::find($request->country);
            $location = $country->name; 
        }

        $sp_location = ShippingfeeLocations::where('name',$location);
        
        if($sp_location->count() > 0){
            $data = $sp_location->first();

            $sp        = Shippingfee::find($data->shippingfee_id);
            $sp_weight = ShippingfeeWeight::where('shippingfee_id',$data->shippingfee_id)->where('weight','<=',$request->weight)->latest('id')->first();

            if($sp->is_outside_manila == 0){ // within manila
                if($request->weight > 10){
                    $rate = ($sp->rate+$sp_weight->rate);
                } else {
                    $rate = $sp->rate;
                }
            } else {
                $rate = ($sp->rate+$sp_weight->rate);
            }
        } else {
            $rate = 0;
        }

        return response()->json(['rate' => $rate]);
    }

    public function remove_product(Request $request)
    {
        Cart::find($request->cartid)->delete();

        return response()->json();
    }

    public function ajax_deliverable_cities($id)
    {
        
    }
}
