<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\ShippingfeeWeight;
use App\Shippingfee;

use App\Countries;
use App\Cities;

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

    public static function shipping_fee($city,$country,$weight){
        if($country == ""){
            return 0;
        } else {
            if($city == "" && $country == 259){
                return 0;
            } else {
                if($country == 259){
                    $city = Cities::find($city);
                    $location = $city->city;
                    
                } else {
                    $country  = Countries::find($country);
                    $location = $country->name; 
                }

                $sp_location = ShippingfeeLocations::where('name',$location);
                
                if($sp_location->count() > 0){
                    $data = $sp_location->first();

                    $sp        = Shippingfee::find($data->shippingfee_id);
                    $sp_weight = ShippingfeeWeight::where('shippingfee_id',$data->shippingfee_id)->where('weight','<=',$weight)->latest('id')->first();


                    if($sp->is_outside_manila == 0){ // within manila
                        if($weight > 10){
                            $rate = $sp->rate+$sp_weight->rate;
                        } else {
                            $rate = $sp->rate;
                        }
                    } else {
                        $rate = $sp->rate+$sp_weight->rate;
                    }
                    
                    return $rate;
                } else {
                    return 0;
                }
            }
            
        }
    }
}
