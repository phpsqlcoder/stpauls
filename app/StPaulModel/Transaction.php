<?php

namespace App\StPaulModel;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
	use SoftDeletes;
	
    public $table = 'transactions';

    protected $fillable = ['name','status','type','user_id'];
    protected $timestamp = true;

}
