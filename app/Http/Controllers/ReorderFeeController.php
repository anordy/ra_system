<?php

namespace App\Http\Controllers;

use App\Models\MvrReorderPlateNumberFee;
use App\Http\Requests\StoreMvrReorderPlateNumberFeeRequest;
use App\Http\Requests\UpdateMvrReorderPlateNumberFeeRequest;
use Illuminate\Support\Facades\Gate;

class ReorderFeeController extends Controller
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
        return view('settings.reorder_fees');
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
     * @param  \App\Http\Requests\StoreMvrReorderPlateNumberFeeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMvrReorderPlateNumberFeeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MvrReorderPlateNumberFee  $MvrReorderPlateNumberFee
     * @return \Illuminate\Http\Response
     */
    public function show(MvrReorderPlateNumberFee $MvrReorderPlateNumberFee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MvrReorderPlateNumberFee  $MvrReorderPlateNumberFee
     * @return \Illuminate\Http\Response
     */
    public function edit(MvrReorderPlateNumberFee $MvrReorderPlateNumberFee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMvrReorderPlateNumberFeeRequest  $request
     * @param  \App\Models\MvrReorderPlateNumberFee  $MvrReorderPlateNumberFee
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMvrReorderPlateNumberFeeRequest $request, MvrReorderPlateNumberFee $MvrReorderPlateNumberFee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MvrReorderPlateNumberFee  $MvrReorderPlateNumberFee
     * @return \Illuminate\Http\Response
     */
    public function destroy(MvrReorderPlateNumberFee $MvrReorderPlateNumberFee)
    {
        //
    }
}
