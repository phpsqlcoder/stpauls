<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    public function privacy_policy()
    {   
        $page = new Page;
        $page->name = 'Privacy-Policy';
        
        return view('theme.'.env('FRONTEND_TEMPLATE').'.pages.privacy-policy', compact('page'));
    }
}
