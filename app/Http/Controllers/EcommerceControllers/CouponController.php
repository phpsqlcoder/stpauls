<?php

namespace App\Http\Controllers\EcommerceControllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Helpers\ListingHelper;


use App\EcommerceModel\Coupon;
use App\EcommerceModel\Product;
use App\EcommerceModel\ProductCategory;
use App\User;
use App\Deliverablecities;

use \App\MailingListModel\Group;

use Carbon\Carbon;
use Auth;


use App\Provinces;
use App\Countries;
use App\Cities;

class CouponController extends Controller
{
    private $searchFields = ['name'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listing = new ListingHelper('desc', 10, 'updated_at');

        $coupons = $listing->simple_search(Coupon::class, $this->searchFields);

        // Simple search init data
        $filter = $listing->get_filter($this->searchFields);
        $searchType = 'simple_search';

        return view('admin.coupon.index',compact('coupons', 'filter', 'searchType'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories =  ProductCategory::has('published_products')->where('status','PUBLISHED')->get();
        $brands = Product::whereNotNull('brand')->distinct()->get(['brand']);
        $products = Product::where('status','PUBLISHED')->orderBy('name','asc')->get();

        $provinces = Provinces::orderby('province','asc')->get();
        $countries = Countries::orderby('name','asc')->get();

        $subscribers_group = Group::orderBy('name','asc')->get();
        $free_products = Product::where('category_id',129)->get();

        return view('admin.coupon.create',compact('categories','brands','provinces','countries','products','subscribers_group','free_products'));
    }

    public function customer_lookup()
    {
       $customers = User::where('role_id',3)->where('is_active',1)->get();
       $arr_customer = array();

        foreach($customers as $customer){
            $arr_customer[] = [
                "value" => $customer->id,
                "text" => $customer->name
            ];
        }

        return json_encode($arr_customer);
    }

    public function product_lookup()
    {
       $products = Product::where('status','PUBLISHED')->get();
       $arr_products = array();

        foreach($products as $product){
            $arr_products[] = [
                "value" => $product->id,
                "text" => $product->name
            ];
        }

        return json_encode($arr_products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|max:150',
            'description' => 'required',
            'terms_and_conditions' => 'required',
            'customer' => $request->coupon_scope == 'specific' ? 'required' : '',
            'reward' => 'required',
            'code' => $request->coupon_activation == 'manual' ? 'required|unique:coupons,coupon_code' : '',
            'reward' => 'required',
            'sf_area' => $request->reward == 'free-shipping-optn' ? 'required' : '',
            'shipping_fee_discount_amount' => ($request->reward == 'free-shipping-optn' && $request->discount_type == 'partial') ? 'required' : '',
            'discount_amount' => $request->reward == 'discount-amount-optn' ? 'required' : '',
            'discount_percentage' => $request->reward == 'discount-percentage-optn' ? 'required' : '',
            'free_product_id' => $request->reward == 'free-product-optn' ? 'required' : '',
        ])->validate();

        $data = $request->all();

        $loc = '';
        if($request->reward == 'free-shipping-optn'){
            if($request->sf_area == 'all'){
                $loc = NULL;
            }

            if($request->sf_area == 'local' || $request->sf_area == 'intl'){

                if($request->sf_area == 'local'){
                    $locations = $data['cities'];
                } else {
                    $locations = $data['countries'];
                }

                foreach($locations as $l){
                    $loc .= $l.'|';
                }
            }   

            $loc_discount_type = $request->discount_type;
            $loc_discount_amount = $request->shipping_fee_discount_amount;
             
        } else {
            $loc = NULL;
            $loc_discount_type = NULL;
            $loc_discount_amount = 0;
        }

        $customernames = '';
        if(isset($request->customer)){
            $customers = explode(',',$request->customer);

            foreach($customers as $c){
                if($c != ''){
                    $customernames .= $c.'|';
                }
            }
        }

        $subscribers_id = '';
        if(isset($request->subscribers_group)){
            $groups = $data['subscribers_group'];
            foreach($groups as $group){
                if($group != ''){
                    $subscribers_id .= $group.'|';
                }
            }
        }

        $amount_discount = 1;
        if($request->reward == 'discount-amount-optn' || $request->reward == 'discount-percentage-optn'){
            $amount_discount = $request->amount_discount;
        }

        $discount_productid = NULL;
        if($request->product_discount == 'current'){
            $discount_productid = NULL;
        }

        if($request->product_discount == 'specific'){
            $discount_productid = $request->discount_productid;
        }

        $coupon = Coupon::create([
            'coupon_code' => $request->coupon_activation == 'manual' ? $request->code : Coupon::generate_unique_code(),
            'name' => $request->name,
            'description' => $request->description,
            'terms_and_conditions' => $request->terms_and_conditions,
            'activation_type' => $request->coupon_activation,
            'customer_scope' => $request->coupon_scope,
            'scope_customer_id' => $request->coupon_scope == 'specific' ? $customernames : NULL,
            'scope_subscriber_group_id' => $request->coupon_scope == 'subscribers' ? $subscribers_id : NULL,
            'area' => $request->sf_area,
            'location' => $loc,
            'location_discount_type' => $loc_discount_type,
            'location_discount_amount' => $loc_discount_amount,
            'amount' => $request->reward == 'discount-amount-optn' ? $request->discount_amount : NULL,
            'percentage' => $request->reward == 'discount-percentage-optn' ? $request->discount_percentage : NULL,
            'free_product_id' => $request->free_product_id,
            'status' => ($request->has('status') ? 'ACTIVE' : 'INACTIVE'),
            'amount_discount_type' => $amount_discount,
            'product_discount' => $request->amount_discount == 2 ? $request->product_discount : NULL,
            'discount_product_id' => $discount_productid,
            'availability' => 1,
            'user_id' => Auth::id(),
        ]);

        if($coupon){
            $this->update_coupon_time_settings($coupon->id,$request);            
            $this->update_coupon_purchase_settings($coupon->id,$request);
            $this->update_coupon_rule_settings($coupon->id,$request);
        }
        

        return redirect(route('coupons.index'))->with('success','Coupon has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function show(Coupon $coupon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon)
    {
        $categories =  ProductCategory::has('published_products')->where('status','PUBLISHED')->get();
        $brands = Product::whereNotNull('brand')->distinct()->get(['brand']);
        $products = Product::where('status','PUBLISHED')->orderBy('name','asc')->get();

        $provinces = Provinces::orderby('province','asc')->get();
        $countries = Countries::orderby('name','asc')->get();

        $subscribers_group = Group::orderBy('name','asc')->get();
        $free_products = Product::where('category_id',129)->get();

        return view('admin.coupon.edit',compact('coupon','categories','brands','provinces','countries','products','subscribers_group','free_products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coupon $coupon)
    {
        Validator::make($request->all(), [
            'name' => 'required|max:150',
            'description' => 'required',
            'terms_and_conditions' => 'required',
            'customer' => $request->coupon_scope == 'specific' ? 'required' : '',
            'reward' => 'required',
            'code' => $request->coupon_activation == 'manual' ? 'required' : '',
            'reward' => 'required',
            'sf_area' => $request->reward == 'free-shipping-optn' ? 'required' : '',
            'shipping_fee_discount_amount' => ($request->reward == 'free-shipping-optn' && $request->discount_type == 'partial') ? 'required|min:1' : '',
            'discount_amount' => $request->reward == 'discount-amount-optn' ? 'required' : '',
            'discount_percentage' => $request->reward == 'discount-percentage-optn' ? 'required' : '',
            'free_product_id' => $request->reward == 'free-product-optn' ? 'required' : '',
        ])->validate();

        $data = $request->all();

        $loc = '';
        if($request->reward == 'free-shipping-optn'){
            if($request->sf_area == 'all'){
                $loc = NULL;
            }

            if($request->sf_area == 'local' || $request->sf_area == 'intl'){

                if($request->sf_area == 'local'){
                    $locations = $data['cities'];
                } else {
                    $locations = $data['countries'];
                }

                foreach($locations as $l){
                    $loc .= $l.'|';
                }
            }   

            $loc_discount_type = $request->discount_type;
            $loc_discount_amount = $request->shipping_fee_discount_amount;
             
        } else {
            $loc = NULL;
            $loc_discount_type = NULL;
            $loc_discount_amount = 0;
        }


        $customernames = '';
        if(isset($request->customer)){
            $customers = explode(',',$request->customer);

            foreach($customers as $c){
                if($c != ''){
                    $customernames .= $c.'|';
                }
            }
        }

        $subscribers_id = '';
        if(isset($request->subscribers_group)){
            $groups = $data['subscribers_group'];
            foreach($groups as $group){
                if($group != ''){
                    $subscribers_id .= $group.'|';
                }
            }
        }


        $amount_discount = 1;
        if($request->reward == 'discount-amount-optn' || $request->reward == 'discount-percentage-optn'){
            $amount_discount = $request->amount_discount;
        }

        $discount_productid = NULL;
        if($request->product_discount == 'current'){
            $discount_productid = NULL;
        }

        if($request->product_discount == 'specific'){
            $discount_productid = $request->discount_productid;
        }

        Coupon::find($coupon->id)->update([
            'coupon_code' => $request->coupon_activation == 'manual' ? $request->code : Coupon::generate_unique_code(),
            'name' => $request->name,
            'description' => $request->description,
            'terms_and_conditions' => $request->terms_and_conditions,
            'activation_type' => $request->coupon_activation,
            'customer_scope' => $request->coupon_scope,
            'scope_customer_id' => $request->coupon_scope == 'specific' ? $customernames : NULL,
            'scope_subscriber_group_id' => $request->coupon_scope == 'subscribers' ? $subscribers_id : NULL,
            'area' => $request->reward == 'free-shipping-optn' ? $request->sf_area : NULL,
            'location' => $request->reward == 'free-shipping-optn' ? $loc : NULL,
            'location_discount_type' =>  $request->reward == 'free-shipping-optn' ? $loc_discount_type : NULL,
            'location_discount_amount' =>  $request->reward == 'free-shipping-optn' ? $loc_discount_amount : NULL,
            'amount' => $request->reward == 'discount-amount-optn' ? $request->discount_amount : NULL,
            'percentage' => $request->reward == 'discount-percentage-optn' ? $request->discount_percentage : NULL,
            'free_product_id' => $request->free_product_id,
            'status' => ($request->has('status') ? 'ACTIVE' : 'INACTIVE'),
            'amount_discount_type' => $amount_discount,
            'product_discount' => $request->amount_discount == 2 ? $request->product_discount : NULL,
            'discount_product_id' => $discount_productid,
            'user_id' => Auth::id(),
        ]);

        if($coupon){
            
            $this->update_coupon_time_settings($coupon->id,$request);            
            $this->update_coupon_purchase_settings($coupon->id,$request);
            $this->update_coupon_rule_settings($coupon->id,$request);
        }

        return back()->with('success','Coupon details has been updated.');
    }

    public function update_coupon_time_settings($couponID,$request)
    {
        $starttime = Carbon::parse($request->starttime)->format('H:i');
        $endtime = Carbon::parse($request->endtime)->format('H:i');
        
        Coupon::find($couponID)->update([
            'start_date' => $request->coupon_time[0] == 'datetime' ? $request->startdate : NULL,
            'end_date' => $request->coupon_time[0] == 'datetime' ? $request->enddate : NULL,
            'start_time' => isset($request->starttime) ? $starttime : NULL,
            'end_time' => isset($request->endtime) ? $endtime : NULL,
            'event_name' => $request->coupon_time[0] == 'custom' ? $request->eventname : NULL,
            'event_date' => $request->coupon_time[0] == 'custom' ? $request->eventdate : NULL,
            'repeat_annually' => $request->has('repeat_annually') ? 1 : 0,
        ]);
    }

    public function update_coupon_purchase_settings($couponID,$request)
    {   
        $data = $request->all();
        $productnames = NULL;
        $productcategories = NULL;
        $productbrand = NULL;
        $totalamount = NULL;
        $totalqty = NULL;
        $amounttype = NULL;
        $qtytype = NULL;

        $coupon_combination_counter = 0;
        $coupon_combination = '';

        if($request->has('purchase_product')){
            $coupon_combination_counter++;
            if(isset($request->product_name)){
                $prodname = $data['product_name'];
                $coupon_combination .= 'product|';
                foreach($prodname as $prod){
                    $productnames .= $prod.'|';
                }
            }

            if(isset($request->product_brand)){
                $prodbrand = $data['product_brand'];
                $coupon_combination .= 'product|';
                foreach($prodbrand as $brand){
                    $productbrand .= $brand.'|';
                }
            } else{
               if(isset($request->product_category)){
                    $prodcat = $data['product_category'];
                    $coupon_combination .= 'product|';
                    foreach($prodcat as $cat){
                        $productcategories .= $cat.'|';
                    }
                } 
            }
            
        }

        if($request->has('purchase_total_amount')){
            $coupon_combination .= 'amount|';
            $coupon_combination_counter++;
            $totalamount = $request->purchase_amount;
            $amounttype = $request->amount_opt;
        }

        if($request->has('purchase_total_qty')){
            $coupon_combination .= 'qty|';
            $coupon_combination_counter++;
            $totalqty = $request->purchase_qty;
            $qtytype = $request->qty_opt;
        }

        Coupon::find($couponID)->update([
            'purchase_product_id' => $productnames,
            'purchase_product_cat_id' => $productcategories,
            'purchase_product_brand' => $productbrand,
            'purchase_amount' => $totalamount,
            'purchase_qty' =>  $totalqty,
            'purchase_amount_type' => $amounttype,
            'purchase_qty_type' =>  $qtytype,
            'purchase_combination_counter' => $coupon_combination_counter,
            'purchase_combination' => $coupon_combination
        ]);
    }

    public function update_coupon_rule_settings($couponID,$request)
    {
        Coupon::find($couponID)->update([
            'customer_limit' => isset($request->customer_limit) ? $request->coupon_customer_limit_qty : 100000,
            'combination' => ($request->has('combination')) ? 1 : 0,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon)
    {
        //
    }

    public function update_status($id,$status)
    {
        Coupon::find($id)->update([
            'status' => $status,
            'user_id' => Auth::id()
        ]);

        return back()->with('success', __('standard.coupons.status_update_success', ['STATUS' => $status]));
    }

    public function single_delete(Request $request)
    {
        $coupon = Coupon::findOrFail($request->coupons);
        $coupon->update([ 'user' => Auth::id() ]);
        $coupon->delete();

        return back()->with('success', __('standard.coupons.single_delete_success'));
    }

    public function restore($coupon){
        Coupon::withTrashed()->find($coupon)->update(['status' => 'INACTIVE','user_id' => Auth::id() ]);
        Coupon::whereId($coupon)->restore();

        return back()->with('success', __('standard.coupons.restore_promo_success'));
    }

    public function multiple_change_status(Request $request)
    {
        $coupons = explode("|", $request->coupons);

        foreach ($coupons as $coupon) {
            $publish = Coupon::where('status', '!=', $request->status)->whereId($coupon)->update([
                'status'  => $request->status,
                'user_id' => Auth::id()
            ]);
        }

        return back()->with('success',  __('standard.coupons.multiple_status_update_success', ['STATUS' => $request->status]));
    }

    public function multiple_delete(Request $request)
    {
        $coupons = explode("|",$request->coupons);

        foreach($coupons as $coupon){
            Coupon::whereId($coupon)->update(['user_id' => Auth::id() ]);
            Coupon::whereId($coupon)->delete();
        }

        return back()->with('success', __('standard.coupons.multiple_delete_success'));
    }

}
