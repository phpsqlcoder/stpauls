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
        $qry = ShippingfeeLocations::where('name',$name);
        $data = $qry->first();

        if($qry > 0){
            $shippingfee = Shippingfee::find($data->shippingfee_id);

            return $shippingfee->rate;
        } else {
            return 0;
        }
        
    }

    public static function weightrate($name,$weight){
        $qry = ShippingfeeLocations::where('name',$name);
        $data = $qry->first();

        if($qry > 0){
            $shippingfee = ShippingfeeWeight::where('shippingfee_id',$data->shippingfee_id)->where('weight','<=',$weight)->latest('id')->first();
            return $shippingfee->rate;
        } else {
            return 0;
        }
    } 
}
