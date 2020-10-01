<?php

namespace App\Http\Controllers\EcommerceControllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\EcommerceModel\Branch;
use App\Helpers\ListingHelper;
use Auth;

class BranchController extends Controller
{
    private $searchFields = ['name', 'address', 'contact_person'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customConditions = [
            [
                'field' => 'is_active',
                'operator' => '=',
                'value' => 1,
                'apply_to_deleted_data' => false
            ]
        ];

        $listing = new ListingHelper('asc', 10, 'name', $customConditions);

        $branches = $listing->simple_search(Branch::class, $this->searchFields);
        $filter = $listing->get_filter($this->searchFields);
        $searchType = 'simple_search';

        return view('admin.branches.index',  compact('branches', 'filter','searchType'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.branches.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,[
                'name' => 'required|max:150|unique:branches,name',
                'address' => 'required|max:250',
                'contact_no' => 'required',
                'email_address' => 'required'
            ],
            [
                'name.unique' => 'This branch is already in the list.',
            ]  
        );

        Branch::create([
            'name' => $request->name,
            'address' => $request->address,
            'contact_no' => $request->contact_no,
            'contact_person' => $request->contact_person ?? ' ',
            'email' => $request->email_address,
            'is_active' => 1,
            'user_id' => Auth::id()
        ]);

        return redirect()->route('branch.index')->with('success', __('standard.branches.create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $branch = Branch::findOrFail($id);
        return view('admin.branches.edit',compact('branch'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Branch::findOrFail($id)->update([
            'name' => $request->name,
            'address' => $request->address,
            'contact_no' => $request->contact_no,
            'contact_person' => $request->contact_person ?? ' ',
            'email' => $request->email_address,
            'user_id' => Auth::id()
        ]);

        return redirect()->route('branch.index')->with('success', __('standard.branches.update_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function single_delete(Request $request)
    {
        $branch = Branch::findOrFail($request->branches);
        $branch->update([ 'user_id' => Auth::id() ]);
        $branch->delete();


        return back()->with('success', __('standard.branches.single_delete_success'));;
    }

    public function restore($id)
    {
        Branch::withTrashed()->find($id)->update(['user_id' => Auth::id() ]);
        Branch::whereId($id)->restore();

        return back()->with('success', __('standard.branches.restore_success'));
    }

    public function multiple_delete(Request $request)
    {
        $branches = explode("|",$request->branches);

        foreach($branches as $branch){
            Branch::whereId($branch)->update(['user_id' => Auth::id() ]);
            Branch::whereId($branch)->delete();
        }

        return back()->with('success', __('standard.branches.multiple_delete_success'));
    }
}
