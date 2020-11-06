<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MediaAccounts extends Model
{
    public $table = 'social_media';

    protected $fillable = [ 'name', 'media_account', 'user_id','account_name'];

    public $timestamps = false;

    public static function icons($media)
    {
    	switch($media){
		    case "facebook":
		        return "fa-facebook-f";
		        break;
		    case "messenger":
		        return "fa-facebook-messenger";
		        break;
		    case "twitter":
		        return "fa-twitter";
		        break;
		    case "youtube":
		        return "fa-youtube";
		        break;
		    case "whatsapp":
		        return "fa-whatsapp";
		        break;
		    case "instagram":
		        return "fa-instagram";
		        break;
		}
    }
}
