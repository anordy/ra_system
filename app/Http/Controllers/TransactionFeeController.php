<?php

namespace App\Http\Controllers;

use App\Models\TransactionFee;
use App\Http\Requests\StoreTransactionFeeRequest;
use App\Http\Requests\UpdateTransactionFeeRequest;
use Illuminate\Support\Facades\Gate;

class TransactionFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        if (!Gate::allows('setting-transaction-fees-view')) {
            abort(403);
        }

        return view('settings.transaction_fees');
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
     * @param  \App\Http\Requests\StoreTransactionFeeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTransactionFeeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TransactionFee  $transactionFee
     * @return \Illuminate\Http\Response
     */
    public function show(TransactionFee $transactionFee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TransactionFee  $transactionFee
     * @return \Illuminate\Http\Response
     */
    public function edit(TransactionFee $transactionFee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTransactionFeeRequest  $request
     * @param  \App\Models\TransactionFee  $transactionFee
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTransactionFeeRequest $request, TransactionFee $transactionFee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TransactionFee  $transactionFee
     * @return \Illuminate\Http\Response
     */
    public function destroy(TransactionFee $transactionFee)
    {
        //
    }
}
