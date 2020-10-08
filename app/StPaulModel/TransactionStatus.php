<?php

namespace App\StPaulModel;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class TransactionStatus extends Model
{
	use SoftDeletes;
	
    public $table = 'transaction_status';

    protected $fillable = ['name','subject', 'content', 'status','user_id'];
    protected $timestamp = true;

}
