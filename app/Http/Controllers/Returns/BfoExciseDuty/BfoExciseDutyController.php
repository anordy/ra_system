<?php

namespace App\Http\Controllers\Returns\BfoExciseDuty;

use App\Http\Controllers\Controller;
use App\Models\Returns\BFO\BFOReturn;
use Illuminate\Http\Request;

class BfoExciseDutyController extends Controller
{
    public function index()
    {
        return view('returns.excise_duty.bfo.index');
    }

    public function show($return_id)
    {
        $return = BFOReturn::query()->findOrFail(decrypt($return_id));
        return view('returns.excise_duty.bfo.show', compact('return', 'return_id'));
    }
}
