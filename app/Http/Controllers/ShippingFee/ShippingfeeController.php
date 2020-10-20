<?php

namespace App\Http\Controllers\ShippingFee;

use Illuminate\Support\Facades\Validator;
use App\Helpers\ListingHelper;
use App\Helpers\ModelHelper;
use App\Shippingfee;
use App\ShippingfeeLocations;
use App\ShippingfeeWeight;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Cities;
use App\Provinces;

class ShippingfeeController extends Controller
{
    private $searchFields = ['name'];
    private $localSearchFields = [ 'name' ];
    public function index()
    {
        $listing = new ListingHelper();

        $shippingfees = $listing->simple_search(Shippingfee::class, $this->searchFields);

   
        $filter = $listing->get_filter($this->searchFields);
        

        $searchType = 'simple_search';

        return view('admin.shippingfee.index',compact('shippingfees','filter','searchType'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $provinces = Provinces::orderBy('province','asc')->get();

        return view('admin.shippingfee.create',compact('provinces'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|max:150|unique:shippingfees,name',
            'rate' => 'required',
            'type' => 'required'
        ])->validate();

        Shippingfee::create([
            'name' => $request->name,
            'is_international' => $request->type,
            'province' => ($request->type == 1) ? 0 : $request->province,
            'rate' => $request->rate,
            'is_outside_manila' => ($request->province == 49) ? 0 : 1,
            'user_id' => Auth::id()
        ]);
        
        return redirect(route('shippingfee.index'))->with('success','Shipping rate has been added.');
    }

    public function manage($id)
    {
        $sp      = Shippingfee::findOrFail($id);
        $weights = $sp->weights()->paginate(10);

        return view('admin.shippingfee.manage',compact('sp','weights'));
    }

    public function location_store(Request $request)
    {   
        $data = $request->all();
        $fee = Shippingfee::find($request->shippingfee_id);

        $fee->update([
            'name' => $request->name,
            'rate' => ($fee->is_international == 0) ? $request->rate : 0
        ]);

        if($fee){

            $arr_locations = [];
            $saved_locations = ShippingfeeLocations::where('shippingfee_id',$request->shippingfee_id)->get();

            foreach($saved_locations as $l){
                array_push($arr_locations,$l->name);
            }

            // save new locations
            $selected_location = $data['selected_locations'];
            foreach($selected_location as $key => $location){
                if(!in_array($location,$arr_locations)){

                    ShippingfeeLocations::create([
                        'shippingfee_id' => $request->shippingfee_id,
                        'name' => $location,
                        'user_id' => Auth::id()
                    ]);
                }
            }

            // delete existing promotional product that is not selected
            $arr_selectedlocations = [];
            foreach($selected_location as $key => $slocation){
                array_push($arr_selectedlocations,$slocation);
            }

            foreach($saved_locations as $location){
                if(!in_array($location->name,$arr_selectedlocations)){
                    ShippingfeeLocations::where('name',$location->name)->delete();
                }
            }

        }

        return back()->with('success','Successfully added new locations for this zone');
    }

    public function weight_store(Request $request)
    {
        $save = ShippingfeeWeight::create([
            'weight' => $request->weight,
            'shippingfee_id' => $request->shippingfee_id,
            'rate' => $request->rate,
            'user_id' => Auth::id()            
        ]);

        return back()->with('success','Successfully added new rate');
    }

    public function weight_update(Request $request)
    {
        $update = ShippingfeeWeight::where('id',$request->weight_id)->update([
            'weight' => $request->weight,
            'rate' => $request->rate,
            'user_id' => Auth::id()            
        ]);

        return back()->with('success','Successfully updated rate');
    }

    public function weight_single_delete(Request $request)
    {
        ShippingfeeWeight::find($request->rates)->delete();

        return back()->with('success','Selected rate has been deleted.');
    }

    public function weight_multiple_delete(Request $request)
    {
        $string = rtrim($request->rates, '|');
        $rates = explode("|",$string);

        foreach($rates as $rate){
            ShippingfeeWeight::find($rate)->delete();
        }

        return back()->with('success','Selected rates has been deleted.');
    }

    public function weight_upload_csv(Request $request)
    {
        if(($handle = fopen($request->csv, 'r')) !== FALSE) {
            // necessary if a large csv file
            set_time_limit(0);

            $row = 0;

            while(($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                $row++;
                // number of fields in the csv
                $col_count = count($data);
                if($row > 1){

                    $shippingfee = ShippingfeeWeight::where('shippingfee_id',$request->shipping_id_csv)->where('weight',$data[0]);

                    if($shippingfee->exists()){

                        $shippingfee->update([
                            'rate' => $data[1],
                            'user_id' => Auth::id()
                        ]);

                    } else {

                        ShippingfeeWeight::create([
                            'shippingfee_id' => $request->shipping_id_csv,
                            'weight' => $data[0],
                            'rate' => $data[1],
                            'user_id' => Auth::id()
                        ]);

                    }
                }

            }
            fclose($handle);
        }
        
        return back()->with('success','Successfully uploaded new rates');
    }


    
    public function show(Shippingfee $shippingfee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Shippingfee  $shippingfee
     * @return \Illuminate\Http\Response
     */
    public function edit(Shippingfee $shippingfee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Shippingfee  $shippingfee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shippingfee $shippingfee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Shippingfee  $shippingfee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shippingfee $shippingfee)
    {
        //
    }

    public function single_delete(Request $request)
    {
        Shippingfee::findOrFail($request->rates)->forceDelete();
        ShippingfeeLocations::where('shippingfee_id',$request->rates)->delete();
        ShippingfeeWeight::where('shippingfee_id',$request->rates)->delete();

        return back()->with('success', 'Shipping fee has been deleted.');
    }

    public function multiple_delete(Request $request)
    {
        $string = rtrim($request->rates, '|');
        $rates = explode("|",$string);

        foreach($rates as $rate){
            Shippingfee::findOrFail($rate)->delete();
        }

        return back()->with('success', 'Selected shipping fee has been deleted');
    }

    public function restore($rate){
        Shippingfee::withTrashed()->find($rate)->update(['user_id' => Auth::id() ]);
        Shippingfee::whereId($rate)->restore();

        return back()->with('success', __('Shipping fee has been restored'));
    }
}
