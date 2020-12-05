<?php

namespace App\MailingListModel;

use App\Helpers\Webfocus\Setting;
use App\Mail\MailingList\CampaignMail;
use App\SentCampaign;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;

class Subscriber extends Model
{
    use SoftDeletes;

    protected $fillable = ['first_name', 'last_name', 'email', 'code', 'is_active'];

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'groups_has_subscribers')->withTimestamps();;
    }

    public function email_with_name()
    {
        if (empty($this->first_name)) {
            return "{$this->email}";
        } else {
            return "{$this->email} - {$this->first_name} {$this->last_name}";
        }
    }

    public function send_campaign(Campaign $campaign)
    {
        Mail::to($this->email)->send(new CampaignMail(Setting::info(), $campaign));
//        return !Mail::failures();
    }

    public static function generate_unique_code()
    {
        $randomString = self::generate_random_string();
        $subscriber = Subscriber::where('code', $randomString)->get();
        while ($subscriber->count()) {
            $randomString = self::generate_random_string();
            $subscriber = Subscriber::where('code', $randomString)->first();
        }

        return $randomString;
    }

    private static function generate_random_string($length = 128) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
