<?php

namespace App\Http\Controllers\EcommerceControllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Helpers\ListingHelper;
use Auth;

use App\EcommerceModel\BranchContactNumber;
use App\EcommerceModel\BranchArea;
use App\EcommerceModel\Branch;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;
use App\Provinces;
use App\Cities;

class BranchController extends Controller
{
    private $searchFields = ['name', 'address'];
    private $areaSearchFields = ['name']; 

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listing = new ListingHelper('desc', 10, 'updated_at');

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
        $provinces = Provinces::orderby('province','asc')->get();
        $areas     = BranchArea::get();

        $featured  = Branch::where('isfeatured',1)->count();

        return view('admin.branches.create',compact('provinces','areas','featured'));
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
                'name' => 'required|max:250|unique:branches,name',
                'email' => 'required|email|max:191|unique:branches,email',
                'address' => 'required|max:250',
                'area' => 'required',
                'province' => 'required',
                'city' => 'required'
            ],
            [
                'name.unique' => 'This branch is already in the list.',
            ]  
        );

        $data    = $request->all();
        $cname   = $data['contactname'];
        $cnumber = $data['contactnumber'];

        $file    = $request->branch_img;

        $branch = Branch::create([
            'name' => $request->name,
            'url' => $request->url,
            'area' => $request->area,
            'province_id' => $request->province,
            'city_id' => $request->city, 
            'address' => $request->address,
            'status' => ($request->has('status') ? 'ACTIVE' : 'INACTIVE'),
            'isfeatured' => ($request->has('is_featured') ? 1 : 0),
            'email' => $request->email,
            'other_details' => $request->other_details,
            'img' => $file->getClientOriginalName(),
            'user_id' => Auth::id()
        ]);

        if($branch){
            Storage::makeDirectory('/public/branches/'.$branch->id);
            Storage::putFileAs('/public/branches/'.$branch->id, $file, $file->getClientOriginalName());

            foreach($cname as $key => $name){
                BranchContactNumber::create([
                    'branch_id' => $branch->id,
                    'contact_name' => $name,
                    'contact_no' => $cnumber[$key],
                    'user_id' => Auth::id()
                ]);
            }
        }

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
        $provinces = Provinces::orderby('province','asc')->get();
        $cities = Cities::where('province',$branch->province_id)->orderBy('city','asc')->get();
        $areas     = BranchArea::get();

        return view('admin.branches.edit',compact('branch','provinces','cities','areas'));
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
        $this->validate(
            $request,[
                'name' => 'required|max:250',
                'email' => 'required|email|max:150',
                'address' => 'required|max:250',
                'area' => 'required',
                'province' => 'required',
                'city' => 'required'
            ],
            [
                'name.unique' => 'This branch is already in the list.',
            ]  
        );

        $data    = $request->all();
        $cid     = $data['contactid'];
        $cname   = $data['contactname'];
        $cnumber = $data['contactnumber'];

        $file    = $request->branch_img;

        $branch = Branch::find($id);

        $branch->update([
            'name' => $request->name,
            'url' => $request->url,
            'area' => $request->area,
            'province_id' => $request->province,
            'city_id' => $request->city, 
            'address' => $request->address,
            'status' => ($request->has('status') ? 'ACTIVE' : 'INACTIVE'),
            'isfeatured' => ($request->has('is_featured') ? 1 : 0),
            'email' => $request->email,
            'other_details' => $request->other_details,
            'img' => isset($file) ? $file->getClientOriginalName() : $branch->img,
            'user_id' => Auth::id()
        ]);

        if($branch){
            if(isset($file)){
                Storage::makeDirectory('/public/branches/'.$branch->id);
                Storage::putFileAs('/public/branches/'.$branch->id, $file, $file->getClientOriginalName());
            }
            
            foreach($cid as $key => $id){
                if($id > 0){
                    BranchContactNumber::find($id)->update([
                      'contact_name' => $cname[$key],
                      'contact_no' => $cnumber[$key],
                      'user_id' => Auth::id()
                    ]);
                } else {
                    if($cname[$key] <> null){
                        BranchContactNumber::create([
                            'branch_id' => $branch->id,
                            'contact_name' => $cname[$key],
                            'contact_no' => $cnumber[$key],
                            'user_id' => Auth::id()
                        ]);
                    }
                }

            }
        }

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

    public function update_status($id,$status)
    {
        Branch::findOrFail($id)->update([
            'status' => $status,
            'user_id' => Auth::id()
        ]);

        return back()->with('success', __('standard.branches.update_success'));
    }

    public function multiple_change_status(Request $request)
    {
        $branches = explode("|", $request->branches);

        foreach ($branches as $branch) {
            $status = Branch::where('status', '!=', $request->status)->whereId($branch)->update([
                'status'  => $request->status,
                'user_id' => Auth::id()
            ]);
        }

        return back()->with('success',  __('standard.branches.multiple_update_success', ['STATUS' => $request->status]));
    }

    public function single_delete(Request $request)
    {
        $branch = Branch::findOrFail($request->branches);
        $branch->update([ 'user_id' => Auth::id() ]);
        $branch->delete();


        return back()->with('success', __('standard.branches.single_delete_success'));
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

    public function remove_contact(Request $request)
    {
        BranchContactNumber::find($request->id)->delete();

        return back()->with('success','Branch contact has been removed.');
    }

    public function areas()
    {
        $listing = new ListingHelper('asc', 10, 'name');

        $areas = $listing->simple_search(BranchArea::class, $this->areaSearchFields);
        $filter = $listing->get_filter($this->areaSearchFields);
        $searchType = 'simple_search';

        return view('admin.branches.areas',  compact('areas', 'filter','searchType'));
    }

    public function area_create()
    {
        return view('admin.branches.area_create');
    }

    public function area_store(Request $request)
    {
        BranchArea::create([
            'name' => $request->name,
            'user_id' => Auth::id()
        ]);

        return redirect(route('branch.areas'))->with('success', 'Branch area has been added.');
    }

    public function area_edit($id)
    {
        $area = BranchArea::find($id);

        return view('admin.branches.area_edit',compact('area'));
    }

    public function area_update(Request $request)
    {
        BranchArea::find($request->areaid)->update([
            'name' => $request->name,
            'user_id' => Auth::id()
        ]);

        return redirect(route('branch.areas'))->with('success', 'Branch area has been updated');
    }

    public function area_single_delete(Request $request)
    {
        $area = BranchArea::findOrFail($request->areas);
        $area->update([ 'user_id' => Auth::id() ]);
        $area->delete();


        return back()->with('success', 'Branch area has been deleted.');
    }

    public function area_multiple_delete(Request $request)
    {
        $areas = explode("|",$request->areas);

        foreach($areas as $area){
            BranchArea::whereId($area)->update(['user_id' => Auth::id() ]);
            BranchArea::whereId($area)->delete();
        }

        return back()->with('success','Selected areas has been deleted.');
    }

    public function remove_image(Request $request)
    {   
        $branch = Branch::find($request->branchid);
        Storage::delete('/public/branches/'.$branch->id.'/'.$branch->img);
        $branch->update(['img' => NULL]);

        return back()->with('success','Branch image has been removed.');
    }
}
