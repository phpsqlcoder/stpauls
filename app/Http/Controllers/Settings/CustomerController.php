<?php

namespace App\Http\Controllers\Settings;
use App\Http\Controllers\Controller;

use App\Permission;

use App\Helpers\ListingHelper;

use App\Http\Requests\UserRequest;
use App\Mail\AddNewUserMail;

use Illuminate\Support\Facades\Input;

use App\Helpers\Webfocus\Setting;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;

use App\Mail\UpdatePasswordMail;
use App\Role;
// use App\Logs;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Validator;


use App\EcommerceModel\Customer;


class CustomerController extends Controller
{
    use SendsPasswordResetEmails;

    public function __construct()
    {
        Permission::module_init($this, 'customer');
    }

    private $searchFields = ['firstname','lastname'];


    public function index($param = null)
    {
        $customConditions = [
            [
                'field' => 'is_active',
                'operator' => '=',
                'value' => 1,
                'apply_to_deleted_data' => false
            ]
        ];

        $listing = new ListingHelper('desc', 10, 'updated_at', $customConditions);

        $customers = $listing->simple_search(Customer::class, $this->searchFields);

        // Simple search init data
        $filter = $listing->get_filter($this->searchFields);

        $searchType = 'simple_search';

        return view('admin.customers.index',compact('customers','filter', 'searchType'));
    }

    public function reactivate_request($param = null)
    {
        $customConditions = [
            [
                'field' => 'reactivate_request',
                'operator' => '=',
                'value' => 1,
                'apply_to_deleted_data' => false
            ]
        ];

        $listing = new ListingHelper('desc', 10, 'updated_at', $customConditions);

        $customers = $listing->simple_search(Customer::class, $this->searchFields);

        // Simple search init data
        $filter = $listing->get_filter($this->searchFields);

        $searchType = 'simple_search';

        return view('admin.customers.reactivate-request',compact('customers','filter', 'searchType'));
    }

    public function reactivate(Request $request)
    {
        $user = Customer::find($request->customer_id)->update([
            'is_active' => $request->status,
            'reactivate_request' => 0,
            'user_id'   => Auth::id(),
        ]);

        $status = ($request->status == 1) ? 'approved' : 'disapproved';
        // $user->send_reactivate_confirmation_email();

        // if (Mail::failures()) {
        //     return back()
        //         ->withInput($request->only('email'))
        //         ->withErrors(['email' => trans('passwords.user')]);
        // }

        return back()->with('success', __('standard.customers.reactivate_status', ['status' => $status]));
    }

    public function deactivate(Request $request)
    {
    	Customer::find($request->customer_id)->update([
            'is_active' => 0,
            'user_id'   => Auth::id(),
        ]);

        return back()->with('success', __('standard.customers.status_success', ['status' => 'deactivated']));
    }

    public function activate(Request $request)
    {
    	Customer::find($request->customer_id)->update([
            'is_active' => 1,
            'user_id'   => Auth::id(),
        ]);

        return back()->with('success', __('standard.customers.status_success', ['status' => 'activated']));
    }


    public function show($id, $param = null)
    {
        $user = User::where('id',$id)->first();
        $logs = Logs::where('created_by',$id)->orderBy('id','desc')->paginate(10);

        return view('admin.customers.profile',compact('user','logs','param'));
    }

}
