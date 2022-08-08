<?php

namespace App\Http\Controllers\Returns\StampDuty;

use App\Http\Controllers\Controller;
use App\Models\Returns\StampDuty\StampDutyReturn;
use Illuminate\Http\Request;

class StampDutyReturnController extends Controller
{
    public function index(){
        return view('returns.stamp-duty.index');
    }

    public function show($returnId){
        $returnId = decrypt($returnId);
        $return = StampDutyReturn::findOrFail($returnId);
        return view('returns.stamp-duty.show', compact('return'));
    }

}
