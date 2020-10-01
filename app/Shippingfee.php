<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shippingfee extends Model
{
    protected $table = 'shippingfees';
    protected $fillable = ['name', 'is_international', 'is_outside_manila', 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function weights()
    {
        return $this->hasMany('\App\ShippingfeeWeight', 'shippingfee_id');
    }

    public function locations()
    {
        return $this->hasMany('\App\ShippingfeeLocations', 'shippingfee_id');
    }
}
