<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Notifications\Ecommerce\CustomerResetPasswordNotification;
use App\Notifications\NewUserResetPasswordNotification;
use App\Notifications\UserResetPasswordNotification;


use App\Notifications\SendEmailNotification;
use App\Notifications\SendAccountEmailNotification;
use App\Notifications\SendOrderApprovedEmailNotification;
use App\Notifications\SendPaymentApprovalRequestEmailNotification;
use App\Notifications\SendCODApprovalRequestEmailNotification;
use App\Notifications\SendOrderReceivedEmailNotification;

use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Role;


class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'email_verified_at', 'password', 'role_id', 'is_active', 'remember_token', 'firstname', 'lastname', 'avatar', 'user_id', 'isDeleted','mobile','phone','address_street','address_city','address_municipality','address_zip','fromMigration'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function details()
    {
        return $this->belongsTo('\App\EcommerceModel\Customer','id','customer_id');
    }

    public function getFullNameAttribute()
    {
        return "$this->firstname $this->lastname";
    }

    public function getFirstNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function getLastNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function getAddressAttribute()
    {
        return $this->address_street.", ".$this->address_municipality.", ".$this->address_city.", ".$this->address_zip;
    }

    public function getRoleAttribute($value)
    {
        return strtoupper($value);
    }

    public function role_name()
    {
        return User::userRole($this->role_id);
    }

    public static function totalUser()
    {
        $total = User::where('is_active','=',1)->count();

        return $total;
    }

    public static function activeTotalUser()
    {
        $total = User::where('role_id','<>',3)->where('is_active','=',1)->count();

        return $total;
    }

    public static function inactiveTotalUser()
    {
        $total = User::where('role_id','<>',3)->where('is_active','=',0)->count();

        return $total;
    }

    public static function userEmail($id)
    {
        $data = User::where('id',$id)->first();

        return $data->email;
    }

    public static function userRole($id)
    {
        $data = Role::where('id',$id)->first();

        if (!$data) {
            return '';
        }

        return $data->name;
    }


    public function send_order_approved_email($sales,$date)
    {
        $this->notify(new SendOrderApprovedEmailNotification($sales,$date));
    }

    public function send_payment_approval_request_email($sales)
    {
        $this->notify(new SendPaymentApprovalRequestEmailNotification($sales));
    }

    public function send_cod_approval_request_email($sales)
    {
        $this->notify(new SendCODApprovalRequestEmailNotification($sales));
    }

    public function send_order_received_email($sales)
    {
        $this->notify(new SendOrderReceivedEmailNotification($sales));
    }

    

    public function customer_send_reset_password_email()
    {
        $token = app('auth.password.broker')->createToken($this);

        $this->notify(new CustomerResetPasswordNotification($token));
    }

    public function send_email_notification($sales,$template)
    {
        $this->notify(new SendEmailNotification($sales,$template));
    }

    public function send_account_email_notification($template)
    {
        $this->notify(new SendAccountEmailNotification($template));
    }

    public function send_reset_password_email()
    {
        $token = app('auth.password.broker')->createToken($this);

        $this->notify(new UserResetPasswordNotification($token));
    }

    public function send_reset_temporary_password_email()
    {
        $token = app('auth.password.broker')->createToken($this);
        $this->notify(new NewUserResetPasswordNotification($token));
    }

    public function has_access_to_pages_module()
    {
        return $this->has_access_to_module(array_keys(Permission::modules())[0]);
    }

    public function has_access_to_albums_module()
    {
        return $this->has_access_to_module(array_keys(Permission::modules())[1]);
    }

    public function has_access_to_file_manager_module()
    {
        return $this->has_access_to_module(array_keys(Permission::modules())[2]);
    }

    public function has_access_to_menu_module()
    {
        return $this->has_access_to_module(array_keys(Permission::modules())[3]);
    }

    public function has_access_to_news_module()
    {
        return $this->has_access_to_module(array_keys(Permission::modules())[4]);
    }

    public function has_access_to_news_categories_module()
    {
        return $this->has_access_to_module(array_keys(Permission::modules())[5]);
    }

    public function has_access_to_website_settings_module()
    {
        return $this->has_access_to_module(array_keys(Permission::modules())[6]);
    }

    public function has_access_to_audit_logs_module()
    {
        return $this->has_access_to_module(array_keys(Permission::modules())[7]);
    }

    public function has_access_to_user_module()
    {
        return $this->has_access_to_module(array_keys(Permission::modules())[8]);
    }
    
    public function has_access_to_subscriber_group_module()
    {
        return $this->has_access_to_module(array_keys(Permission::modules())[18]);
    }

    public function has_access_to_subscriber_module()
    {
        return $this->has_access_to_module(array_keys(Permission::modules())[19]);
    }

    public function has_access_to_campaign_module()
    {
        return $this->has_access_to_module(array_keys(Permission::modules())[20]);
    }

    public function has_access_to_mailing_list_sent_items_module()
    {
        return $this->has_access_to_module(array_keys(Permission::modules())[21]);
    }

    public function has_access_to_module($module)
    {
        if ($this->is_an_admin() == 1) {
            return true;
        }

        $routes = $this->get_module_routes($module);

        foreach($routes as $route) {
            if ($this->is_route_exist_to_user_permission($route)) {
                return true;
                break;
            }
        }

        return false;
    }

    private function get_module_routes($module)
    {
        return Permission::where('module', $module)->pluck('name');
    }

    private function is_route_exist_to_user_permission($route)
    {
        return \App\ViewPermissions::check_permission($this->role_id, $route) == 1;
    }

    public function has_access_to_route($route)
    {
        if ($this->is_an_admin()) {
            return true;
        }

        $userPermissionRoutes = $this->get_assigned_routes();

        if (in_array($route, $userPermissionRoutes)) {
            return true;
        }

        return false;
    }

    public function get_assigned_routes()
    {
        $permission = $this->assign_role->permissions;

        if ($permission) {
            return $permission->pluck('routes')->flatten()->all();
        }

        return [];
    }

    public function get_image_url_storage_path()
    {
        $delimiter = 'storage/';
        if (strpos($this->avatar, $delimiter) !== false) {
            $paths = explode($delimiter, $this->avatar);
            return $paths[1];
        }

        return '';
    }

    public function get_image_file_name()
    {
        $path = explode('/', $this->avatar);
        $nameIndex = count($path) - 1;
        if ($nameIndex < 0)
            return '';

        return $path[$nameIndex];
    }

    public function is_an_admin()
    {
        return $this->role_id == 1;
    }

    public function is_not_an_admin()
    {
        return $this->role_id != 1;
    }

    public function assign_role()
    {
        return $this->belongsTo(Role::class,'role_id', 'id');
    }
}
