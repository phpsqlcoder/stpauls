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



class CartController extends Controller
{
    public function store(Request $request)
    {       
        $product = Product::whereId($request->product_id)->first();
        $qty = isset($request->qty) ? $request->qty : 1;
        
        if (auth()->check()) {
            
            $cart = Cart::where('product_id', $request->product_id)
                ->where('user_id', Auth::id())
                ->first();

            if (!empty($cart)) {

                $newQty = $cart->qty + $qty;
                $save = $cart->update([
                    'qty' => $newQty,
                    'price' => (isset($request->price)) ? $request->price : $product->price
                ]);
            } else {
                $save = Cart::create([
                    'product_id' => $request->product_id,
                    'user_id' => Auth::id(),
                    'qty' => $qty,
                    'price' => (isset($request->price)) ? $request->price : $product->price
                ]);
            }

        } else {
            $cart = session('cart', []);
            $not_exist = true;

            foreach ($cart as $key => $order) {
                if ($order->product_id == $request->product_id) {
                    $cart[$key]->qty = $qty;
                    $cart[$key]->price = (isset($request->price)) ? $request->price : $product->price;
                    $not_exist = false;
                    break;
                }
            }

            if ($not_exist) {
                $order = new Cart();
                $order->product_id = $request->product_id;
                $order->qty = $qty;
                $order->price = (isset($request->price)) ? $request->price : $product->price;

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

        if (auth()->check()) {
            
            $cart = Cart::where('product_id', $request->product_id)
                ->where('user_id', Auth::id())
                ->first();

            if (!empty($cart)) {

                $newQty = $cart->qty + $request->quantity;
                $save = $cart->update([
                    'qty' => $newQty,
                    'price' => $product->price
                ]);
            } else {
                $save = Cart::create([
                    'product_id' => $request->product_id,
                    'user_id' => Auth::id(),
                    'qty' => $request->quantity,
                    'price' => $product->price
                ]);
            }

        } else {
            $cart = session('cart', []);
            $not_exist = true;

            foreach ($cart as $key => $order){
                if ($order->product_id == $request->product_id) {
                    $cart[$key]->qty = $request->quantity;
                    $cart[$key]->price = $product->price;
                    $not_exist = false;
                    break;
                }
            }

            if ($not_exist) {
                $order = new Cart();
                $order->product_id = $request->product_id;
                $order->qty = $request->quantity;
                $order->price = $product->price;

                array_push($cart, $order);
            }

            session(['cart' => $cart]);
        }

        return redirect(route('cart.front.show'));
    }

    public function view()
    {
        if (auth()->check()) {
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
                return redirect()->route('product.front.list');
            }

            foreach($cartId as $key => $cart){
                Cart::whereId($cart)->update([
                    'qty' => $qty[$key],
                    'price' => $price[$key]
                ]);
            }
           
            return redirect()->route('cart.front.checkout');

        } else {
            $cart = session('cart', []);

            for ($x = 1; $x <= $request->total_products; $x++) {
                foreach ($cart as $key => $order) {
                    if ($order->product_id == $request->record_id[$x]) {
                        $cart[$key]->qty = $request->quantity[$x];
                        break;
                    }
                }
            }

            session(['cart' => $cart]);

            return redirect()->route('customer-front.login');
        }
    }

    // public function pay_again($id){
    //     $r = SalesHeader::findOrFail($id);

    //     $sales = 
    //     $urls = [
    //         'notification' => route('cart.payment-notification'),
    //         'result' => route('profile.sales'),
    //         'cancel' => route('profile.sales'),
    //     ];
       
    //     $base64Code = PaynamicsHelper::payNow($r->order_number, Auth::user(), $r->items, number_format($r->net_amount, 2, '.', ''), $urls, false ,number_format($r->delivery_fee_amount, 2, '.', ''));
    //      return view('theme.paynamics.sender', compact('base64Code'));
    // }

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
            $address = $request->billing_address;
        }

        if($request->shipOption != 2 && $request->shippingfee == 0){
            $deliveryStatus = 'Shipping Fee Validation';
        } else {
            // if($request->shipOption == 1 || $request->shipOption == 2){
            if($request->shipOption == 1){
                $deliveryStatus = 'Waiting for Approval';
            } else {
                $deliveryStatus = 'Waiting for Payment';
            }
        }

        $pickupdate = $request->input('pickup_date_2');
        $pickuptime = $request->input('pickup_time_2');

        $salesHeader = SalesHeader::create([
            'order_number' => $requestId,
            'customer_id' => Auth::id(),
            'customer_name' => $request->firstname.' '.$request->lastname,
            'customer_contact_number' => $request->mobile,
            'customer_address' => $address,
            'customer_delivery_adress' => $address,
            'order_source' => 'web',
            'delivery_tracking_number' => '',
            'delivery_courier' => '',
            'delivery_type' => $delivery_type->name,
            'delivery_fee_amount' => $request->shippingfee,
            'delivery_status' => $deliveryStatus,
            'gross_amount' => $request->totalDue,
            'tax_amount' => 0,
            'net_amount' => $request->totalDue,
            'discount_amount' => $request->loyaltydiscount,
            'payment_status' => 'UNPAID',
            'status' => 'active',
            'other_instruction' => $request->other_instruction,
            'user_id' => 0,
            'payment_method' => (!isset($request->payment_method)) ? 0 : $request->payment_method,
            'payment_option' => (!isset($request->payment_method)) ? 0 : $request->payment_option,
            'branch' => ($request->shipOption == 2)  ? $request->branch : NULL,
            'pickup_date' => ($request->shipOption == 2) ? $pickupdate : NULL,
            'pickup_time' => ($request->shipOption == 2) ? $pickuptime : NULL,
            'service_fee' => $request->servicefee,
            'is_approve' => 0,
            'is_other' => ($request->shipOption != 2 && $request->shippingfee == 0) ? 1 : 0
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
                    'discount_amount' => $promoChecker == 1 ? $price[$key]*('.'.$promoDetails->discount) : 0.00,
                    'gross_amount' => $request->totalDue,
                    'net_amount' => 0,
                    'qty' => $qty[$key],
                    'uom' => $product->uom,
                    'created_by' => Auth::id()
                ]);
            }
        // 

        Cart::where('user_id', Auth::id())->delete();

        // Loyalty
            $discountPurchaseAmount = 10000;
            if($request->totalDue >= $discountPurchaseAmount){

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

        if($request->payment_method == 1){
            $address_line1 = ($request->country == 259) ? $request->address: $request->billing_address;
            $address_line2 = ($request->country == 259) ? $request->barangay : '';
            $city          = ($request->country == 259) ? $data_city->city : '';
            $province      = ($request->country == 259) ? $data_province->province : '';
            $zipcode       = ($request->country == 259) ? $request->zipcode : '';
            $order         = $request;
            $uniqID        = $salesHeader->order_number;

            if($request->shipOption == 2){
                return view('theme.globalpay.payment_confirmation', compact('order','uniqID','address_line1','address_line2','city','province','zipcode'));
            } else {
                if($request->shippingfee > 0){
                    return view('theme.globalpay.payment_confirmation', compact('order','uniqID','address_line1','address_line2','city','province','zipcode'));
                } else {
                    return redirect(route('account-my-orders'))->with('success',' Order has been placed.');
                }
            }

        } else {
            return redirect(route('account-my-orders'))->with('success',' Order has been placed.');
        }
              
    }


    public function check_loyalty($purchasedAmount)
    {
        $discountPurchaseAmount = 10000;

        if($purchasedAmount >= $discountPurchaseAmount){

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
    }

    public function globalpay(Request $request)
    {
        // $sales = SalesHeader::find($request->orderid);
        
        // $address_line1 = '';
        // $address_line2 = '';
        // $city          = '';
        // $province      = '';
        // $zipcode       = '';
        // $order         = $request;
        // $uniqID        = $sales->order_number;
        
        // return view('theme.globalpay.payment_confirmation', compact('order','uniqID','address_line1','address_line2','city','province','zipcode'));

    }
}
