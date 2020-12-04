<?php

namespace App\EcommerceModel;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Wishlist extends Model
{
    public $table = 'wishlist';
    public $fillable = ['customer_id','product_id','product_name'];
    protected $timestamp = true;

    public function customer_details()
    {
    	return $this->belongsTo('\App\User','customer_id');
    }

    public static function product_wishlist($id)
    {
    	$count = Wishlist::where('customer_id',Auth::id())->where('product_id',$id)->count();

    	return $count;
    }
}
