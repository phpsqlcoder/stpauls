<?php

namespace App\Http\Controllers\Settings;

use App\Helpers\ListingHelper;
use App\Http\Requests\UserRequest;
use App\Mail\AddNewUserMail;
use App\Permission;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\Helpers\Webfocus\Setting;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;

use App\Mail\UpdatePasswordMail;
use App\Role;
use App\User;
use App\Logs;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use SendsPasswordResetEmails;

    private $searchFields = ['name'];

    public function __construct()
    {
        Permission::module_init($this, 'user');
    }

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
                'operator' => '<>',
                'value' => 3,
                'apply_to_deleted_data' => true
            ]
        ];

        $listing = new ListingHelper('desc', 10, 'updated_at', $customConditions);

        $users = $listing->simple_search(User::class, $this->searchFields);

        // Simple search init data
        $filter = $listing->get_filter($this->searchFields);

        $searchType = 'simple_search';

        return view('admin.users.index',compact('users','filter', 'searchType'));
    }

    public function create()
    {
        $roles = Role::where('id','<>',3)->orderBy('name','asc')->get();
        return view('admin.users.create',compact('roles'));
    }

    public function store(UserRequest $request)
    {

        $user = User::create([
            'firstname'      => $request->fname,
            'lastname'       => $request->lname,
            'name'           => $request->fname.' '.$request->lname,
            'password'       => str_random(32),
            'email'          => $request->email,
            'role_id'        => $request->role,
            'is_active'      => 1,
            'user_id'        => Auth::id(),
            'remember_token' => str_random(60)
        ]);

        $user->send_reset_temporary_password_email();

        return redirect()->route('users.index')->with('success', 'Pending for activation. Please remind the user to check the email and activate the account.');
//        }
    }

    public function edit($id)
    {
        $roles = Role::get();
        $user = User::where('id',$id)->first();

        return view('admin.users.edit',compact('user','roles'));
    }

    public function update(Request $request, User $user)
    {
        Validator::make($request->all(), [
            'fname' => 'required|max:150',
            'lname' => 'required|max:150',
            'email' => 'required|email|max:191|unique:users,email,'.$user->id,
            'role' => 'required|exists:role,id'
        ])->validate();

        $user->update([
            'firstname'=> $request->fname,
            'lastname' => $request->lname,
            'name'     => $request->fname.' '.$request->lname,
            'email'    => $request->email,
            'role_id'  => $request->role,
            'user_id'  => Auth::id(),
        ]);

        return redirect(route('users.index'))->with('success', __('standard.users.update_success'));
    }

    public function deactivate(Request $request)
    {
        User::find($request->user_id)->update([
            'is_active' => 0,
            'user_id'   => Auth::id(),
        ]);

        return back()->with('success', __('standard.users.status_success', ['status' => 'deactivated']));
    }

    public function activate(Request $request)
    {
        User::find($request->user_id)->update([
            'is_active' => 1,
            'user_id'   => Auth::id(),
        ]);

        return back()->with('success', __('standard.users.status_success', ['status' => 'activated']));
    }


    public function show($id, $param = null)
    {
        $user = User::where('id',$id)->first();
        $logs = Logs::where('created_by',$id)->orderBy('id','desc')->paginate(10);

        return view('admin.users.profile',compact('user','logs','param'));
    }

    public function filter()
    {
        $params = Input::all();

        return $this->apply_filter($params);
    }

    public function apply_filter($param = null)
    {
        $user = User::where('id',$param['id'])->first();

        if(isset($param['order'])){
            $logs = Logs::where('created_by',$param['id'])->orderBy($param['sort'],$param['order'])->paginate($param['pageLimit']);
        } else {
            $logs = Logs::where('created_by',$param['id'])->paginate($param['pageLimit']);
        }

        return view('admin.users.profile',compact('user','logs','param'));
    }

}
