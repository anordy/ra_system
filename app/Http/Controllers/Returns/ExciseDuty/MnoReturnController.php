<?php

namespace App\Http\Controllers\Returns\ExciseDuty;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessStatus;
use App\Models\BusinessTaxType;
use App\Models\Returns\ExciseDuty\MnoPenalty;
use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Models\TaxType;
use App\Traits\ReturnCardReport;
use App\Traits\ReturnSummaryCardTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $return = MnoReturn::find(decrypt($id));

        return view('returns.excise-duty.mno.show', compact('return'));
    }
}
