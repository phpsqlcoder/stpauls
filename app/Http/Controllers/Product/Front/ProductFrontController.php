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
        $product = Product::whereSlug($slug)->where('status', 'PUBLISHED')->first();

        $categories = 
            ProductCategory::where('parent_id',0)
            ->where('status','PUBLISHED')
            ->where('id','<>',$product->category_id)
            ->get();

        $reviews_count = ProductReview::where('product_id',$product->id)->where('is_approved',1)->count();
        $reviews = ProductReview::where('product_id',$product->id)->where('is_approved',1)->paginate(10);

        $page = $product;
        if (empty($product)) {
            abort(404);
        }

        if(isset($_GET['page'])){
            $tab = 'reviews';
        } else {
            $tab = 'details';
        }

        return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.product.profile',compact('product', 'page','categories','reviews','reviews_count','tab'));
    }
    
    public function product_list(Request $request, $slug)
    {
        $category = ProductCategory::where('slug',$slug)->first();

        $page = new Page();
        $page = $category;
        $pageLimit = 40;

        $subcategories = [];
        array_push($subcategories,$category->id);

        foreach($category->child_categories as $child){
            array_push($subcategories,$child->id);

            foreach($child->child_categories as $sub){
                array_push($subcategories,$sub->id);
            }
        }

        $products = Product::whereIn('category_id',$subcategories)->where('status','PUBLISHED');

        $maxPrice = $products->max('price');
        $minPrice = 1;

        $categories = ProductCategory::where('parent_id',0)->where('status','PUBLISHED')->where('id','<>',$category->id)->get();

        if($request->has('search')){

            if(!empty($request->rating)){
                $rating = $request->rating;
                $products->whereIn('id',function($query) use($rating){
                    $query->select('product_id')->from('ecommerce_product_review')
                    ->where('rating',$rating)
                    ->where('is_approved',1);
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

                $productMaxPrice = $maxPrice;
                $maxPrice = $price[1];
                $minPrice = $price[0];

            }

            $total_product = $products->count();
            $products = $products->orderBy('updated_at','desc')->paginate($pageLimit);
        }
        else{
            $productMaxPrice = $maxPrice;
            $minPrice = $minPrice;
            $total_product = $products->count();
            $products = $products->orderBy('updated_at','desc')->paginate($pageLimit);
        }

        return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.product.product-list',compact('products','page','categories','total_product','maxPrice','minPrice','productMaxPrice','category','request'));
    }

    public function search_product(Request $request)
    {
        $keyword = $request->keyword;

        $products = 
            Product::join('product_additional_info','products.id','=','product_additional_info.product_id')
                ->select('products.*','product_additional_info.authors')
                ->whereStatus('PUBLISHED')
                ->where(function($query) use ($keyword){
                    $query->where('products.name','like','%'.$keyword.'%')
                        ->orWhere('products.code','like','%'.$keyword.'%')
                        ->orWhere('products.description','like','%'.$keyword.'%')
                        ->orWhere('authors','like','%'.$keyword.'%');
                    })
                ->get();

        return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.product.product-search',compact('products','keyword'));

    }
}
