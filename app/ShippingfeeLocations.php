<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\ShippingfeeWeight;
use App\Shippingfee;

use App\Countries;
use App\Cities;

use DB;

class ShippingfeeLocations extends Model
{
    protected $table = 'shippingfee_locations';
    protected $fillable = ['name', 'shippingfee_id', 'user_id','province_id'];

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

    public static function provinces($feeId)
    {
        $fee = Shippingfee::find($feeId);

        if(in_array($fee->area,['luzon','visayas','mindanao'])){
            if($fee->area == 'luzon'){
                $provinces = Provinces::where('island',$fee->area)->whereNotIn('id',[49,65,24,42])->orderBy('province','asc')->get();
            } else {
                $provinces = Provinces::where('island',$fee->area)->orderBy('province','asc')->get();
            }
        }

        return $provinces;
    }

    public static function cities($feeId)
    {
        $fee = Shippingfee::find($feeId);
        $cities = Cities::where('province',$fee->province)->orderBy('city','asc')->get();
            
        return $cities;
    }

    public static function checkIfSelected($sId,$provId)
    {
        // selected province that is in the selected shipping rate
        $count = ShippingfeeLocations::where('shippingfee_id',$sId)->where('province_id',$provId)->count();

        if($count > 0){
            return 'selected';
        } else {
            // selected province that is not in the selected shipping rate
            $row = ShippingfeeLocations::where('province_id',$provId)->count();

            if($row > 0){
                return 'disabled';
            } else {
                return '';
            }
        }
    }

    public static function checkIfCitySelected($sId,$city,$provId)
    {
        // selected city that is in the selected shipping rate
        $count = ShippingfeeLocations::where('shippingfee_id',$sId)->where('name',$city)->where('province_id',$provId)->count();

        if($count > 0){
            return 'selected';
        } else {
            // selected city that is not in the selected shipping rate
            $row = ShippingfeeLocations::where('name',$city)->where('province_id',$provId)->count();

            if($row > 0){
                return 'disabled';
            } else {
                return '';
            }
        }
    }
}
