<?php

namespace App\StPaulModel;

use Illuminate\Database\Eloquent\Model;

class LoyalCustomer extends Model
{
    public $table = 'loyal_customers';
    protected $fillable = [
    	'customer_id', 'discount_id', 'user_id', 'status', 'customer_name'
    ];

    public $timestamp = true;

    public function details()
    {
    	return $this->belongsTo('\App\EcommerceModel\Customer','customer_id','customer_id');
    }

    public function discount_details()
    {
    	return $this->belongsTo('\App\StPaulModel\Discount','discount_id');
    }

    public static function total_purchase($customerId)
    {
        $count = \App\EcommerceModel\SalesHeader::where('customer_id',$customerId)->count();

        return $count;
    }
}
