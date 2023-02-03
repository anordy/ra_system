<?php

namespace App\Http\Controllers\Returns\BfoExciseDuty;

use App\Http\Controllers\Controller;
use App\Models\Returns\BFO\BfoReturn;
use App\Traits\ReturnCardReport;
use App\Traits\ReturnSummaryCardTrait;
use Illuminate\Support\Facades\Gate;

class BfoExciseDutyController extends Controller
{
    use ReturnCardReport,ReturnSummaryCardTrait;

    public function index()
    {
        if (!Gate::allows('return-bfo-excise-duty-return-view')) {
            abort(403);
        }
        $cardOne   = 'returns.bfo-excise-duty.bfo-card-one';
        $cardTwo   = 'returns.bfo-excise-duty.bfo-card-two';
        $tableName = 'returns.bfo-excise-duty.bfo-excise-duty-table';

        return view('returns.excise-duty.bfo.index', compact('cardOne', 'cardTwo', 'tableName'));
    }

    public function show($return_id)
    {
        $return = BfoReturn::query()->findOrFail(decrypt($return_id));
        $return->penalties = $return->penalties->concat($return->tax_return->penalties)->sortBy('tax_amount');

        return view('returns.excise-duty.bfo.show', compact('return', 'return_id'));
    }
}
