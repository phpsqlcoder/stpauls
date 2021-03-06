<?php

namespace App\Http\Controllers\EcommerceControllers;

use App\EcommerceModel\Cart;
use App\Mail\SalesCompleted;
use Illuminate\Support\Facades\Mail;
use App\EcommerceModel\SalesPayment;
use App\EcommerceModel\SalesHeader;
use App\EcommerceModel\SalesDetail;
use App\Helpers\Webfocus\Setting;
use App\EcommerceModel\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\PaynamicsLog;
use App\Helpers\PaynamicsHelper;
use App\Page;
use Auth;
use Redirect;
use DateTime;

use Str;

use DB;
use App\EcommerceModel\Customer;
use App\EcommerceModel\CheckoutOption;
use App\StPaulModel\LoyalCustomer;

use App\Cities;
use App\Provinces;
use App\Countries;

use App\EcommerceModel\CouponCartDiscount;
use App\EcommerceModel\CouponCart;
use App\EcommerceModel\Coupon;
use App\EcommerceModel\CouponSale;

use App\User;



class CartController extends Controller
{
    public function deduct_qty(Request $request)
    {
        $cart = Cart::where('product_id', $request->product_id)
                ->where('user_id', Auth::id())
                ->decrement('qty',1);

        return response()->json([
            'success' => true,
            'totalItems' => Setting::EcommerceCartTotalItems()                
        ]);
    }

    public function store(Request $request)
    {       
        $product = Product::whereId($request->product_id)->first();
        $qty = isset($request->qty) ? $request->qty : 1;
        
        $saleChecker = DB::table('promos')->join('onsale_products','promos.id','=','onsale_products.promo_id')->where('promos.status','ACTIVE')->where('promos.is_expire',0)->where('onsale_products.product_id',$product->id)->count();

        if($saleChecker > 0){
            $discount = ($product->on_sale->promo_details->discount/100);
            $discountedAmount = ($product->price * $discount);

            $price = ($product->price - $discountedAmount);
        } else {
            if($product->discount > 0){
                $price = $product->price-$product->discount;
            } else {
                $price = $product->price;
            }  
        }
        

        if (auth()->check()) {
            
            $cart = Cart::where('product_id', $request->product_id)
                ->where('user_id', Auth::id())
                ->first();

            if (!empty($cart)) {


                $newQty = $cart->qty + $qty;
                $save = $cart->update([
                    'qty' => $newQty,
                    'price' => (isset($request->price)) ? $request->price : $price
                ]);
            } else {
                $save = Cart::create([
                    'product_id' => $request->product_id,
                    'user_id' => Auth::id(),
                    'qty' => $qty,
                    'price' => (isset($request->price)) ? $request->price : $price
                ]);
            }

        } else {
            $cart = session('cart', []);
            $not_exist = true;

            foreach ($cart as $key => $order) {
                if ($order->product_id == $request->product_id) {
                    $cart[$key]->qty = $qty;
                    $cart[$key]->price = (isset($request->price)) ? $request->price : $price;
                    $not_exist = false;
                    break;
                }
            }

            if ($not_exist) {
                $order = new Cart();
                $order->product_id = $request->product_id;
                $order->qty = $qty;
                $order->price = (isset($request->price)) ? $request->price : $price;

                array_push($cart, $order);
            }

            session(['cart' => $cart]);
        }
       
        $inventory_remark = true;

        if($inventory_remark){
            return response()->json([
                'success' => true,
                'totalItems' => Setting::EcommerceCartTotalItems()                
            ]);
            
        }else{
            return response()->json([
                'success' => false,
                'totalItems' => Setting::EcommerceCartTotalItems()                
            ]);
        }
    }

    public function buynow(Request $request)
    {   
        $product = Product::whereId($request->product_id)->first();

        $saleChecker = DB::table('promos')->join('onsale_products','promos.id','=','onsale_products.promo_id')->where('promos.status','ACTIVE')->where('promos.is_expire',0)->where('onsale_products.product_id',$product->id)->count();

        if($saleChecker > 0){
            $discount = ($product->on_sale->promo_details->discount/100);
            $discountedAmount = ($product->price * $discount);

            $price = ($product->price - $discountedAmount);
        } else {
            if($product->discount > 0){
                $price = $product->price-$product->discount;
            } else {
                $price = $product->price;
            }  
        }

        if (auth()->check()) {
            
            $cart = Cart::where('product_id', $request->product_id)
                ->where('user_id', Auth::id())
                ->first();

            if (!empty($cart)) {

                $newQty = $cart->qty + $request->quantity;
                $save = $cart->update([
                    'qty' => $newQty,
                    'price' => $price
                ]);
            } else {
                $save = Cart::create([
                    'product_id' => $request->product_id,
                    'user_id' => Auth::id(),
                    'qty' => $request->quantity,
                    'price' => $price
                ]);
            }

        } else {
            $cart = session('cart', []);
            $not_exist = true;

            foreach ($cart as $key => $order){
                if ($order->product_id == $request->product_id) {
                    $cart[$key]->qty = $request->quantity;
                    $cart[$key]->price = $price;
                    $not_exist = false;
                    break;
                }
            }

            if ($not_exist) {
                $order = new Cart();
                $order->product_id = $request->product_id;
                $order->qty = $request->quantity;
                $order->price = $price;

                array_push($cart, $order);
            }

            session(['cart' => $cart]);
        }

        return redirect(route('cart.front.show'));
    }

    public function view()
    {
        if (auth()->check()) {

            // reset coupon carts of customer
            CouponCartDiscount::where('customer_id',Auth::id())->delete();
            CouponCart::where('customer_id',Auth::id())->delete();

            $cart = Cart::where('user_id',Auth::id())->get();
            $totalProducts = $cart->count();
        } else {
            $cart = session('cart', []);
            $totalProducts = count(session('cart', []));
        }

        $page = new Page();
        $page->name = 'Cart';

        return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.cart.index', compact('cart', 'totalProducts','page'));
    }

    public function remove_product(Request $request)
    {
        if (auth()->check()) {
            Cart::whereId($request->order_id)->delete();
        } else {
            $cart = session('cart', []);
            $index = (int) $request->order_id;
            if (isset($cart[$index])) {
                unset($cart[$index]);
            }
            session(['cart' => $cart]);
        }

        return back();
    }

    public function proceed_checkout(Request $request)
    {  
        $data   = $request->all();
        $cartId = $data['cart_id'];
        $qty    = $data['qty'];
        $price  = $data['product_price'];

        if (auth()->check()) {        

            if (Cart::where('user_id', auth()->id())->count() == 0) {
                return redirect()->route('product.front.list',['new-products']);
            }

            foreach($cartId as $key => $cart){
                Cart::find($cart)->update([
                    'qty' => $qty[$key],
                    'price' => $price[$key]
                ]);
            }


            if($request->coupon_counter > 0){
                $data     = $request->all();
                $coupons  = $data['couponid'];
                $product  = $data['coupon_productid'];
                $usage    = $data['couponUsage'];
                $discount = $data['discount'];

                foreach($coupons as $key => $c){
                    $coupon = Coupon::find($c);

                    if($coupon->status == 'ACTIVE'){
                        CouponCart::create([
                            'coupon_id' => $coupon->id,
                            'product_id' => $product[$key] == 0 ? 0 : $product[$key],
                            'customer_id' => Auth::id(),
                            'total_usage' => $usage[$key],
                            'discount' => $discount[$key]
                        ]);
                    }
                }
            }

            CouponCartDiscount::create([
                'customer_id' => Auth::id(),
                'coupon_discount' => $request->coupon_total_discount
            ]);
            
           
            return redirect()->route('cart.front.checkout');
        } else {
            return redirect()->route('customer-front.login');
        }
    }

    public function save_sales(Request $request)
    {
        $today = getdate();
        $ran = microtime();
        $requestId = $today[0].substr($ran, 2,6);

        $delivery_type = CheckoutOption::find($request->shipOption);
        
        if($request->country == 259){
            $data_city     = Cities::find($request->city);
            $data_province = Provinces::find($request->province);
            $data_country  = Countries::find($request->country);

            $address = $request->address.' '.$request->barangay.', '.$data_city->city.' '.$data_province->province.', '.$request->zipcode.' '.$data_country->name;
        } else {
            $data_country  = Countries::find($request->country);
            $address = $request->billing_address.', '.$data_country->name;
        }

        if($request->islocation == 0){
            $deliveryStatus = 'Shipping Fee Validation';
        } else {
            if($request->shipOption == 1){
                $deliveryStatus = 'Waiting for Approval';
            } else {
                $deliveryStatus = 'Waiting for Payment';
            }
        }

        $net_amount = $request->net_amount;

        $coupon_discount = $request->coupon_discount;
        $sfee_discount = $request->sf_discount_amount;
        $loyaltydiscount = $request->loyaltydiscount;

        $total_discount =  $coupon_discount+$sfee_discount+$loyaltydiscount;

        $salesHeader = SalesHeader::create([
            'order_number' => $requestId,
            'customer_id' => Auth::id(),
            'customer_name' => $request->firstname.' '.$request->lastname,
            'email' => $request->email,
            'customer_contact_number' => $request->mobile,
            'customer_address' => $address,
            'customer_delivery_adress' => $address,
            'order_source' => 'web',
            'delivery_tracking_number' => '',
            'delivery_courier' => '',
            'delivery_type' => $delivery_type->name,
            'delivery_fee_amount' => $request->shippingfee,
            'delivery_status' => $deliveryStatus,
            'gross_amount' => number_format($net_amount,2,'.',''),
            'tax_amount' => 0,
            'net_amount' => number_format($net_amount,2,'.',''),
            'discount_amount' => $total_discount,
            'discount_percentage' => $request->loyaltydiscount,
            'payment_status' => 'UNPAID',
            'status' => 'active',
            'other_instruction' => $request->other_instruction,
            'user_id' => 0,
            'payment_method' => (!isset($request->payment_method)) ? 0 : $request->payment_method,
            'payment_option' => (!isset($request->payment_method)) ? 0 : $request->payment_option,
            'branch' => ($request->shipOption == 2)  ? $request->branch : NULL,
            'pickup_date' => ($request->shipOption == 2) ? $request->pickup_date : NULL,
            'service_fee' => $request->servicefee,
            'is_approve' => NULL,
            'is_other' => ($request->shipOption != 2 && $request->shippingfee == 0) ? 1 : 0,
            'sdd_booking_type' => ($request->has('bookingType') ? 1 : 0),
        ]);

        Customer::where('customer_id',Auth::id())->update([
            'mobile' => $request->mobile,
            'country' => $request->country,
            'address' => ($request->country == 259) ? $request->address : '',
            'barangay' => ($request->country == 259) ? $request->barangay : '',
            'city' => ($request->country == 259) ? $request->city : NULL,
            'province' => ($request->country == 259) ? $request->province : NULL,
            'zipcode' => $request->zipcode,
            'intl_address' => ($request->country <> 259) ? $request->billing_address : ''
        ]);

        // Ordered Products
        $data = $request->all();
        $productid = $data['productid'];
        $qty       = $data['qty'];
        $price     = $data['product_price'];

        foreach($productid as $key => $prodId){

            $product  = Product::find($prodId);
            $promoQry = DB::table('promos')->join('onsale_products','promos.id','=','onsale_products.promo_id')->where('promos.status','ACTIVE')->where('promos.is_expire',0)->where('onsale_products.product_id',$prodId);

            $promoChecker = $promoQry->count();
            if($promoChecker == 1){
               $promoDetails = $promoQry->first(); 
               $discount = $promoDetails->discount/100;
            }

            SalesDetail::create([
                'sales_header_id' => $salesHeader->id,
                'product_id' => $prodId,
                'product_name' => $product->name,
                'product_category' => $product->category_id,
                'price' => $price[$key],
                'tax_amount' => $price[$key]-($price[$key]/1.12),
                'promo_id' => $promoChecker == 1 ? $promoDetails->id : 0,
                'promo_description' => $promoChecker == 1 ? $promoDetails->name : '',
                'discount_amount' => $promoChecker == 1 ? $price[$key]*$discount : 0.00,
                'gross_amount' => $request->net_amount,
                'net_amount' => 0,
                'qty' => $qty[$key],
                'uom' => $product->uom,
                'created_by' => Auth::id()
            ]);
        }
        // 

        Cart::where('user_id', Auth::id())->delete();

        if($request->shipOption == 1){
            $admin = User::find(1);
            $admin->send_cod_approval_request_email($salesHeader);

            $customer = User::find(Auth::id());
            $customer->send_customer_cod_approval_request_email($salesHeader);
        }
        

        // Loyalty
            $discountPurchaseAmount = 10000;
            if($request->net_amount >= $discountPurchaseAmount){

                $qry = SalesHeader::where('customer_id',Auth::id())->where('net_amount','>=',$discountPurchaseAmount)->count();

                if($qry == 2){
                   LoyalCustomer::create([
                        'customer_name' => auth()->user()->fullname,
                        'customer_id' => Auth::id(),
                        'total_purchase' => 1,
                        'status' => 'PENDING',
                        
                    ]); 
                }
            }
        //

        // Coupon 
            if(isset($data['couponid'])){
                $coupons = $data['couponid'];
                $code = $data['couponcode'];
                $productid = $data['coupon_productid'];
                $discount = $data['coupon_productdiscount'];
                $sfee = $data['is_sfee'];
                
                foreach($coupons as $key => $c){
                    CouponSale::create([
                        'customer_id' => Auth::id(),
                        'coupon_id' => $c,
                        'coupon_code' => $code[$key],
                        'sales_header_id' => $salesHeader->id,
                        'order_status' => 'UNPAID',
                        'product_id' => $productid[$key],
                        'discount' => $discount[$key],
                        'is_sfee' => $sfee[$key]
                    ]);
                }
            }

            CouponCart::where('customer_id',Auth::id())->delete();
        //

        $customer = Customer::where('customer_id',Auth::id())->first();

        if($request->payment_method == 1){
            $address_line1 = ($request->country == 259) ? $request->address: $request->billing_address;
            $address_line2 = ($request->country == 259) ? $request->barangay : '';
            $city          = ($request->country == 259) ? $data_city->city : '';
            $province      = ($request->country == 259) ? $data_province->province : '';
            $zipcode       = ($request->country == 259) ? $request->zipcode : '';
            $uniqID        = $salesHeader->order_number;

            $firstname     = $customer->firstname;
            $lastname      = $customer->lastname;
            $email         = $customer->email;

            if($request->islocation == 0){
                return redirect(route('order.received',$requestId));
            } else {
                return view('theme.globalpay.payment_confirmation', compact('net_amount','uniqID','address_line1','address_line2','city','province','zipcode','firstname','lastname','email'));
            }

        } else {
            return redirect(route('order.payment-received',$requestId));
        }
              
    }

    public function globalpay(Request $request)
    {
        $sales = SalesHeader::find($request->orderid);
        $customer = Customer::where('customer_id',$sales->customer_id)->first();
        $data_city = Cities::find($customer->city);
        $data_province = Provinces::find($customer->province);

        $address_line1 = ($customer->country == 259) ? $customer->address: $customer->intl_address;
        $address_line2 = ($customer->country == 259) ? $customer->barangay : '';
        $city          = ($customer->country == 259) ? $data_city->city : '';
        $province      = ($customer->country == 259) ? $data_province->province : '';
        $zipcode       = $customer->zipcode;
        $order         = $sales;
        $uniqID        = $sales->order_number;

        $firstname     = $customer->firstname;
        $lastname      = $customer->lastname;
        $email         = $customer->email;
        
        return view('theme.globalpay.payment_confirmation', compact('order','uniqID','address_line1','address_line2','city','province','zipcode','firstname','lastname','email'));

    }
}
