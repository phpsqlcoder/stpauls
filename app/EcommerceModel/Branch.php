<?php

namespace App\EcommerceModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;

    protected $table = 'branches';
    protected $fillable = ['name', 'url', 'area', 'province_id', 'city_id','address', 'user_id', 'status', 'isfeatured', 'email', 'other_details', 'img'];
    public $timestamps = true;

    public function province()
    {
    	return $this->belongsTo('\App\Provinces','province_id');
    }

    public function city()
    {
    	return $this->belongsTo('\App\Cities','city_id');
    }

    public function getBranchAddressAttribute()
    {
        return $this->address.", ".$this->city->city.", ".$this->province->province;
    }

    public function contacts()
    {
    	return $this->hasMany('App\EcommerceModel\BranchContactNumber','branch_id');
    }
}
