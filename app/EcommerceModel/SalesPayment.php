<?php

namespace App\EcommerceModel;

use Illuminate\Database\Eloquent\Model;

class SalesPayment extends Model
{

    protected $table = 'ecommerce_sales_payments';
    protected $fillable = [
        'sales_header_id','payment_type','amount','status', 'payment_date', 'receipt_number','created_by',
        'order_number','remark','trans_id','err_desc','signature','cc_name','cc_no','bank_name','country',
        'remarks','response_body','response_id','response_code','is_verify','attachment', 'user_id'
    ];

    public function sales_header()
    {
        return $this->belongsTo('\App\EcommerceModel\SalesHeader','sales_header_id');
    }

    public static function pending_money_transfer()
    {   

        $qry1 = \DB::table('ecommerce_sales_payments')
                ->leftJoin('ecommerce_sales_headers', 'ecommerce_sales_payments.sales_header_id', '=', 'ecommerce_sales_headers.id')
                ->where('ecommerce_sales_payments.is_verify',NULL)
                ->where('ecommerce_sales_payments.status','PAID')
                ->where('ecommerce_sales_headers.payment_method','>',1)->count();


        $qry = \DB::table('ecommerce_sales_headers')
                ->leftJoin('ecommerce_sales_payments', 'ecommerce_sales_headers.id', '=', 'ecommerce_sales_payments.sales_header_id')
                ->where('ecommerce_sales_headers.delivery_status','Shipping Fee Validation')->count();

        return $qry1+$qry;
    }

    public static function unvalidated_delivery_payments($type)
    {
        $qry = SalesHeader::join('ecommerce_sales_payments','ecommerce_sales_headers.id','=','ecommerce_sales_payments.sales_header_id')
            ->where('ecommerce_sales_headers.delivery_type',$type)
            ->where('ecommerce_sales_payments.is_verify',0)
            ->count();

        return $qry;
    }











    

    // public static function check_if_has_added_payments($id)
    // {
    //     $data = SalesPayment::where('sales_header_id',$id)->exists();

    //     if($data){
    //         return 1;
    //     } else {
    //         return 0;
    //     }
    // }

    // public static function remaining_balance($amount,$id)
    // {
    //     $paid_amount = SalesPayment::where('sales_header_id',$id)->sum('amount');

    //     return $amount-$paid_amount;
    // }

    // public static function check_if_has_remaining_balance($gross_amount,$id)
    // {
    //     $balance = \App\EcommerceModel\SalesPayment::remaining_balance($gross_amount,$id);
    //     if($balance == 0){
    //         return 0;
    //     } else {
    //         return 1;
    //     }
    // }
}
