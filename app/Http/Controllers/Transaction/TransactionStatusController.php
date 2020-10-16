<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Support\Facades\Validator;
use App\Helpers\ListingHelper;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StPaulModel\TransactionStatus;
use App\StPaulModel\Transaction;

use Auth;


class TransactionStatusController extends Controller
{
    private $searchFields = ['name'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listing = new ListingHelper('desc', 10, 'updated_at');

        $transactions = $listing->simple_search(TransactionStatus::class, $this->searchFields);

        // Simple search init data
        $filter = $listing->get_filter($this->searchFields);
        $searchType = 'simple_search';

        $counter = 
            Transaction::whereNotIn('name',function($query){
                $query->select('name')->from('transaction_status');
            })->where('status','ACTIVE')->count();

        return view('admin.transaction-status.index',compact('transactions', 'counter', 'filter', 'searchType'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $transactions = 
            Transaction::whereNotIn('name',function($query){
                $query->select('name')->from('transaction_status');
            })->where('status','ACTIVE')->orderBy('name','asc')->get();

        return view('admin.transaction-status.create',compact('transactions'));
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
                'transaction_name' => 'required',
                'subject' => 'required|max:150',
            ]  
        );

        $transaction = Transaction::find($request->transaction_name);

        TransactionStatus::create([
            'name' => $transaction->name,
            'transaction_id' => $transaction->id,
            'subject' => $request->subject,
            'content' => $request->content,
            'status' => ($request->has('status') ? 'ACTIVE' : 'INACTIVE'),
            'user_id' => Auth::id()
        ]);

        return redirect(route('transaction-status.index'))->with('success',' Transaction status has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TransactionStatus  $transactions
     * @return \Illuminate\Http\Response
     */
    public function show(TransactionStatus $transactions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TransactionStatus  $transactions
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $transaction = TransactionStatus::findOrFail($id);

        return view('admin.transaction-status.edit',compact('transaction'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TransactionStatus  $transactions
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        TransactionStatus::findOrFail($id)->update([
            'name' => $request->name,
            'status' => ($request->has('status') ? 'ACTIVE' : 'INACTIVE'),
            'user_id' => Auth::id()
        ]);

        return redirect(route('transaction-status.index'))->with('Transaction status has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TransactionStatus  $transactions
     * @return \Illuminate\Http\Response
     */
    public function destroy(TransactionStatus $transactions)
    {
        //
    }

    public function single_delete(Request $request)
    {
        $category = TransactionStatus::findOrFail($request->transactions);
        $category->update([ 'user_id' => Auth::id() ]);
        $category->delete();

        return back()->with('success','Transaction status has been deleted.');
    }

    public function restore($transaction){
        TransactionStatus::withTrashed()->find($transaction)->update(['user_id' => Auth::id() ]);
        TransactionStatus::whereId($transaction)->restore();

        return back()->with('success','Transaction status has been restored.');
    }

    public function update_status($id,$status)
    {
        TransactionStatus::where('id',$id)->update([
            'status' => $status,
            'user_id' => Auth::id()
        ]);

        return back()->with('success', 'Transaction status has been changed to '.$status);
    }

    public function multiple_change_status(Request $request)
    {
        $transactions = explode("|", $request->transactions);

        foreach ($transactions as $transaction) {
            TransactionStatus::where('status', '!=', $request->status)->whereId($transaction)->update([
                'status'  => $request->status,
                'user_id' => Auth::id()
            ]);
        }

        return back()->with('success', 'Selected transaction status has been changed to ',$request->status);
    }

    public function multiple_delete(Request $request)
    {
        $transactions = explode("|",$request->transactions);

        foreach($transactions as $transaction){
            TransactionStatus::whereId($transaction)->update(['user_id' => Auth::id() ]);
            TransactionStatus::whereId($transaction)->delete();
        }

        return back()->with('success','Selected transaction status has been deleted.');
    }
}
