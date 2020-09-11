<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Helpers\ListingHelper;
use Illuminate\Http\Request;

use App\Deliverablecities;
use App\Permission;

use App\Provinces;
use App\Cities;

use Auth;

class DeliverablecitiesController extends Controller
{
    private $searchFields = ['city_name','rate','is_outside'];

    public function __construct()
    {
        Permission::module_init($this, 'delivery_flat_rate');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($param = null)
    {
        $listing = new ListingHelper('asc', 10, 'city_name');

        $locations = $listing->simple_search(Deliverablecities::class, $this->searchFields);

        // Simple search init data
        $filter = $listing->get_filter($this->searchFields);
        $searchType = 'simple_search';

        return view('admin.deliverable-locations.index',compact('locations', 'filter', 'searchType'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $provinces = Provinces::orderBy('province','asc')->get();

        return view('admin.deliverable-locations.create',compact('provinces'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $city = Cities::find($request->city);
        Deliverablecities::create([
            'province' => $request->province,
            'city' => $city->id,
            'city_name' => $city->city,
            'rate' => $request->rate,
            'is_outside' => $request->has('is_outside'),
            'status' => ($request->has('status') ? 'PUBLISHED' : 'PRIVATE'),
            'user_id' => Auth::id()
        ]);

        return redirect(route('locations.index'))->with('success','Successfully saved new location!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Deliverablecities  $deliverablecities
     * @return \Illuminate\Http\Response
     */
    public function show(Deliverablecities $deliverablecities)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Deliverablecities  $deliverablecities
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rate = Deliverablecities::findOrFail($id);

        $provinces = Provinces::orderBy('province','asc')->get();
        $cities = Cities::where('province',$rate->province)->orderBy('city','asc')->get();
        

        return view('admin.deliverable-locations.edit',compact('rate','provinces','cities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Deliverablecities  $deliverablecities
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $city = Cities::find($request->city);
        Deliverablecities::findOrFail($id)->update([
            'province' => $request->province,
            'city' => $city->id,
            'city_name' => $city->city,
            'rate' => $request->rate,
            'is_outside' => $request->has('is_outside'),
            'user_id' => Auth::id()
        ]);

        return redirect(route('locations.index'))->with('success','Successfully updated delivery rate!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Deliverablecities  $deliverablecities
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {

    }

    public function update_status($id,$status)
    {
        Deliverablecities::find($id)->update([
            'status' => $status,
            'user_id' => Auth::id()
        ]);

        return back()->with('success', __('standard.locations.update_success', ['STATUS' => $status]));
    }

    public function multiple_change_status(Request $request)
    {
        $locations = explode("|", $request->locations);

        foreach ($locations as $location) {
            $publish = Deliverablecities::whereId($location)->update([
                'status'  => $request->status,
                'user_id' => Auth::id()
            ]);
        }

        return back()->with('success',  __('standard.locations.update_success', ['STATUS' => $request->status]));
    }

    public function single_delete(Request $request)
    {
        $rate = Deliverablecities::findOrFail($request->locations);
        $rate->update([ 'user_id' => Auth::id() ]);
        $rate->delete();


        return back()->with('success', 'Location has been deleted.');
    }

    public function restore($id)
    {
        Deliverablecities::withTrashed()->find($id)->update(['user_id' => Auth::id() ]);
        Deliverablecities::whereId($id)->restore();

        return back()->with('success', 'Location has been restored.');
    }

    public function multiple_delete(Request $request)
    {
        $locations = explode("|",$request->locations);

        foreach($locations as $location){
            Deliverablecities::whereId($location)->update(['user_id' => Auth::id() ]);
            Deliverablecities::whereId($location)->delete();
        }

        return back()->with('success', 'Selected locations has been deleted.');
    }
}
