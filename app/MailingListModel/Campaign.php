<?php

namespace App\MailingListModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'from_email', 'from_name', 'subject', 'content'];

}
