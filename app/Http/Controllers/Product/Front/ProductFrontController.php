<?php

namespace App\Http\Controllers\Product\Front;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use App\EcommerceModel\ProductCategory;
use App\EcommerceModel\ProductReview;
use App\EcommerceModel\ProductPhoto;
use App\EcommerceModel\Product;

use App\Page;
use DB;


class ProductFrontController extends Controller
{
    public function show($slug)
    {
        //$sales_history = 0;
        if(Auth::guest()) {
            $product = Product::whereSlug($slug)->where('status', 'PUBLISHED')->first();
        } else {
            $product = Product::whereSlug($slug)->where('status', '!=', 'UNEDITABLE')->first(); 
            //$sales_history = $this->checkIfUserPurchasedTheItem($product->id);      
          
        }

        $categories = 
            ProductCategory::where('parent_id',0)
            ->where('status','PUBLISHED')
            ->where('id','<>',$product->category_id)
            ->get();

        $qry_reviews = 
            ProductReview::where('product_id',$product->id)
            ->where('is_approved',1);

        $reviews = $qry_reviews->get();
        $reviews_count = $qry_reviews->count();
        //
        
        $page = $product;
        if (empty($product)) {
            abort(404);
        }


        return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.product.profile',compact('product', 'page','categories','reviews','reviews_count'));
    }

    // public function checkIfUserPurchasedTheItem($id){

    //     $rs = DB::select("SELECT d.*                  
    //                 FROM `ecommerce_sales_details` d 
    //                 left join ecommerce_sales_headers h on h.id=d.sales_header_id 
    //                 where d.product_id='".$id."' and h.user_id='".Auth::id()."'
    //                  ");

    //     if (empty($rs)) {
    //         return 0;
    //     }else{
    //         return 1;
    //     }

    // }
    
    public function product_list(Request $request, $slug)
    {
        $category = ProductCategory::where('slug',$slug)->first();

        $page = new Page();
        $page = $category;
        $pageLimit = 40;
        $maxPrice   = 1000;

        $products = Product::where('category_id',$category->id)->where('status','PUBLISHED')->paginate(10);
        $categories = ProductCategory::where('parent_id',0)->where('status','PUBLISHED')->where('id','<>',$category->id)->get();

        if($request->has('search')){

            $products = Product::where('category_id',$category->id)->whereStatus('PUBLISHED');

            if(!empty($request->sort)){            
                if($request->sort == 'Price low to high'){
                    $products = $products->orderBy('price','asc');
                }
                elseif($request->sort == 'Price high to low'){
                    $products = $products->orderBy('price','desc');
                }
            }

            if(!empty($request->limit)){ 
                if($request->limit=='All')
                    $pageLimit = 100000000;      
                else
                    $pageLimit = $request->limit;
            }

            if(!empty($request->price)){
                $price = explode(';',$request->price);
                $products = $products->whereBetween('price',[$price[0],$price[1]]);

                $maxPrice = $price[1];
            }

            $total_product = $products->count();
            $products = $products->orderBy('name','asc')->paginate($pageLimit);
        }
        else{

            $qry = Product::where('category_id',$category->id)->where('status','PUBLISHED');
            $products = $qry->paginate($pageLimit);
            $total_product = $qry->count();
        
        }

        return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.product.product-list',compact('products','page','categories','total_product','maxPrice','category','request'));
    }

    public function search_product(Request $request)
    {
        $page = new Page();
        $page->name = 'Products';
        $pageLimit  = 40;
        $maxPrice   = 1000;

        $categories = ProductCategory::where('parent_id',0)->where('status','PUBLISHED')->get();

        if(!empty($request->searchtxt)){
            $searchtxt = $request->searchtxt;
        } else {
            $searchtxt = "";
        }

        if($request->has('search')){

            $products = Product::join('product_additional_info','products.id','=','product_additional_info.product_id')->select('products.*','product_additional_info.authors')->whereStatus('PUBLISHED');

            if(!empty($request->searchtxt)){  
                $searchtxt = $request->searchtxt;      
                $products = $products->where(function($query) use ($searchtxt){
                    $query->where('products.name','like','%'.$searchtxt.'%')
                        ->orWhere('products.description','like','%'.$searchtxt.'%')
                        ->orWhere('authors','like','%'.$searchtxt.'%');
                    });
            }

            if(!empty($request->sort)){            
                if($request->sort == 'Price low to high'){
                    $products = $products->orderBy('price','asc');
                }
                elseif($request->sort == 'Price high to low'){
                    $products = $products->orderBy('price','desc');
                }
            }

            if(!empty($request->limit)){ 
                if($request->limit=='All')
                    $pageLimit = 100000000;      
                else
                    $pageLimit = $request->limit;
            }

            if(!empty($request->price)){
                $price = explode(';',$request->price);
                $products = $products->whereBetween('price',[$price[0],$price[1]]);

                $maxPrice = $price[1];
            }

            
            $total_product = $products->count();
            $products = $products->orderBy('name','asc')->paginate($pageLimit);
        }
        else{
        
        }

        return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.product.product-search',compact('products','categories','total_product','page','request','searchtxt','maxPrice'));

    }
}
