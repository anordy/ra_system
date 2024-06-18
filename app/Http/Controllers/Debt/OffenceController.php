<?php

namespace App\Http\Controllers\Debt;

use App\Http\Controllers\Controller;
use App\Models\Installment\InstallmentItem;
use App\Models\Offence\Offence;
use App\Models\ZmBill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OffenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('debt-management-offence-view')) {
            abort(403);
        }
        return  view('debts.offence.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('debt-management-offence-view')) {
            abort(403);
        }
        return  view('debts.offence.create');
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
     * @param  \App\Models\Offence\Offence  $offence
     * @return \Illuminate\Http\Response
     */
    public function show( $offenceId)
    {
        if (!Gate::allows('debt-management-offence-view')) {
            abort(403);
        }
       try{
           $offence = Offence::with('taxTypes')->find(decrypt($offenceId));
           $bill = ZmBill::where('billable_id',decrypt($offenceId))
                        ->where('billable_type',get_class($offence))
//                        ->where('status',Offence::PENDING)
                        ->first();


            return  view('debts.offence.show',compact('offence','bill'));
       }catch (\Exception $e){
        dd('s',$e);
       }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Offence\Offence  $offence
     * @return \Illuminate\Http\Response
     */
    public function edit(Offence $offence)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Offence\Offence  $offence
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Offence $offence)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Offence\Offence  $offence
     * @return \Illuminate\Http\Response
     */
    public function destroy(Offence $offence)
    {
        //
    }
}
