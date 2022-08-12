<?php

namespace App\Http\Controllers\Returns\StampDuty;

use App\Http\Controllers\Controller;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Traits\ReturnCardReport;
use Illuminate\Http\Request;

class StampDutyReturnController extends Controller
{
    use ReturnCardReport;

    public function index(){

        $data = $this->returnCardReport(StampDutyReturn::class, 'stamp_duty', 'stamp_duty_return');

        return view('returns.stamp-duty.index', compact('data'));
    }

    public function show($returnId){
        $returnId = decrypt($returnId);
        $return = StampDutyReturn::findOrFail($returnId);
        return view('returns.stamp-duty.show', compact('return'));
    }

}
