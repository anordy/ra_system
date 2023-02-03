<?php

namespace App\Http\Controllers\Returns\ExciseDuty;

use App\Http\Controllers\Controller;
use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Traits\ReturnCardReport;
use App\Traits\ReturnSummaryCardTrait;

class MnoReturnController extends Controller
{
    use ReturnCardReport,ReturnSummaryCardTrait;

    public function index()
    {
        $cardOne   = 'returns.excise-duty.mno-card-one';
        $cardTwo   = 'returns.excise-duty.mno-card-two';
        $tableName = 'returns.excise-duty.mno-returns-table';

        return view('returns.excise-duty.mno.index', compact('cardTwo', 'cardOne', 'tableName'));
    }

    public function show($id)
    {
        $return = MnoReturn::findOrFail(decrypt($id));
        $return->penalties = $return->penalties->concat($return->tax_return->penalties)->sortBy('tax_amount');
        return view('returns.excise-duty.mno.show', compact('return'));
    }
}
