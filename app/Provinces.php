<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use \App\Cities;

class Provinces extends Model
{
    public $table = 'tbl_provinces';

    public function cities()
    {
        return $this->hasMany(Cities::class, 'province')->orderBy('city','asc');
    }
}
