<?php

namespace App\EcommerceModel;

use App\Notifications\Ecommerce\CustomerResetPasswordNotification;
use App\Notifications\Ecommerce\CustomerApprovedAccountReactivationNotification;
use App\Notifications\Ecommerce\CustomerDisapprovedAccountReactivationNotification;

use App\Notifications\Ecommerce\CustomerAccountDeactivatedNotification;
use App\Notifications\Ecommerce\OrderApprovedNotification;
use App\Notifications\Ecommerce\OrderRejectedNotification;
use App\Notifications\Ecommerce\PaymentApprovedNotification;
use App\Notifications\Ecommerce\PaymentRejectedNotification;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use SoftDeletes;
    use Notifiable;

    protected $guard = 'customer';

    protected $table = 'customers';
    protected $fillable = ['customer_id','firstname','lastname','email','telno','mobile','address','barangay','city','province','zipcode','is_active','provider','provider_id','is_subscriber','user_id','reactivate_request','customer_id','country'];


    public function delivery_rate()
    {
        return $this->belongsTo('\App\Deliverablecities','city','city');
    }

    public function country()
    {
        return $this->belongsTo('\App\Countries','country');
    }

    public function cities()
    {
        return $this->belongsTo('\App\Cities','city');
    }

    public function provinces()
    {
        return $this->belongsTo('\App\Provinces','province');
    }

    // public function send_reset_password_email()
    // {
    //     $token = app('auth.password.broker')->createToken($this);

    //     $this->notify(new CustomerResetPasswordNotification($token));
    // }

    // public function send_approved_account_reactivation_email()
    // {
    //     $token = app('auth.password.broker')->createToken($this);

    //     $this->notify(new CustomerApprovedAccountReactivationNotification($token));
    // }

    public function send_disapproved_account_reactivation_email()
    {
        $this->notify(new CustomerDisapprovedAccountReactivationNotification());
    }

    // public function send_account_deactivated_email()
    // {
    //     $this->notify(new CustomerAccountDeactivatedNotification());
    // }

    public function send_order_approved_email()
    {
        $this->notify(new OrderApprovedNotification());
    }

    public function send_order_rejected_email()
    {
        $this->notify(new OrderRejectedNotification());
    }

    // public function send_payment_approved_email()
    // {
    //     $this->notify(new PaymentApprovedNotification());
    // }

    // public function send_payment_rejected_email()
    // {
    //     $this->notify(new PaymentRejectedNotification());
    // }

    public static function reactivation_request()
    {
        $qry = Customer::where('reactivate_request',1)->count();

        return $qry;
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

    // public static function customer_username($id)
    // {
    //     $qry = Customer::find($id);

    //     return $qry->firstname.' '.$qry->lastname;
    // }

    public function getAddress1Attribute() {

        return "{$this->address} {$this->barangay}";
    }

    public function getAddress2WithZipAttribute() {

        return "{$this->cities->city}, {$this->provinces->province} {$this->zipcode}";
    }

    public function getAddress2Attribute() {

        return "{$this->cities->city}, {$this->provinces->province}";
    }
}
