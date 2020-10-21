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

    public static function email_counter()
    {
    	$counter = 
            Transaction::whereNotIn('name',function($query){
                $query->select('name')->from('transaction_status');
            })->where('status','ACTIVE')->count();

           return $counter;
    }

}
