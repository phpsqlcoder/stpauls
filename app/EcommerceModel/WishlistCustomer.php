<?php

namespace App\EcommerceModel;

use Illuminate\Database\Eloquent\Model;
use Auth;

class WishlistCustomer extends Model
{
    public $table = 'wishlist_customer';
    public $fillable = ['product_id','customer_id'];
    protected $timestamp = true;

    public function customer_details()
    {
    	return $this->belongsTo('\App\User','customer_id');
    }

    public static function product_wishlist($id)
    {
    	$count = WishlistCustomer::where('customer_id',Auth::id())->where('product_id',$id)->count();

    	return $count;
    }
}
