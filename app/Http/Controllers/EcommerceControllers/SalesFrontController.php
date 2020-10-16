<?php

namespace App\Http\Controllers\EcommerceControllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;

use App\EcommerceModel\SalesHeader;
use App\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\EcommerceModel\PaymentOption;
use App\EcommerceModel\SalesPayment;
use App\EcommerceModel\DeliveryStatus;
use App\EcommerceModel\SalesDetail;

class SalesFrontController extends Controller
{
    public function orders()
    {   
        $page = new Page();
        $page->name = 'Transaction History';

        $sales = SalesHeader::where('customer_id',Auth::id())->orderBy('id','desc')->paginate(10);

        return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.customer.orders', compact('sales','page'));
    }

    // public function ajax_payment_types($id)
    // {
    //     $sales = SalesHeader::find($id);

    //     $types = PaymentOption::where('payment_id',$sales->payment_method)->where('is_active',1)->orderBy('name','asc')->get();

    //     return response($types);
    // }

    public function pay_order(Request $request)
    {
        $payment = SalesPayment::create([
            'sales_header_id' => $request->header_id,
            'receipt_number' => '',
            'payment_type' => $request->payment_type,
            'payment_date' => today(),
            'amount' => $request->amount,
            'is_verify' => 0,
            'status' => 'PAID',
            'attachment' => $request->attachment->getClientOriginalName(),
            'created_by' => Auth::id()
        ]);

        SalesHeader::find($request->header_id)->update(['delivery_status' => 'WAITING FOR VALIDATION']);
            
            
        if(isset($request->attachment)){

            $file = $request->attachment;

            Storage::makeDirectory('/public/payments/'.$payment->id);
            Storage::putFileAs('/public/payments/'.$payment->id, $file, $file->getClientOriginalName());
        }

        return back()->with('success',' Payment has been submitted.');
    }


    public function cancel_order(Request $request)
    {
        SalesHeader::find($request->orderid)->update(['status' => 'CANCELLED', 'delivery_status' => 'CANCELLED']);

        return back()->with('success','Successfully cancelled your order');
    }  

    public function display_delivery_history(Request $request)
    {
        $deliveries = DeliveryStatus::where('order_id',$request->orderid)->get();

        return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.customer.order-deliveries',compact('deliveries'));
    }

    public function display_items(Request $request)
    {
        $sales = SalesHeader::find($request->orderid);
        $items = SalesDetail::where('sales_header_id',$sales->id)->get();

        return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.customer.order-items',compact('items','sales'));
    }

    public function order_info($id)
    {
        $page = new Page();
        $page->name = 'Order Summary';

        $sales = SalesHeader::find($id);

        return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.customer.order-summary', compact('sales','page'));
    }

    public function globalpay_success($orderno)
    {
        $page = new Page();
        $page->name = 'Success Payment';

        $sales = SalesHeader::where('order_number',$orderno)->first();

        return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.customer.globalpay-success',compact('page','sales'));
    }
  
}
