<?php

namespace App\Http\Controllers\ShippingFee;

use App\Helpers\ListingHelper;
use App\Helpers\ModelHelper;
use App\Shippingfee;
use App\ShippingfeeLocations;
use App\ShippingfeeWeight;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class ShippingfeeController extends Controller
{
    private $searchFields = ['name'];
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $save = Shippingfee::create([
            'name' => $request->zone,
            'user_id' => Auth::id(),
            'is_international' => (isset($request->is_international)) ? 1 : 0,
            'is_outside_manila' => (isset($request->is_outside_manila)) ? 1 : 0
        ]);
        
        return back()->with('success','Successfully added new zone');
    }

    public function manage($id)
    {
        $sp = Shippingfee::findOrFail($id);

        return view('admin.shippingfee.manage',compact('sp'));
    }

    public function location_store(Request $request){
        foreach($request->selected_countries as $location){
            $store = ShippingfeeLocations::create([
                'shippingfee_id' => $request->shippingfee_id,
                'name' => $location,
                'user_id' => Auth::id()
            ]);
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

    public function weight_delete_all(Request $request)
    {
        $delete = ShippingfeeWeight::where('shippingfee_id',$request->shipping_id_delete)->delete();

        return back()->with('success','Successfully deleted all rates');
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
                    $save = ShippingfeeWeight::create([
                        'weight' => $data[0],
                        'shippingfee_id' => $request->shipping_id_csv,
                        'rate' => $data[1],
                        'user_id' => Auth::id()            
                    ]);
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
}
