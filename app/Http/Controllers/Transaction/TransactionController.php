<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Support\Facades\Validator;
use App\Helpers\ListingHelper;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StPaulModel\Transaction;

use Auth;


class TransactionController extends Controller
{
    private $searchFields = ['name','updated_at'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listing = new ListingHelper('desc', 10, 'updated_at');

        $transactions = $listing->simple_search(Transaction::class, $this->searchFields);

        // Simple search init data
        $filter = $listing->get_filter($this->searchFields);
        $searchType = 'simple_search';

        return view('admin.transaction-status.transaction_index',compact('transactions', 'filter', 'searchType'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.transaction-status.transaction_create');
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
                'name' => 'required|max:150|unique:transactions,name',
            ]  
        );

        Transaction::create([
            'name' => $request->name,
            'type' => $request->type,
            'status' => ($request->has('status') ? 'ACTIVE' : 'INACTIVE'),
            'user_id' => Auth::id()
        ]);

        return redirect(route('transactions.index'))->with('success',' Transaction has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transaction  $transactions
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transactions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transaction  $transactions
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);

        return view('admin.transaction-status.transaction_edit',compact('transaction'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transaction  $transactions
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Transaction::findOrFail($id)->update([
            'name' => $request->name,
            'type' => $request->type,
            'status' => ($request->has('status') ? 'ACTIVE' : 'INACTIVE'),
            'user_id' => Auth::id()
        ]);

        return redirect(route('transactions.index'))->with('Transaction has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaction  $transactions
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transactions)
    {
        //
    }

    public function update_status($id,$status)
    {
        Transaction::where('id',$id)->update([
            'status' => $status,
            'user_id' => Auth::id()
        ]);

        return back()->with('success', 'Transaction status has been changed to '.$status);
    }

    public function multiple_change_status(Request $request)
    {
        $transactions = explode("|", $request->transactions);

        foreach ($transactions as $transaction) {
            Transaction::where('status', '!=', $request->status)->whereId($transaction)->update([
                'status'  => $request->status,
                'user_id' => Auth::id()
            ]);
        }

        return back()->with('success', 'Selected transaction status has been changed to ',$request->status);
    }

    public function single_delete(Request $request)
    {
        $category = Transaction::findOrFail($request->transactions);
        $category->update([ 'user_id' => Auth::id() ]);
        $category->delete();

        return back()->with('success','Transaction has been deleted.');
    }

    public function restore($transaction)
    {
        Transaction::withTrashed()->find($transaction)->update(['user_id' => Auth::id() ]);
        Transaction::whereId($transaction)->restore();

        return back()->with('success','Transaction has been restored.');
    }

    public function multiple_delete(Request $request)
    {
        $transactions = explode("|",$request->transactions);

        foreach($transactions as $transaction){
            Transaction::whereId($transaction)->update(['user_id' => Auth::id() ]);
            Transaction::whereId($transaction)->delete();
        }

        return back()->with('success','Selected transaction(s) has been deleted.');
    }
}
