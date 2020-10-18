<?php

namespace App\EcommerceModel;

use Illuminate\Database\Eloquent\Model;

class PaymentOption extends Model
{
    public $table = 'payment_options';
    protected $fillable = ['payment_id','name','type','account_no','branch','qrcode','is_default','is_active','user_id','recipient','account_name'];
    public $timestamp = true;

}
