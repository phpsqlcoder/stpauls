<?php

namespace App\Http\Controllers\EcommerceControllers;

use App\EcommerceModel\Wishlist;
use App\EcommerceModel\WishlistCustomer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Helpers\ListingHelper;
use Auth;

use App\EcommerceModel\Product;

class WishlistController extends Controller
{
    private $searchFields = ['product_name'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listing = new ListingHelper('desc', 10, 'updated_at');

        $products = $listing->simple_search(Wishlist::class, $this->searchFields);
        $filter = $listing->get_filter($this->searchFields);
        $searchType = 'simple_search';

        return view('admin.wishlist.index',compact('products', 'filter','searchType'));
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
     * @param  \App\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function show(Wishlist $wishlist)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function edit(Wishlist $wishlist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wishlist $wishlist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wishlist $wishlist)
    {
        //
    }

    public function add_to_wishlist(Request $request)
    {
        $product = Product::find($request->product_id);
        $data = Wishlist::where('product_id',$request->product_id);

        if($data->count() > 0){
            $wishlist = $data->first();

            Wishlist::where('product_id',$request->product_id)->update([
                'total_count' => ($wishlist->total_count+1)
            ]);

            $qry = WishlistCustomer::where('customer_id',Auth::id())->where('product_id',$product->id)->exists();
            if(!$qry){
                WishlistCustomer::create([
                    'customer_id' => Auth::id(),
                    'product_id' => $product->id
                ]);
            }

        } else {
            Wishlist::create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'total_count' => 1
            ]);

            WishlistCustomer::create([
                'customer_id' => Auth::id(),
                'product_id' => $product->id
            ]);
        }
        
    }

    public function remove_to_wishlist(Request $request)
    {
        $data = Wishlist::where('product_id',$request->product_id)->first();

        $qry = Wishlist::where('product_id',$request->product_id)->update(['total_count' => ($data->total_count-1)]);

        if($qry){
            $qry2 = Wishlist::where('product_id',$request->product_id)->first();

            if($qry2->total_count == 0){
                Wishlist::where('product_id',$request->product_id)->delete();
            }
        }
        WishlistCustomer::where('customer_id', Auth::id())->where('product_id',$request->product_id)->delete();
    }
}
