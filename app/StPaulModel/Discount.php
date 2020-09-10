<?php

namespace App\StPaulModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
	use SoftDeletes;

    public $table = 'discounts';

    protected $fillable = ['name', 'discount', 'status', 'user_id'];
    public $timestamp = true;

}
