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
use App\EcommerceModel\SalesHeader;
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
    private $salesHeadersearchFields = ['order_number','created_at'];


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

    public function orders($id)
    {
        $customConditions = [
            [
                'field' => 'customer_id',
                'operator' => '=',
                'value' => $id,
                'apply_to_deleted_data' => true
            ]
        ];

        $user = \App\User::find($id);

        $listing = new ListingHelper('desc', 10, 'created_at', $customConditions);

        $sales = $listing->simple_search(SalesHeader::class, $this->salesHeadersearchFields);

        // Simple search init data
        $filter = $listing->get_filter($this->salesHeadersearchFields);

        $searchType = 'simple_search';

        return view('admin.customers.orders',compact('sales','filter', 'searchType','user'));        
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

        $this->send_email_notification($request->customer_id,'Deactivate Account');

        return back()->with('success', __('standard.customers.status_success', ['status' => 'deactivated']));
    }

    public function reactivate(Request $request)
    {
        Customer::where('customer_id',$request->customer_id)->update([
            'is_active' => $request->status,
            'reactivate_request' => 0,
            'user_id'   => Auth::id(),
        ]);

        if($request->status == 1){
            User::find($request->customer_id)->update([
                'is_active' => 1,
                'user_id' => Auth::id()
            ]);
        }
        

        $status = ($request->status == 1) ? 'approved' : 'disapproved';
        if($request->status == 1){
            $this->send_email_notification($request->customer_id,'Approve Reactivation Request');
        } else {
            $this->send_email_notification($request->customer_id,'Disapprove Reactivation Request');
        }

        return back()->with('success', __('standard.customers.reactivate_status', ['status' => $status]));
    }

    public function send_email_notification($customer_id,$transactionstatus)
    {
        $qry = TransactionStatus::where('name',$transactionstatus)->where('status','ACTIVE');

        if($qry->exists()){
            $template = $qry->first();

            $user = User::find($customer_id);
            $user->send_account_email_notification($template);
        }
    }

}
