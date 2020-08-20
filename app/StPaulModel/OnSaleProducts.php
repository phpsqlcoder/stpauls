<?php

namespace App\StPaulModel;

use Illuminate\Database\Eloquent\Model;

class OnSaleProducts extends Model
{
    public $table = 'onsale_products';
    protected $fillable = ['promo_id', 'product_id', 'cost', 'user_id'];
 	public $timestamps = true;

 	public function details()
 	{
 		return $this->belongsTo('\App\EcommerceModel\Product','product_id','id');
 	}

 	public function promo_details()
 	{
 		return $this->belongsTo('\App\StPaulModel\Promo','promo_id')->withTrashed();
 	}
}
