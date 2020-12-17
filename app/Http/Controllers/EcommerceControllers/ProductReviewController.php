<?php

namespace App\Http\Controllers\EcommerceControllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\EcommerceModel\ProductReview;
use App\EcommerceModel\Product;
use App\Http\Controllers\Controller;
use App\Helpers\ListingHelper;
use Auth;

class ProductReviewController extends Controller
{
    private $searchFields = ['product_name','rating'];

    public function index($param = null)
    {
        $listing = new ListingHelper('asc', 10, 'product_name');

        $reviews = $listing->simple_search(ProductReview::class, $this->searchFields);

        // Simple search init data
        $filter = $listing->get_filter($this->searchFields);
        $searchType = 'simple_search';

        return view('admin.product-review.index',compact('reviews', 'filter', 'searchType'));

    }

    public function store(Request $request)
    {
        $product = Product::find($request->product_id);

        ProductReview::create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'review' => $request->review,
            'rating' => $request->rating,           
            'customer_id' => Auth::id()           
        ]);
        
        return back();
    }

    public function single_approve(Request $request)
    {
        $review = ProductReview::findOrFail($request->reviews)->update([
            'is_approved' => 1,
            'approver' => auth()->user()->id,
            'approved_date' => now()
        ]);

        return back()->with('success', __('standard.product-review.single_approve_success'));
    }

    public function single_delete(Request $request)
    {
        $review = ProductReview::findOrFail($request->reviews);
        $review->update([ 'user_id' => auth()->user()->id ]);
        $review->delete();

        return back()->with('success', __('standard.product-review.single_delete_success'));
    }

    public function restore($id){
        $review = ProductReview::withTrashed()->find($id);
        $review->update(['user_id' => auth()->user()->id ]);
        $review->restore();

        return back()->with('success', __('standard.product-review.restore_success'));

    }
    
    public function multiple_delete(Request $request)
    {
        $reviews = explode("|",$request->reviews);

        foreach($reviews as $review){
            ProductReview::whereId($review)->update(['user_id' => Auth::id() ]);
            ProductReview::whereId($review)->delete();
        }

        return back()->with('success', __('standard.product-review.multiple_delete_success'));
    }

    public function multiple_approve(Request $request)
    {
        $reviews = explode("|",$request->reviews);

        foreach($reviews as $review){
            ProductReview::whereId($review)->update([
                'is_approved' => 1,
                'approver' => auth()->user()->id,
                'approved_date' => now()
            ]);
        }

        return back()->with('success', __('standard.product-review.multiple_approve_success'));
    }
}
