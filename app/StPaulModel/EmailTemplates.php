<?php

namespace App\StPaulModel;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class EmailTemplates extends Model
{
	use SoftDeletes;
	
    public $table = 'email_templates';

    protected $fillable = [ 'name','transactionstatus_id','content','status','user_id'];
    protected $timestamp = true;

    public function transactionstatus()
    {
    	return $this->belongsTo('\App\StPaulModel\TransactionStatus','transactionstatus_id');
    }
}
