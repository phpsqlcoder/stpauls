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
use App\Logs;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Validator;


use App\StPaulModel\TransactionStatus;
use App\EcommerceModel\Customer;
use App\User;


class CustomerController extends Controller
{
    use SendsPasswordResetEmails;

    public function __construct()
    {
        Permission::module_init($this, 'customer');
    }

    private $searchFields = ['firstname','lastname','updated_at'];


    public function index($param = null)
    {
        $customConditions = [
            [
                'field' => 'is_active',
                'operator' => '=',
                'value' => 1,
                'apply_to_deleted_data' => false
            ],
            [
                'field' => 'role_id',
                'operator' => '=',
                'value' => 3,
                'apply_to_deleted_data' => true
            ]
        ];

        $listing = new ListingHelper('desc', 10, 'updated_at', $customConditions);

        $customers = $listing->simple_search(User::class, $this->searchFields);

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
        $customer = Customer::find($request->customer_id);
        $user     = User::find($customer->customer_id);

        $customer->update([
            'is_active' => $request->status,
            'reactivate_request' => 0,
            'user_id'   => Auth::id(),
        ]);

        $user->update([
            'is_active' => $request->status,
            'user_id' => Auth::id()
        ]);


        $status = ($request->status == 1) ? 'approved' : 'disapproved';
        if($request->status == 1){
            $user->customer_send_approved_account_reactivation_email();
        } else {
            $user->send_disapproved_account_reactivation_email();
        }

        return back()->with('success', __('standard.customers.reactivate_status', ['status' => $status]));
    }

    public function activate(Request $request)
    {
        $user = User::find($request->customer_id);
        $user->update([
            'is_active' => 1,
            'user_id'   => Auth::id(),
        ]);

        Customer::where('customer_id',$user->id)->update([
            'is_active' => 1,
            'user_id'   => Auth::id(),
        ]);

        return back()->with('success', __('standard.customers.status_success', ['status' => 'activated']));
    }

    public function deactivate(Request $request)
    {
        $user = User::find($request->customer_id);
    	$user->update([
            'is_active' => 0,
            'user_id'   => Auth::id(),
        ]);

        Customer::where('customer_id',$user->id)->update(['is_active' => 0]);

        $user->customer_send_account_deactivated_email();

        return back()->with('success', __('standard.customers.status_success', ['status' => 'deactivated']));
    }

    public function send_email_notification($sales,$transactionstatus)
    {
        $qry = TransactionStatus::where('name',$transactionstatus)->where('status','ACTIVE');
        $count = $qry->count();

        if($qry->count() > 0){
            $template = $qry->first();

            $user = User::find($sales->customer_id);
            $user->send_email_notification($sales,$template);
        }
    }

    








    // public function show($id, $param = null)
    // {
    //     $user = Customer::where('id',$id)->first();
    //     $logs = Logs::where('created_by',$id)->orderBy('id','desc')->paginate(10);

    //     return view('admin.customers.profile',compact('user','logs','param'));
    // }

}
