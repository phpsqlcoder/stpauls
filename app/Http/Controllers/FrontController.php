<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\EcommerceModel\ProductCategory;
use App\EcommerceModel\Product;
use App\Page;

use Auth;

class FrontController extends Controller
{

    public function home()
    {
        return $this->page('home');
    }

    public function page($slug)
    {
        if(Auth::guest()) {
            $page = Page::where('slug', $slug)->where('status', 'PUBLISHED')->first();
        } else {
            $page = Page::where('slug', $slug)->first();
        }

        if($page == null) {
            abort(404);
        }

        $breadcrumb = $this->breadcrumb($page);

        if (!empty($page->template)) {

            return view('theme.'.env('FRONTEND_TEMPLATE').'.pages.'.$page->template, compact('page'))->withShortcodes();
        }

        $parentPage = null;
        if ($page->has_parent_page() || $page->has_sub_pages())
        {
            if ($page->has_parent_page()) {
                $parentPage = $page->parent_page;
                while($parentPage->has_parent_page()) {
                    $parentPage = $parentPage->parent_page;
                }
            } else {
                $parentPage = $page;
            }
        }

        return view('theme.'.env('FRONTEND_TEMPLATE').'.page', compact('page', 'parentPage','breadcrumb'))->withShortcodes();
    }

    public function breadcrumb($page)
    {
        return [
            'home' => url('/'),
            $page->name => url('/').'/'.$page->slug
        ];
    }

    public function search_product(Request $request)
    {
        // $searchFields = ['name', 'label', 'contents'];
        // $listing = new ListingHelper();

        // $searchPages = $listing->simple_search(Page::class, $searchFields);

        // $filter = $listing->get_filter($searchFields);

        $page = new Page();
        $page->name = 'Product Search';

        $keyword = $_GET['product'];

        $categories = ProductCategory::where('parent_id',0)->where('status','PUBLISHED')->orderBy('name','asc')->get();
        $qry_search = Product::where('name','like','%'.$keyword.'%');
        $products   = $qry_search->paginate(5);
        $count      = $qry_search->count();


        return view('theme.'.env('FRONTEND_TEMPLATE').'.pages.product-search', compact('page','categories','products','count','keyword'));
    }

}
