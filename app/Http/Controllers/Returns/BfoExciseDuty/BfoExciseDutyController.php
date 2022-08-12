<?php

namespace App\Http\Controllers\Returns\BfoExciseDuty;

use App\Http\Controllers\Controller;
use App\Models\Returns\BFO\BfoReturn;
use App\Traits\ReturnCardReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BfoExciseDutyController extends Controller
{
    use ReturnCardReport;

    public function index()
    {
        $data = $this->returnCardReport(BfoReturn::class, 'bfo', 'bfo');

        return view('returns.excise-duty.bfo.index', compact('data'));
    }

    public function show($return_id)
    {
        $return = BfoReturn::query()->findOrFail(decrypt($return_id));
        return view('returns.excise-duty.bfo.show', compact('return', 'return_id'));
    }
}
