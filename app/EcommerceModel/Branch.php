<?php

namespace App\EcommerceModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;

    protected $table = 'branches';
    protected $fillable = ['name', 'address', 'contact_no', 'contact_person','email','is_active','user_id'];
    public $timestamps = true;
}
