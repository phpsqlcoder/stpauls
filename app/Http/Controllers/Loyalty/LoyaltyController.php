<?php

namespace App\Http\Controllers\Loyalty;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ListingHelper;

use Auth;

use App\StPaulModel\LoyalCustomer;
use App\StPaulModel\Discount;

class LoyaltyController extends Controller
{
    private $searchFields = ['customer_id','total_purchase'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($param = null)
    {
        $customConditions = [
            [
                'field' => 'total_purchase',
                'operator' => '>',
                'value' => 1,
                'apply_to_deleted_data' => false
            ]
        ];

        $listing = new ListingHelper('desc', 10, 'updated_at', $customConditions);

        $customers = $listing->simple_search(LoyalCustomer::class, $this->searchFields);
        $discounts = Discount::where('status','PUBLISHED')->orderBy('name')->get(); 

        // Simple search init data
        $filter = $listing->get_filter($this->searchFields);

        $searchType = 'simple_search';

        return view('admin.loyalty.index',compact('customers','discounts','filter', 'searchType'));
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
        //
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
        //
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
        //
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

    public function approved(Request $request)
    {
        $discount = Discount::find($request->discount);

        LoyalCustomer::find($request->id)->update([
            'discount_id' => $discount->id,
            'status' => 'APPROVED',
            'user_id' => Auth::id()
        ]);

        return redirect()->route('loyalty.index')->with('success', __('standard.loyalty.approved_loyalty_success'));
    }

    public function disapproved(Request $request)
    {
       LoyalCustomer::find($request->id)->update([
        'status' => 'DISAPPROVED'
       ]); 

       return redirect()->route('loyalty.index')->with('success', __('standard.loyalty.disapproved_loyalty_success'));
    }

    public function update_discount(Request $request)
    {
        LoyalCustomer::find($request->id)->update([
            'discount_id' => $request->discount,
            'user_id' => Auth::id()
        ]);

        return redirect()->route('loyalty.index')->with('success', __('standard.loyalty.update_discount_success'));
    }
    
}
