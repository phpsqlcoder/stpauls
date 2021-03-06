<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Role;

class Permission extends Model
{
    use SoftDeletes;

    public $table = 'permission';

    protected $fillable = [ 'name', 'module', 'description', 'routes', 'methods', 'user_id', 'is_view_page'];

    protected $casts = [
        'routes' => 'array',
        'methods' => 'array',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission')->where('isAllowed', 1);
    }

    public function module_code()
    {
        return implode("_", explode(' ', $this->module));
    }

    public static function module_init($controller, $moduleName)
    {
        $permissions = Permission::where('module', $moduleName)->get();

        foreach ($permissions as $permission) {
            $controller->middleware('checkAccessRights:'.$permission->id, ['only' => $permission->methods]);
        }
    }

    public static function has_access_to_route($routeId)
    {
        if (auth()->check())
        {
            $userPermissions = auth()->user()->assign_role->permissions;
            if ($userPermissions->count())
            {
                $permissionIds = $userPermissions->pluck('id')->toArray();

                return (in_array($routeId, $permissionIds));
            }
        }

        return false;
    }

    // public static function modules()
    // {
    //     return [
    //         'page' => 'Page',
    //         'banner' => 'Banner',
    //         'file_manager' => 'File Manager',
    //         'menu' => 'Menu',
    //         'news' => 'News',
    //         'news_category' => 'News Category',
    //         'website_settings' => 'Website Settings',
    //         'audit_logs' => 'Audit Logs',
    //         'user' => 'User',
    //         'product' => 'Product',
    //         'product_category' => 'Product Category',
    //         'product_reviews' => 'Product Reviews',
    //         'customer' => 'Customer',
    //         'sales_transaction' => 'Sales Transaction',
    //         'inventory' => 'Inventory',
    //         'delivery_flat_rate' => "Delivery Flat Rate",
    //         'report' => 'Report'
    //     ];
    // }

    public static function modules()
    {
        return [
            'settings' => 'Settings',
            'users' => 'Users',
            'account_management' => 'Account Management',
            'roles' => 'Roles',
            'permissions' => 'Permissions',
            'products' => 'Products',
            'product_categories' => 'Product Categories',
            'customers' => 'Customers',
            'sales_transaction' => 'Manage Sales Transaction',
            'promos' => 'Promos',
            'loyalty' => 'Loyalty',
            'discounts' => 'Discounts',
            'inventory' => 'Inventory',
            'shipping_rates' => 'Shipping Rates',
            'branches' => 'Branches',
            'email_notifications' => 'Email Notifications',
            'transaction_status' => 'Transaction Status',
            'reports' => 'Reports',
	        'subscriber_group' => 'Subscriber Group',
            'subscriber' => 'Subscriber',
            'campaign' => 'Campaign',
            'sent_item' => 'Sent Campaign',
        ];
    }
}
