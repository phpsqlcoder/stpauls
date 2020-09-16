<?php

namespace App\EcommerceModel;

use App\Notifications\Ecommerce\CustomerResetPasswordNotification;
use App\Notifications\Ecommerce\CustomerReactivateAccountNotification;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;

    protected $guard = 'customer';

    protected $table = 'customers';
    protected $fillable = ['email','password','firstname','lastname','telno','mobile','address','barangay','city','province','zipcode','is_active','provider','fbId','googleId','is_subscriber','user_id','remember_token','reactivate_request'];


    public function delivery_rate()
    {
        return $this->belongsTo('\App\Deliverablecities','city','city');
    }
    public function setIsEmailSubscriberAttribute($value)
    {
        if ($value == 0 || $value == false) {
            $this->attributes['is_email_subscriber'] = 0;
        } else {
            $this->attributes['is_email_subscriber'] = 1;
        }
    }

    public function getContactNumbersAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setContactNumbersAttribute($arrayValue)
    {
        $newValue = [];
        foreach ($arrayValue as $value) {
            if (!empty($value)) {
                array_push($newValue, $value);
            }
        }

        $this->attributes['contact_numbers'] = json_encode($newValue);
    }

    public function send_reset_password_email()
    {
        $token = app('auth.password.broker')->createToken($this);

        $this->notify(new CustomerResetPasswordNotification($token));
    }

    public function send_reactivate_confirmation_email()
    {
        $token = app('auth.password.broker')->createToken($this);

        $this->notify(new CustomerReactivateAccountNotification($token));
    }

    public function middle_name_abbreviation()
    {
        if (empty($this->middle_name)) {
            return ' ';
        }

        $explodeMN = explode(" ", $this->middle_name);

        $abbreviation = ' ';
        foreach($explodeMN as $mname) {
            $abbreviation .= strtoupper($mname[0]).'.';
        }

        return $abbreviation." ";
    }

    public function getFullNameWithAbbreviationAttribute() {
        return "{$this->first_name}{$this->middle_name_abbreviation()}{$this->last_name} {$this->ext_name}";
    }

    public function getFullNameAttribute() {

        return "{$this->firstname} {$this->lastname}";
    }

    public function getContactNumbersStrAttribute() {
        return implode(' / ', $this->contact_numbers);
    }

    public function is_an_email_subscriber()
    {
        return $this->is_email_subscriber == 1;
    }

    public static function customer_username($id)
    {
        $qry = Customer::find($id);

        return $qry->firstname.' '.$qry->lastname;
    }

    public function cities()
    {
        return $this->belongsTo('\App\Cities','city');
    }

    public function provinces()
    {
        return $this->belongsTo('\App\Provinces','province');
    }

    public function getAddress1Attribute() {

        return "{$this->address} {$this->barangay}";
    }

    public function getAddress2WithZipAttribute() {

        return "{$this->cities->city}, {$this->provinces->province} {$this->zipcode}";
    }

    public function getAddress2Attribute() {

        return "{$this->cities->city}, {$this->provinces->province}";
    }

    public static function reactivation_request()
    {
        $qry = Customer::where('reactivate_request',1)->count();

        return $qry;
    }
}
