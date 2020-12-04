<?php

namespace App\MailingListModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'description'];

    public function subscribers()
    {
        return $this->belongsToMany(Subscriber::class, 'groups_has_subscribers')->withTimestamps();;
    }
}
