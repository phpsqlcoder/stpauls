<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippingfeeWeight extends Model
{
    protected $table = 'shippingfee_weights';
    protected $fillable = ['weight', 'rate', 'shippingfee_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

	public function shippingfee()
    {
        return $this->belongsTo('App\Shippingfee','shippingfee_id');
    }    
}
