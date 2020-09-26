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

    public function pay_again($id){
        $r = SalesHeader::findOrFail($id);

        $sales = 
        $urls = [
            'notification' => route('cart.payment-notification'),
            'result' => route('profile.sales'),
            'cancel' => route('profile.sales'),
        ];
       
        $base64Code = PaynamicsHelper::payNow($r->order_number, Auth::user(), $r->items, number_format($r->net_amount, 2, '.', ''), $urls, false ,number_format($r->delivery_fee_amount, 2, '.', ''));
         return view('theme.paynamics.sender', compact('base64Code'));
    }

    public function save_sales(Request $request)
    {
        $today = getdate();
        $ran = microtime();
        $requestId = $today[0].substr($ran, 2,6);

        $delivery_type = CheckoutOption::find($request->shipOption);

        if($request->province == 0){
            $address = $request->other_address;
        } else {
            $data_city = Cities::find($request->city);
            $data_province = Provinces::find($request->province);

            $address = $request->address.' '.$request->barangay.', '.$data_city->city.' '.$data_province->province.', '.$request->zipcode;
        }

        $pickupdate = $request->input('pickup_date_'.$request->shipOption);
        $pickuptime = $request->input('pickup_time_'.$request->shipOption);

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
            'delivery_fee_amount' => $request->deliveryfee,
            'delivery_status' => ($request->shipOption == 1) ? 'Waiting for Approval' : 'Waiting for Payment',
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
            'branch' => ($request->shipOption == 2)  ? $request->branch : 0,
            'pickup_date' => ($request->shipOption <= 2) ? $pickupdate : NULL,
            'pickup_time' => ($request->shipOption <= 2) ? $pickuptime : NULL,
            'service_fee' => $request->servicefee
        ]);

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

        Cart::where('user_id', Auth::id())->delete();
        $this->check_loyalty($request->totalDue);

        if($request->payment_method == 1){
            $address_line1 = $request->address;
            $address_line2 = $request->barangay;
            $city          = $data_city->city;
            $province      = $data_province->province;
            $zipcode       = $request->zipcode;
            $order         = $request;
            $uniqID        = $salesHeader->order_number;
            return view('theme.globalpay.payment_confirmation', compact('order','uniqID','address_line1','address_line2','city','province','zipcode'));
        } else {
            return redirect(route('account-my-orders'))->with('success',' Order has been placed.');
        }
       
    }


    public function check_loyalty($amount)
    {
        $discountBracket = 10000;

        if($amount >= $discountBracket){
            $qry = LoyalCustomer::where('customer_id',Auth::id());

            if($qry->exists()) {
                $customer = $qry->first();

                $qry->update(['total_purchase' => ($customer->total_purchase+1) ]);
            } else {
                LoyalCustomer::create([
                    'customer_id' => Auth::id(),
                    'total_purchase' => 1,
                    'status' => 'PENDING'
                ]);
            }
        }
    }

    public function receive_data_from_payment_gateway(Request $request)
    {
        $paymentResponse = (isset($_POST['paymentresponse'])) ? $_POST['paymentresponse'] : null;

        if (empty($paymentResponse)) {
            return false;
        }
        
        $body = str_replace(" ", "+", $paymentResponse);

        try {
            $Decodebody = base64_decode($body);
            $ServiceResponseWPF = simplexml_load_string($Decodebody, 'SimpleXMLElement'); // new \SimpleXMLElement($Decodebody);
            $application = $ServiceResponseWPF->application;
            $responseStatus = $ServiceResponseWPF->responseStatus;

            $log = [
                'result_return' => $paymentResponse,
                'request_id' => $application->request_id,
                'response_id' => $application->response_id,
                'response_code' => $responseStatus->response_code,
                'response_message' => $responseStatus->response_message,
                'response_advise' => $responseStatus->response_advise,
                'timestamp' => $application->timestamp,
                'ptype' => $application->ptype,
                'rebill_id' => $application->rebill_id,
                'token_id' => (isset($application->token_id)) ? $application->token_id : '',
                'token_info' => (isset($application->token_info)) ? $application->token_info : '',
                'processor_response_id' => $responseStatus->processor_response_id,
                'processor_response_authcode' => $responseStatus->processor_response_authcode,
                'signature'  => $application->signature,
            ];
            $merchant = Setting::paynamics_merchant();
            $cert = $merchant['key']; //merchantkey

            $forSign = $application->merchantid . $application->request_id . $application->response_id . $responseStatus->response_code . $responseStatus->response_message . $responseStatus->
                response_advise . $application->timestamp . $application->rebill_id . $cert;

            $_sign = hash("sha512", $forSign);
           
            if ($_sign == $ServiceResponseWPF->application->signature) {

                $sales = SalesHeader::where('order_number', $application->request_id)->first();

                if (empty($sales)) {
                    $log['response_title'] = 'Sales Header not found';
                    PaynamicsLog::create($log);

                    return false;
                }

                if ($responseStatus->response_code == "GR001" || $responseStatus->response_code == "GR002") {
                    //SUCCESS TRANSACTION

                    $log['response_title'] = 'Success';
                    PaynamicsLog::create($log);

                    $sales->update([
                        'payment_status' => 'PAID',
                        'delivery_status' => 'Scheduled for Processing'
                    ]);
                    $update_payment = SalesPayment::create([
                        'sales_header_id' => $sales->id,
                        'amount' => $sales->net_amount,
                        'payment_type' => 'Paynamics-'.$application->ptype,
                        'status' => 'PAID',
                        'payment_date' => date('Y-m-d',strtotime($application->timestamp)),
                        'receipt_number' => $application->response_id,
                        'created_by' => Auth::id() ?? '1',
                        'response_body'=> $body,
                        'response_id' => $application->response_id,
                        'response_code' => $responseStatus->response_code
                    ]);
                    
                } else if ($responseStatus->response_code == "GR053") {
                    $log['response_title'] = 'Cancelled';
                    PaynamicsLog::create($log);

                    $sales->update([
                        'payment_status' => 'CANCELLED'                        
                    ]);
                } else {

                    $log['response_title'] = 'Failed';
                    PaynamicsLog::create($log);

                    $sales->update([
                        'payment_status' => 'FAILED'
                    ]);
                }
            } else {
                $log['response_title'] = 'Invalid Signature';
                PaynamicsLog::create($log);
            }
        } catch(Exception $ex) {
            PaynamicsLog::create([
                'result_return' => $ex->getMessage(),
                'response_title' => 'Try catch Error'
            ]);
        }
    }

    public function generate_payment(Request $request){
        $salesHeader = SalesHeader::where('order_number',$request->order)->first();        
        $sign = $this->generateSignature('2amqVf04H9','PH00125',$request->order,str_replace(".", "", number_format($request->amount,2,'.','')),'PHP');
        $payment = $this->ipay($salesHeader,$request->amount,$sign);
        return response()->json([
                'success' => true,
                'order_number' => $request->order,
                'customer_contact_number' => Auth::user()->contact_mobile, 
                'amount' => number_format($request->amount,2,'.',''),
                'signature' => $sign
            ]);
    }  
}
