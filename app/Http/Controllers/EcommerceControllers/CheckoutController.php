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

        $provinces = Provinces::orderBy('province','asc')->get();
        $cities    = Cities::orderBy('city','asc')->get();
        $branches  = Branch::where('is_active',1)->get();


        $products  = Cart::where('user_id',Auth::id())->get();   
        $amount = 0;
        $weight = 0;
        foreach($products as $product){
            $amount += $product->price*$product->qty;
            $weight += $product->product->weight;
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


        return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.cart.checkout', compact('customer','products','amount','weight','provinces','cities','page','cod','stp','sdd','dtd','branches','loyalty_discount','payment_method'));

        // return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.cart.checkout', compact('customer','products','amount','user','locations','provinces','cities','page','cod','stp','sdd','dtd','branches','loyalty_discount','settings','payment_method'));
    }

    public function ajax_city_rates($id)
    {
        $request = explode('|', $id);

        $city = Cities::find($request[0]);

        $qry = ShippingfeeLocations::where('name',$city->city);
        if($qry->count() > 0){
            $data = $qry->first();

            $shippingfee = Shippingfee::find($data->shippingfee_id);
            $weight_rate = ShippingfeeWeight::where('shippingfee_id',$data->shippingfee_id)->where('weight','<=',$request[1])->latest()->first();

            $locationfee = $shippingfee->rate;
            $weightfee = $weight_rate->rate;
        } else {
            $locationfee = 0;
            $weightfee = 0;
        }

        return response()->json([
            'locationfee' => $locationfee,
            'weightfee' => $weightfee
        ]);
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
