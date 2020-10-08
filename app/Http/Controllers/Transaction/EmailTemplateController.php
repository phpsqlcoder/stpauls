<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Support\Facades\Validator;
use App\Helpers\ListingHelper;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\StPaulModel\TransactionStatus;
use App\StPaulModel\EmailTemplates;

use Auth;

class EmailTemplateController extends Controller
{
    private $searchFields = ['name'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listing = new ListingHelper();

        $templates = $listing->simple_search(EmailTemplates::class, $this->searchFields);

        // Simple search init data
        $filter = $listing->get_filter($this->searchFields);
        $searchType = 'simple_search';

        return view('admin.email-templates.index',compact('templates', 'filter', 'searchType'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $transactions = TransactionStatus::where('status','ACTIVE')->orderBy('name','asc')->get();

        return view('admin.email-templates.create',compact('transactions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        EmailTemplates::create([
            'name' => $request->name,
            'transactionstatus_id' => $request->transaction_id,
            'content' => $request->content,
            'status' => ($request->has('status') ? 'ACTIVE' : 'INACTIVE'),
            'user_id' => Auth::id()
        ]);

        return redirect(route('email-templates.index'))->with('success', 'Template has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EmailTemplates  $emailTemplates
     * @return \Illuminate\Http\Response
     */
    public function show(EmailTemplates $emailTemplates)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EmailTemplates  $emailTemplates
     * @return \Illuminate\Http\Response
     */
    public function edit(EmailTemplates $emailTemplates)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EmailTemplates  $emailTemplates
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmailTemplates $emailTemplates)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EmailTemplates  $emailTemplates
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmailTemplates $emailTemplates)
    {
        //
    }
}
