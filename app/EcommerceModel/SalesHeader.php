<?php

namespace App\EcommerceModel;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesHeader extends Model
{
    use SoftDeletes;

    protected $table = 'ecommerce_sales_headers';
    protected $fillable = [
        'user_id', 'order_number', 'order_source', 'response_code', 'customer_name', 'customer_contact_number', 'customer_address', 'customer_delivery_adress', 'delivery_tracking_number', 'delivery_fee_amount', 'delivery_courier', 'delivery_type',
        'gross_amount', 'tax_amount', 'net_amount', 'discount_amount', 'payment_status',
        'delivery_status', 'status','other_instruction','customer_id','payment_method','branch','pickup_date','pickup_time','payment_option',
        'service_fee','is_approve','remarks','is_other','email','discount_percentage','sdd_booking_type','courier_name','rider_name','rider_contact_no','rider_plate_no','rider_link_tracker'
    ];

    public $timestamp = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->belongsTo('\App\EcommerceModel\SalesPayment','id', 'sales_header_id')->withDefault([
            'payment_type' => '',
            'id' => '0',
        ]);
    }

    public function items(){
        return $this->hasMany('App\EcommerceModel\SalesDetail','sales_header_id');
    }

    public function deliveries(){
        return $this->hasMany('App\EcommerceModel\DeliveryStatus','order_id');
    }

    public function customer_details(){
        return $this->belongsTo(Customer::class, 'customer_id','customer_id');
    }

    public function customer_main_details()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public static function payment_status($order_num){
        $data = SalesHeader::where('order_number',$order_num)->first();
        return $data->payment_status;
        
    }

    public function salesheader_payments()
    {
        return $this->belongsTo('\App\EcommerceModel\SalesPayment','id','sales_header_id');
    }


    public static function pending_cod()
    {
        $qry = SalesHeader::where('payment_method',0)->where('is_approve',NULL)->where('status','active')->count();

        return $qry;
    }

    public static function pending_credit_payment()
    {
        $qry = SalesHeader::where('status','active')->where('payment_method',1)->whereIn('delivery_status',['WAITING FOR VALIDATION','Waiting for Payment'])->count();

        return $qry;
    }

    public static function shippingfee_validation()
    {
        $sales = SalesHeader::where('status','<>','CANCELLED')->where('payment_status','UNPAID')->where('delivery_type','!=','Store Pick Up')->where('delivery_fee_amount',0)->count();

        return $sales;
    }

    public static function payment_type($orderid)
    {
        $sales = SalesHeader::find($orderid);

        if($sales->payment_method == 0){
            $type = 'Cash';
        } elseif($sales->payment_method == 1) {
            $type = 'Card Payment';
        } else {
            $type = $sales->payment_option;
        }

        return $type;
    }


    // public static function discounted_amount($salesid)
    // {
    //     $sales = SalesHeader::find($salesid);

    //     $subtotal = 0;
    //     foreach ($sales->items as $item) {
    //         $subtotal += $item->price*$item->qty;
    //     }

    //     $amount = $subtotal+$sales->delivery_fee_amount+$sales->service_fee;
        
    //     return number_format($sales->net_amount-$amount,2);
    // }













    












    public static function balance($id){
        $amount = SalesHeader::whereId($id)->sum('net_amount');        
        $paid = (float) SalesPayment::where('sales_header_id',$id)->whereStatus('PAID')->sum('amount');
        return ($amount - $paid);
    }

    public static function paid($id){
        $paid = SalesPayment::where('sales_header_id',$id)->whereStatus('PAID')->sum('amount');
        return $paid;
    }
    
    // public function getPaymentstatusAttribute(){
    //     $paid = SalesPayment::where('sales_header_id',$this->id)->whereStatus('PAID')->sum('amount');
  
    //     if($paid >= $this->net_amount){
    //         $tag_as_paid = SalesHeader::whereId($this->id)->update(['payment_status' => 'PAID']);
    //         if($this->delivery_status == 'Waiting for Payment'){
    //             $update_delivery_status = SalesHeader::whereId($this->id)->update(['delivery_status' => 'Processing Stock']);
    //         }
    //         return 'PAID';
    //     }else{
    //         return 'UNPAID';
    //     }
       
    // }

    

    // public static function status(){
    //     $data = SalesHeader::where('status','PAID')->first();
    //     if(!empty($data)){
    //         return $data;
    //     } else {
    //         return NULL;
    //     }

    // }

    // public static function first_payment($id){
    //     $data = \App\EcommerceModel\Sales::where('sales_header_id',$id)->get();
    //     return $data;
    // }

    // public function payment()
    // {
    //     return $this->belongsTo('\App\EcommerceModel\SalesPayment','sales_header_id');
    // }
}
