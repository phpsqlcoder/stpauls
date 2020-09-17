<?php

namespace App\Http\Controllers\EcommerceControllers;



use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\EcommerceModel\DeliveryStatus;

use App\EcommerceModel\Product;
use App\EcommerceModel\ProductCategory;

use Illuminate\Support\Facades\Validator;
use App\Helpers\ListingHelper;



use Auth;
use DB;

use App\EcommerceModel\SalesPayment;
use App\EcommerceModel\SalesHeader;
use App\EcommerceModel\SalesDetail;
use App\EcommerceModel\Customer;


class ReportsController extends Controller
{
    public function sales_list(Request $request)
    {
        
        $qry = "SELECT h.*,d.*,h.created_at as hcreated,h.id as hid,p.category_id,c.name as catname,p.brand,p.code FROM `ecommerce_sales_details` d left join ecommerce_sales_headers h on h.id=d.sales_header_id left join products p on p.id=d.product_id left join product_categories c on c.id=p.category_id where h.id>0 and h.status<>'CANCELLED' and h.delivery_status<>'CANCELLED'";

        if(isset($_GET['brand']) && $_GET['brand']<>''){
            $qry.= " and p.brand='".$_GET['brand']."'";
        }
        if(isset($_GET['customer']) && $_GET['customer']<>''){
            $qry.= " and h.customer_name='".$_GET['customer']."'";
        }
        if(isset($_GET['product']) && $_GET['product']<>''){
            $qry.= " and d.product_id='".$_GET['product']."'";
        }
        if(isset($_GET['category']) && $_GET['category']<>''){
            $qry.= " and p.category_id='".$_GET['category']."'";
        }

        if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
            $qry.= " and h.created_at >='".$_GET['startdate']." 00:00:00.000' and h.created_at <='".$_GET['enddate']." 23:59:59.999'";
        }

        $rs = DB::select($qry);

        return view('admin.reports.sales.list',compact('rs'));
    }

    public function unpaid_list(Request $request)
    {
        $qry = "SELECT h.*,d.*,h.created_at as hcreated,h.id as hid,p.category_id,c.name as catname,p.brand,p.code FROM `ecommerce_sales_details` d left join ecommerce_sales_headers h on h.id=d.sales_header_id left join products p on p.id=d.product_id left join product_categories c on c.id=p.category_id where h.id > 0 and h.payment_status = 'UNPAID'";

        if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
            $qry.= " and h.created_at >='".$_GET['startdate']." 00:00:00.000' and h.created_at <='".$_GET['enddate']." 23:59:59.999'";
        }

        $rs = DB::select($qry);

        return view('admin.reports.sales.unpaid',compact('rs'));

    }

    public function sales_payments(Request $request)
    {
        $qry = "SELECT p.payment_type, p.amount as amount_paid, p.payment_date, p.receipt_number, p.created_at, h.order_number,h.customer_name, h.delivery_status FROM `ecommerce_sales_payments` p LEFT JOIN ecommerce_sales_headers h ON h.id = p.sales_header_id where p.id > 0"; 

        if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
            $qry.= " and p.paymment_date >='".$_GET['startdate']."' and p.paymment_date <='".$_GET['enddate']."' ";
        } else {
            $qry.= " and MONTH(p.payment_date) = MONTH(CURRENT_DATE()) AND YEAR(p.payment_date) = YEAR(CURRENT_DATE()) ";
        }

        $rs = DB::select($qry);

        return view('admin.reports.sales.payment',compact('rs'));
    }

    public function customer_list(Request $request)
    {
        $rs = Customer::orderBy('firstname')->get();        

        return view('admin.reports.customer.list',compact('rs'));

    }

    public function product_list(Request $request)
    {
        $rs = Product::all();        

        return view('admin.reports.product.list',compact('rs'));
    }

    public function best_selling(Request $request)
    {
        $rs = SalesDetail::select('product_id', DB::raw('count(product_id) numberOfSales'))->groupBy('product_id')->get();

        return view('admin.reports.product.best_selling',compact('rs'));
    }

    public function inventory_list(Request $request)
    {
        $rs = Product::all();
        
        return view('admin.reports.inventory.list',compact('rs'));
    }

    public function inventory_reorder_point(Request $request)
    {
        $rs = Product::where('reorder_point','>',0)->get();
        
        return view('admin.reports.inventory.inventory_reorder_point',compact('rs'));
    }



















    

    


    public function sales(Request $request)
    {
        $rs = '';
        if(isset($_GET['act'])){
            $rs = DB::select("SELECT h.*,d.*,h.created_at as hcreated           
                    FROM `ecommerce_sales_details` d 
                    left join ecommerce_sales_headers h on h.id=d.sales_header_id 
                    where h.payment_status='PAID'
                     ");
        }

        return view('admin.reports.sales',compact('rs'));

    }


    public function delivery_report($id)
    {
        $rs = SalesHeader::find($id);
        
        return view('admin.reports.delivery_report',compact('rs'));

    }

    public function delivery_status(Request $request)
    {
        $rs = '';
       // if(isset($_GET['act'])){

            $rs = DB::select("SELECT h.*,d.*,h.created_at as hcreated           
                    FROM `ecommerce_sales_details` d 
                    left join ecommerce_sales_headers h on h.id=d.sales_header_id 
                    where h.payment_status='PAID'
                     ");

        //}

        return view('admin.reports.delivery_status',compact('rs'));

    }


    public function sales_per_customer(Request $request)
    {   
        $qry = "SELECT h.*,d.*,h.created_at as hcreated           
                FROM `ecommerce_sales_details` d 
                left join ecommerce_sales_headers h on h.id=d.sales_header_id 
                where h.payment_status='PAID'";

        if(isset($_GET['agent']) && strlen($_GET['agent'])>=1){
            $qry.= " and h.customer_name='".$_GET['agent']."'";
        }

        if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
            $qry.= " and h.created_at >='".$_GET['startdate']." 00:00:00.000' and h.created_at <='".$_GET['enddate']." 23:59:59.999'";
        }

        $rs = DB::select($qry);

        $customers = SalesHeader::distinct()->get(['customer_name']);

        return view('admin.reports.sales_per_customer',compact('rs','customers'));
    }

    public function stock_card(Product $id)
    {
     
        $rs = '';   

        $sales = \DB::table('ecommerce_sales_details')
                ->leftJoin('ecommerce_sales_headers', 'ecommerce_sales_details.sales_header_id', '=', 'ecommerce_sales_headers.id')
                ->where('ecommerce_sales_details.product_id','=',$id->id)
                ->where('ecommerce_sales_headers.payment_status','=','PAID')
                ->where('ecommerce_sales_headers.status','=','active')
                ->select('ecommerce_sales_headers.created_at as created', 'ecommerce_sales_details.qty as qty','ecommerce_sales_headers.order_number as ref',
                    \DB::raw('"sales" as type'))
                ->get();

        $inventory = \DB::table('inventory_receiver_details')
                ->leftJoin('inventory_receiver_header', 'inventory_receiver_details.header_id', '=', 'inventory_receiver_header.id')
                ->where('inventory_receiver_details.product_id','=',$id->id)
                ->where('inventory_receiver_header.status','=','POSTED')
                ->select('inventory_receiver_header.posted_at as created', 'inventory_receiver_details.inventory as qty','inventory_receiver_header.id as ref',
                    \DB::raw('"inventory" as type'))
                ->get();

        $rs = $sales->merge($inventory)->sortBy('created');
       
        return view('admin.reports.product.stockcard',compact('rs','id'));

    }

  

}
