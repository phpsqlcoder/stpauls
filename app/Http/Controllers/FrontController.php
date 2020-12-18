<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Requests\ContactUsRequest;

use App\Helpers\Webfocus\Setting;
use Illuminate\Support\Facades\Mail;
use App\Mail\InquiryAdminMail;
use App\Mail\InquiryMail;

use App\Page;
use Auth;

use App\EcommerceModel\BranchArea;
use App\EcommerceModel\Branch;
use App\TitleRequest;

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

    public function branches()
    {
        $page = new Page;
        $page->name = 'Branches';

        $areas = BranchArea::all();
        $featured = Branch::where('isfeatured',1)->first();

        return view('theme.'.env('FRONTEND_TEMPLATE').'.pages.branches', compact('page','areas','featured'));
    }

    public function request_title()
    {
        $page = new Page;
        $page->name = 'Request a Title';

        return view('theme.'.env('FRONTEND_TEMPLATE').'.pages.request-a-title', compact('page'));
    }

    public function store_title_request(Request $request)
    {
        Validator::make(
            $request->all(),[
                'firstname' => 'required|max:150',
                'lastname' => 'required|max:150',
                'email' => 'required|email|max:150|unique:title_requests,email',
                'title' => 'required',
            ],
            [
                'firstname.required' => 'The first name field is required.',
                'lastname.required' => 'The last name field is required.'
            ])->validate();

        TitleRequest::create([
            'email' => $request->email,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'mobile_no' => $request->mobileno,
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'message' => $request->message
        ]);

        return back()->with('success', 'Request for a title has been submitted.');
    }

    public function contact_us(ContactUsRequest $request)
    {
        $client = $request->all();

        Mail::to($client['email'])->send(new InquiryMail(Setting::info(), $client));

        $recipientEmails = EmailRecipient::email_list();
        foreach ($recipientEmails as $email) {
            Mail::to($email)->send(new InquiryAdminMail(Setting::info(), $client));
        }

        if (Mail::failures()) {
            return redirect()->back()->with('error', 'Failed to send inquiry. Please try again later.');
        }

        return redirect()->back()->with('success', 'Success! Your inquiry has been sent.');
    }
}
