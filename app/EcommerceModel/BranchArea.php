<?php

namespace App\EcommerceModel;

use Illuminate\Database\Eloquent\Model;

class BranchArea extends Model
{

    protected $table = 'branch_areas';
    protected $fillable = ['name','user_id'];
    public $timestamps = true;

    public function branches()
    {
    	return $this->hasMany('\App\EcommerceModel\Branch','area');
    }
}
