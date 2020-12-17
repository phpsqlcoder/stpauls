<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TitleRequest extends Model
{
    public $table = 'title_requests';
    public $fillable = ['email','firstname','lastname','mobile_no','title','author','isbn','message'];
    public $timestamp = true;
}
