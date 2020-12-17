<?php

namespace App\EcommerceModel;

use Illuminate\Database\Eloquent\Model;

class BranchContactNumber extends Model
{

    protected $table = 'branch_contact_nos';
    protected $fillable = ['branch_id', 'contact_name', 'contact_no','user_id'];
    public $timestamps = true;
}
