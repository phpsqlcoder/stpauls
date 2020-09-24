<?php

namespace App\Http\Controllers\EcommerceControllers;

use App\EcommerceModel\GiftCertificate;
use App\EcommerceModel\SalesDetail;
use App\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\EcommerceModel\DeliveryStatus;
use App\EcommerceModel\SalesHeader;
use App\EcommerceModel\SalesPayment;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ListingHelper;
use App\Page;
use Auth;

class SalesController extends Controller
{
    private $searchFields = ['order_number','response_code','updated_at'];
    private $paymentsSearchFields = ['payment_date','receipt_number'];

    public function __construct()
    {
        Permission::module_init($this, 'sales_transaction');
    }

    public function index()
    {

        $customConditions = [
            [
                'field' => 'status',
                'operator' => '=',
                'value' => 'active',
                'apply_to_deleted_data' => true
            ],
        ];


        $listing = new ListingHelper('desc',10,'order_number',$customConditions);

        $sales = SalesHeader::where('id','>','0');
        if(isset($_GET['startdate']) && $_GET['startdate']<>'')
            $sales = $sales->where('created_at','>=',$_GET['startdate']);
        if(isset($_GET['enddate']) && $_GET['enddate']<>'')
            $sales = $sales->where('created_at','<=',$_GET['enddate'].' 23:59:59');
        if(isset($_GET['search']) && $_GET['search']<>'')
            $sales = $sales->where('order_number','like','%'.$_GET['search'].'%');

        $sales = $sales->orderBy('id','desc');
        $sales = $sales->paginate(20);

        $filter = $listing->get_filter($this->searchFields);
        $searchType = 'simple_search';

        return view('admin.sales.index',compact('sales','filter','searchType'));

    }

    public function sales_money_transfer()
    {

        $listing = new ListingHelper('desc',10,'order_number');

        $sales = SalesHeader::where('payment_method','<>',1);

            // SalesHeader::join('ecommerce_sales_payments','ecommerce_sales_headers.id','=','ecommerce_sales_payments.sales_header_id')
            // ->select('ecommerce_sales_payments.*','ecommerce_sales_headers.order_number','ecommerce_sales_headers.customer_name')
            // ->where('ecommerce_sales_headers.payment_method','<>',1)
            // ->where('ecommerce_sales_headers.delivery_type','<>','Cash on Delivery');

        if(isset($_GET['startdate']) && $_GET['startdate']<>'')
            $sales = $sales->where('ecommerce_sales_payments.payment_date','>=',$_GET['startdate']);

        if(isset($_GET['enddate']) && $_GET['enddate']<>'')
            $sales = $sales->where('ecommerce_sales_payments.payment_date','<=',$_GET['enddate'].' 23:59:59');

        if(isset($_GET['search']) && $_GET['search']<>'')
            $sales = $sales->where('ecommerce_sales_payments.receipt_number','like','%'.$_GET['search'].'%');

        $sales = $sales->orderBy('id','desc');
        $sales = $sales->paginate(10);

        $filter = $listing->get_filter($this->paymentsSearchFields);
        $searchType = 'simple_search';

        return view('admin.sales.money-transfer',compact('sales','filter','searchType'));

    }

    public function sales_cash_on_delivery()
    {
        $listing = new ListingHelper('desc',10,'order_number');

        $sales = SalesHeader::where('delivery_type','Cash on Delivery');

        if(isset($_GET['search']) && $_GET['search']<>'')
            $sales = $sales->where('order_number','like','%'.$_GET['search'].'%');

        $sales = $sales->orderBy('id','desc');
        $sales = $sales->paginate(10);

        $filter = $listing->get_filter($this->searchFields);
        $searchType = 'simple_search';

        return view('admin.sales.cash-on-delivery',compact('sales','filter','searchType'));
    }

    public function payment_add_store(Request $request)
    {   
        $payment = SalesPayment::create([
            'sales_header_id' => $request->sales_header_id,
            'payment_type' => $request->payment_type,
            'amount' => $request->amount,
            'status' => 'PAID',
            'payment_date' => $request->payment_dt,
            'receipt_number' => '',
            'remarks' => $request->payment_remarks,
            'created_by' => Auth::id()
        ]);

        if($payment){
            SalesHeader::find($request->sales_header_id)->update(['delivery_status' => 'Delivered']);
        }

        return back()->with('success','Payment has been added successfully.');
    }

    public function sales_same_day_delivery()
    {

        $listing = new ListingHelper('desc',10,'order_number');

        $sales = SalesHeader::where('delivery_type','Same Day Delivery');

        if(isset($_GET['search']) && $_GET['search']<>'')
            $sales = $sales->where('order_number','like','%'.$_GET['search'].'%');

        $sales = $sales->paginate(20);

        $filter = $listing->get_filter($this->searchFields);
        $searchType = 'simple_search';

        return view('admin.sales.same-day-delivery',compact('sales','filter','searchType'));

    }

    public function validate_payment(Request $request)
    {
        SalesPayment::find($request->payment_id)->update(['is_verify' => 1]);

        return back()->with('success',__('standard.sales.validate_success'));
    }

    public function store(Request $request)
    {
        //
    }

    public function destroy(Request $request)
    {
        $sale = SalesHeader::findOrFail($request->id_delete);
        $sale->update(['status' => 'CANCELLED', 'delivery_status' => 'CANCELLED']);

        return back()->with('success','Successfully deleted transaction');
    }

    public function update(Request $request)
    {

        $save = SalesPayment::create([
            'sales_header_id' => $request->id,
            'payment_type' => $request->payment_type,
            'amount' => $request->amount,
            'status'  => (isset($request->status) ? 'PAID' : 'UNPAID'),
            'payment_date'  => $request->payment_date,
            'receipt_number'  => $request->receipt_number,
            'created_by' => Auth::id()
        ]);

        $sales = SalesHeader::where('id',$request->id)->first();
        $totalPayment = SalesPayment::where('sales_header_id',$request->id)->sum('amount');
        $total = $totalPayment + $request->amount;
        if($total >= $sales->net_amount)
            $status = 'PAID';
        else $status = 'UNPAID';

        $save = SalesHeader::findOrFail($request->id)->update([
            'payment_status' => $status
        ]);

        return back()->with('success','Successfully updated payment!');
        //return $status;
    }

    public function show($id)
    {
        $sales         = SalesHeader::find($id);
        $salesPayments = SalesPayment::where('sales_header_id',$id)->get();
        $salesDetails  = SalesDetail::where('sales_header_id',$id)->get();
        
        $totalPayment  = SalesPayment::where('sales_header_id',$id)->sum('amount');
        $totalNet      = $sales->sum('net_amount');

        if($totalNet <= $totalPayment)
            $status = 'PAID';
        else 
            $status = 'UNPAID';

        return view('admin.sales.view',compact('sales','salesPayments','salesDetails','status'));
    }

    public function quick_update(Request $request)
    {
        $update = SalesHeader::findOrFail($request->pages)->update([
            'delivery_status' => $request->status
        ]);

        $order = SalesHeader::findOrFail($request->pages);
        //dd($order);
        $this->sms_update_order_status($order->customer_contact_number,$order);

        return back()->with('success','Successfully updated delivery status!');

    }

    public function delivery_status(Request $request)
    {
        //dd($request);
        $update = SalesHeader::whereId($request->del_id)->update([
            'delivery_status' => $request->delivery_status
        ]);

        $update_delivery_table = DeliveryStatus::create([
            'order_id' => $request->del_id,
            'user_id' => Auth::id(),
            'status' => $request->delivery_status,
            'remarks' => $request->del_remarks
        ]);

        $order = SalesHeader::findOrFail($request->del_id);

        //$this->sms_update_order_status($order->customer_contact_number,$order);

        return back()->with('success','Successfully updated delivery status!');

    }

    public function sms_update_order_status($number,$order){

        $message = "Your order #".$order->order_number." is now on ".strtoupper($order->delivery_status)." status -LydiasLechon";
        $apicode = "TR-JUNDR725076_39D3A";
        $url = 'https://www.itexmo.com/php_api/api.php';
        $itexmo = array('1' => $number, '2' => $message, '3' => $apicode);
        $param = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($itexmo),
            ),
        );
        $context  = stream_context_create($param);
       // return;
        return file_get_contents($url, false, $context);
    }

    public function view_payment($id)
    {
        $salesPayments = SalesPayment::where('sales_header_id',$id)->get();
        $totalPayment = SalesPayment::where('sales_header_id',$id)->sum('amount');
        $totalNet = SalesHeader::where('id',$id)->sum('net_amount');
        $remainingPayment = $totalNet - $totalPayment;

        return view('admin.sales.payment',compact('salesPayments','totalPayment','totalNet','remainingPayment'));
    }

    public function cancel_product(Request $request)
    {
        return $request;
    }

    public function display_payments(Request $request){
        $input = $request->all();

        $payments = SalesPayment::where('sales_header_id',$request->id)->get();

        return view('admin.sales.added-payments-result',compact('payments'));
    }

    public function display_delivery(Request $request){

        $input = $request->all();

        $delivery = DeliveryStatus::where('order_id',$request->id)->get();

        return view('admin.sales.delivery_history',compact('delivery'));
    }

    public function update_delivery_fee(Request $request)
    {
        $sales = SalesHeader::find($request->salesid);

        $sales->update([
            'delivery_fee_amount' => $request->delivery_fee,
            'gross_amount' => ($sales->gross_amount+$request->delivery_fee),
            'net_amount' => ($sales->net_amount+$request->delivery_fee),
            'user_id' => Auth::id()
        ]);

        return back()->with('success','Delivery has been added updated.');
    }

}
