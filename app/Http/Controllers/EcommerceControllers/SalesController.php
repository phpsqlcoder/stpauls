<?php

namespace App\Http\Controllers\EcommerceControllers;

use App\EcommerceModel\GiftCertificate;
use App\EcommerceModel\SalesDetail;
use App\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ListingHelper;
use Auth;

use App\EcommerceModel\DeliveryStatus;
use App\EcommerceModel\SalesPayment;
use App\EcommerceModel\SalesHeader;
use App\EcommerceModel\Customer;
use App\User;
use App\Page;
use App\Setting;

use App\StPaulModel\TransactionStatus;

class SalesController extends Controller
{
    private $searchFields = ['order_number','customer_name','updated_at'];

    public function __construct()
    {
        Permission::module_init($this, 'sales_transaction');
    }

    public function invoice($sales)
    {   
        $sales = SalesHeader::find($sales);
        $settings = Setting::find(1);

        return view('admin.sales.invoice',compact('sales','settings'));
    }

    // public function index()
    // {

    //     $customConditions = [
    //         [
    //             'field' => 'status',
    //             'operator' => '=',
    //             'value' => 'active',
    //             'apply_to_deleted_data' => true
    //         ],
    //     ];

    //     $listing = new ListingHelper('desc',10,'order_number',$customConditions);

    //     $sales = SalesHeader::where('id','>','0');
    //     if(isset($_GET['startdate']) && $_GET['startdate']<>'')
    //         $sales = $sales->where('created_at','>=',$_GET['startdate']);
    //     if(isset($_GET['enddate']) && $_GET['enddate']<>'')
    //         $sales = $sales->where('created_at','<=',$_GET['enddate'].' 23:59:59');
    //     if(isset($_GET['search']) && $_GET['search']<>'')
    //         $sales = $sales->where('order_number','like','%'.$_GET['search'].'%');

    //     $sales = $sales->orderBy('id','desc');
    //     $sales = $sales->paginate(20);

    //     $filter = $listing->get_filter($this->searchFields);
    //     $searchType = 'simple_search';

    //     return view('admin.sales.index',compact('sales','filter','searchType'));

    // }

    public function sales_money_transfer()
    {
        $customConditions = [
            [
                'field' => 'payment_method',
                'operator' => '>',
                'value' => 1,
                'apply_to_deleted_data' => false
            ]
        ];

        $listing = new ListingHelper('desc', 20, 'created_at', $customConditions);

        $sales = $listing->simple_search(SalesHeader::class, $this->searchFields);

        // Simple search init data
        $filter = $listing->get_filter($this->searchFields);

        $searchType = 'simple_search';

        return view('admin.sales.money-transfer',compact('sales','filter','searchType'));

    }

    public function sales_cash_on_delivery()
    {
        $customConditions = [
            [
                'field' => 'payment_method',
                'operator' => '=',
                'value' => 0,
                'apply_to_deleted_data' => false
            ],
            [
                'field' => 'delivery_type',
                'operator' => '=',
                'value' => 'Cash on Delivery',
                'apply_to_deleted_data' => false
            ]
        ];

        $listing = new ListingHelper('desc', 20, 'created_at', $customConditions);

        $sales = $listing->simple_search(SalesHeader::class, $this->searchFields);

        // Simple search init data
        $filter = $listing->get_filter($this->searchFields);

        $searchType = 'simple_search';

        return view('admin.sales.cash-on-delivery',compact('sales','filter','searchType'));
    }

    public function sales_card_payment()
    {
        $customConditions = [
            [
                'field' => 'payment_method',
                'operator' => '=',
                'value' => 1,
                'apply_to_deleted_data' => false
            ]
        ];

        $listing = new ListingHelper('desc', 20, 'created_at', $customConditions);

        $sales = $listing->simple_search(SalesHeader::class, $this->searchFields);

        // Simple search init data
        $filter = $listing->get_filter($this->searchFields);

        $searchType = 'simple_search';

        return view('admin.sales.card-payment',compact('sales','filter','searchType'));
    }

    public function display_payment_details($id){

        $payment = SalesPayment::where('sales_header_id',$id)->first();

        return view('admin.sales.payment-details',compact('payment'));
    }

    public function order_response(Request $request)
    {   
        $sales = SalesHeader::find($request->orderid);
        $user  = User::find($sales->customer_id);

        $netamount = ($sales->net_amount+$request->shippingfee);

        if($sales->status == 'CANCELLED'){
            return back()->with('error', 'Order was already cancelled by the customer.');
        } else {

            if($request->status == 'APPROVE'){

                $sales->update([
                    'delivery_fee_amount' => ($sales->is_other == 1) ? $request->shippingfee : $sales->delivery_fee_amount,
                    'net_amount' => ($sales->is_other == 1) ? $netamount : $sales->net_amount,
                    'gross_amount' => ($sales->is_other == 1) ? $netamount : $sales->gross_amount,
                    'delivery_status' => 'Scheduled for Processing',
                    'is_approve' => 1,
                    'remarks' => $request->remarks,
                    'user_id' => Auth::id()
                ]);

                $this->send_email_notification($sales,'Approve Order');

                $admin = User::find(Auth::id());
                $admin->send_order_approved_email($sales,now());

                return back()->with('success', 'Order has been approved.');

            } else {

                $sales->update([
                    'status' => 'CANCELLED',
                    'delivery_status' => 'Cancelled',
                    'remarks' => $request->remarks,
                    'is_approve' => 0,
                    'user_id' => Auth::id()
                ]);

                $this->send_email_notification($sales,'Reject Order');
                return back()->with('success', 'Order has been rejected.');

            }
        }   
    }

    public function validate_payment(Request $request)
    {
        $payment  = SalesPayment::find($request->payment_id);
        $sales    = SalesHeader::find($payment->sales_header_id);
        $user     = User::find($sales->customer_id);

        if($request->status == 'APPROVE'){

            $payment->update([
                'is_verify' => 1,
                'user_id' => Auth::id()
            ]);
            
            $sales->update([
                'payment_status' => 'PAID',
                'delivery_status' => 'Scheduled for Processing',
                'user_id' => Auth::id(),
                'is_approve' => 1
            ]);

            $this->send_email_notification($sales,'Approve Payment');

            $admin = User::find(Auth::id());
            $admin->send_order_approved_email($sales);

            return back()->with('success',__('standard.sales.approve_success'));

        } else {
            $payment->update([
                'is_verify' => 0, 
                'status' => 'UNPAID',
                'remarks' => $request->remarks,
                'user_id' => Auth::id()
            ]);

            $sales->update([
                'status' => 'CANCELLED',
                'delivery_status' => 'Cancelled',
                'user_id' => Auth::id()
            ]);

            $this->send_email_notification($sales,'Reject Payment');
            return back()->with('success',__('standard.sales.reject_success'));
        }
        
    }

    public function payment_add_store(Request $request)
    {   
        $payment = SalesPayment::create([
            'sales_header_id' => $request->sales_header_id,
            'payment_type' => $request->pamenty_mode,
            'amount' => $request->amount,
            'status' => 'PAID',
            'payment_date' => $request->payment_dt,
            'receipt_number' => '',
            'remarks' => $request->payment_remarks,
            'created_by' => Auth::id(),
            'user_id' => Auth::id(),
            'is_verify' => 1
        ]);

        if($payment){
            $sales = SalesHeader::find($request->sales_header_id);
            $sales->update([
                'delivery_status' => 'Delivered',
                'payment_status' => 'PAID',
                'user_id' => Auth::id()
            ]);


            if(DeliveryStatus::where('order_id',$request->sales_header_id)->where('status','Delivered')->exists()){

            } else {
                DeliveryStatus::create([
                    'order_id' => $request->sales_header_id,
                    'user_id' => Auth::id(),
                    'status' => 'Delivered',
                    'remarks' => $request->payment_remarks
                ]); 
            }
            $this->send_email_notification($sales,'Add Payment');
        }

        return back()->with('success','Payment has been added.');
    }

    public function delivery_status(Request $request)
    {
        $qry = SalesHeader::find($request->del_id);

        $qry->update([
            'user_id' => Auth::id(),
            'delivery_status' => $request->delivery_status
        ]);
        
        $update_delivery_table = DeliveryStatus::create([
            'order_id' => $request->del_id,
            'user_id' => Auth::id(),
            'status' => $request->delivery_status,
            'remarks' => $request->del_remarks
        ]);

        $this->send_email_notification($qry,$request->delivery_status);

        return back()->with('success','Successfully updated delivery status!');

    }

    public function add_shippingfee(Request $request)
    {
        $sales = SalesHeader::findOrFail($request->orderid);
        $netamount = ($sales->net_amount+$request->shippingfee);

        $sales->update([
            'delivery_status' => ($sales->delivery_type == 'Cash on Delivery') ? 'Scheduled for Processing' : 'Waiting for Payment',
            'is_approve' => ($sales->delivery_type == 'Cash on Delivery') ? 1 : 0,
            'delivery_fee_amount' => $request->shippingfee,
            'net_amount' => number_format($netamount,2,'.',''),
            'gross_amount' => number_format($netamount,2,'.',''),
            'is_approve' => 1,
            'user_id' => Auth::id()
        ]);

        $this->send_email_notification($sales,'Add Shipping Fee');
        $admin = User::find(Auth::id());
        $admin->send_order_approved_email($sales);

        return back()->with('success', 'Shipping fee has been added.');
    }


    public function send_email_notification($sales,$transactionstatus)
    {
        $qry = TransactionStatus::where('name',$transactionstatus)->where('status','ACTIVE');
        $count = $qry->count();

        if($qry->count() > 0){
            $template = $qry->first();

            $user = User::find($sales->customer_id);
            $user->send_email_notification($sales,$template);
        }
    }

    public function cancel_order(Request $request)
    {
        SalesHeader::find($request->orderid)->update([
            'status' => 'CANCELLED',
            'delivery_status' => 'Cancelled'
        ]);

        return back()->with('success', 'Order has been cancelled.');
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

    // public function view_payment($id)
    // {
    //     $salesPayments = SalesPayment::where('sales_header_id',$id)->get();
    //     $totalPayment = SalesPayment::where('sales_header_id',$id)->sum('amount');
    //     $totalNet = SalesHeader::where('id',$id)->sum('net_amount');
    //     $remainingPayment = $totalNet - $totalPayment;

    //     return view('admin.sales.payment',compact('salesPayments','totalPayment','totalNet','remainingPayment'));
    // }

    // public function display_payments(Request $request){
    //     $input = $request->all();

    //     $payments = SalesPayment::where('sales_header_id',$request->id)->get();

    //     return view('admin.sales.added-payments-result',compact('payments'));
    // }

    public function display_delivery(Request $request){

        $input = $request->all();

        $delivery = DeliveryStatus::where('order_id',$request->id)->get();

        return view('admin.sales.delivery_history',compact('delivery'));
    }

}
