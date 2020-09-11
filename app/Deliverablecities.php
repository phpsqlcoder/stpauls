<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deliverablecities extends Model
{
	use SoftDeletes;
    protected $table = 'deliverable_cities';
    protected $fillable = ['province', 'city', 'city_name', 'rate', 'is_outside', 'user_id','status'];
    

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function province_detail()
    {
    	return $this->belongsTo('\App\Provinces','province');
    }

    public function city()
    {
    	return $this->belongsTo('\App\Cities','city');
    }

    public static function check_area($city)
    {
        $qry = Deliverablecities::where('city',$city)->count();

        return $qry;
    }
}
