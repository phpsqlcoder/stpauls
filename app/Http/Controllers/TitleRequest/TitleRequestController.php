<?php

namespace App\Http\Controllers\TitleRequest;

use App\TitleRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Helpers\ListingHelper;

class TitleRequestController extends Controller
{
    private $searchFields = ['title'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listing = new ListingHelper('desc', 10, 'updated_at');

        $requests = $listing->simple_search(TitleRequest::class, $this->searchFields);

        // Simple search init data
        $filter = $listing->get_filter($this->searchFields);
        $searchType = 'simple_search';

        return view('admin.title-request.index',compact('requests', 'filter', 'searchType'));
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
     * @param  \App\TitleRequest  $titleRequest
     * @return \Illuminate\Http\Response
     */
    public function show(TitleRequest $titleRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TitleRequest  $titleRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(TitleRequest $titleRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TitleRequest  $titleRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TitleRequest $titleRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TitleRequest  $titleRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(TitleRequest $titleRequest)
    {
        //
    }
}
