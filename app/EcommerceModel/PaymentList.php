<?php

namespace App\EcommerceModel;

use Illuminate\Database\Eloquent\Model;

class PaymentList extends Model
{
    public $table = "payment_list";
    protected $fillable = ['is_active','user_id'];
    public function options()
    {
    	return $this->hasMany('\App\EcommerceModel\PaymentOption','payment_id');
    }

    public static function paymentOptionstatus($id)
    {
    	$qry = PaymentList::find($id);

    	return $qry->is_active;
    }

    public function paymentList()
    {
        return $this->hasMany('App\EcommerceModel\PaymentOption','payment_id')->where('is_active',1);
    }
}
