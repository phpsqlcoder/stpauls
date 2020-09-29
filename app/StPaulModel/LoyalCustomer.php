<?php

namespace App\StPaulModel;

use Illuminate\Database\Eloquent\Model;

class LoyalCustomer extends Model
{
    public $table = 'loyal_customers';
    protected $fillable = [
    	'customer_id', 'discount_id', 'user_id', 'total_purchase', 'status'
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
}
