<?php

namespace App\Http\Controllers\Loyalty;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Helpers\ListingHelper;
use App\Http\Controllers\Controller;

use Auth;

use App\StPaulModel\Discount;

class DiscountController extends Controller
{
    private $searchFields = ['name'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($param = null)
    {
        $listing = new ListingHelper('desc', 10, 'updated_at');

        $discounts = $listing->simple_search(Discount::class, $this->searchFields);

        // Simple search init data
        $filter = $listing->get_filter($this->searchFields);
        $searchType = 'simple_search';

        return view('admin.loyalty.discount-index',compact('discounts', 'filter', 'searchType'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.loyalty.discount-create');
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
                'name' => 'required|max:150|unique:discounts,name',
                'discount' => 'required'
            ]
        );

        Discount::create([
            'name' => $request->name,
            'discount' => $request->discount,
            'status' => ($request->has('status') ? 'PUBLISHED' : 'PRIVATE'),
            'user_id' => Auth::id()
        ]);

        return redirect()->route('discounts.index')->with('success', __('standard.discount.create_success'));
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
        $discount = Discount::find($id);

        return view('admin.loyalty.discount-edit',compact('discount'));
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
        Discount::find($id)->update([
            'name' => $request->name,
            'discount' => $request->discount,
            'user_id' => Auth::id()
        ]);

        return redirect()->route('discounts.index')->with('success', __('standard.discount.update_success'));
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
        $discount = Discount::findOrFail($request->discounts);
        $discount->update([ 'user' => Auth::id() ]);
        $discount->delete();

        return back()->with('success', __('standard.discount.single_delete_success'));
    }

    public function restore($discount){
        Discount::withTrashed()->find($discount)->update(['user_id' => Auth::id() ]);
        Discount::whereId($discount)->restore();

        return back()->with('success', __('standard.discount.restore_discount_success'));
    }

    public function update_status($id,$status)
    {
        Discount::find($id)->update([
            'status' => $status,
            'user_id' => Auth::id()
        ]);

        return back()->with('success', __('standard.discount.discount_update_success', ['STATUS' => $status]));
    }

    public function multiple_change_status(Request $request)
    {
        $discounts = explode("|", $request->discounts);

        foreach ($discounts as $discount) {
            $publish = Discount::where('status', '!=', $request->status)->whereId($discount)->update([
                'status'  => $request->status,
                'user_id' => Auth::id()
            ]);
        }

        return back()->with('success',  __('standard.discount.discount_update_success', ['STATUS' => $request->status]));
    }

    public function multiple_delete(Request $request)
    {
        $discounts = explode("|", $request->discounts);

        foreach($discounts as $discount){
            Discount::whereId($discount)->update(['user_id' => Auth::id() ]);
            Discount::whereId($discount)->delete();
        }

        return back()->with('success', __('standard.discount.multiple_delete_success'));
    }
}
