<?php

namespace App\StPaulModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promo extends Model
{
	use SoftDeletes;

    protected $fillable = [ 'name', 'promo_start', 'promo_end', 'discount', 'status', 'is_expire', 'user_id'];
    public $timestamps = true;

    public function products()
    {
    	return $this->hasMany('\App\StPaulModel\OnSaleProducts','promo_id');
    }
}
