<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\ShippingfeeWeight;
use App\Shippingfee;

class ShippingfeeLocations extends Model
{
    protected $table = 'shippingfee_locations';
    protected $fillable = ['name', 'shippingfee_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

	public function shippingfee()
    {
        return $this->belongsTo('App\Shippingfee','shippingfee_id');
    }   

    public static function locationrate($name){
        $qry = ShippingfeeLocations::where('name',$name)->first();

        $shippingfee = Shippingfee::find($qry->shippingfee_id);

        return $shippingfee->rate;
    }

    public static function weightrate($name,$weight){
        $qry = ShippingfeeLocations::where('name',$name)->first();

        $shippingfee = ShippingfeeWeight::where('shippingfee_id',$qry->shippingfee_id)->where('weight','<=',$weight)->latest()->first();

        
        return $shippingfee->rate;
    } 
}
