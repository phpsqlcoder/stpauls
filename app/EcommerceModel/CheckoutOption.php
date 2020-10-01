<?php

namespace App\EcommerceModel;

use Illuminate\Database\Eloquent\Model;

class CheckoutOption extends Model
{
    public $table = "ecommerce_checkout_options";
    protected $fillable = ['delivery_rate','service_fee','minimum_purchase','maximum_purchase','allowed_days','allowed_time_from','allowed_time_to','reminder','is_active','user_id','within_metro_manila','outside_metro_manila'];

    public static function check_availability($id)
    {
    	$qry     = CheckoutOption::find($id);
    	$currDay = date('D',strtotime(today()));

    	$set_days = explode('|',$qry->allowed_days);
    	$arr_setDays = [];
            foreach($set_days as $key => $day){
                array_push($arr_setDays,$day);
        }


        if(in_array($currDay,$arr_setDays)){
        	$now  = date('H:i',strtotime(now()));
        	$from = $qry->allowed_time_from;
        	$to   = $qry->allowed_time_to;

        	if($now >= $from && $now <= $to){
        		return 1;
        	} else {
        		return 0;
        	}
        } else {
        	return 0;
        }
    }
}
