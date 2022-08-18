<?php

namespace App\Http\Controllers\Returns\ExciseDuty;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessStatus;
use App\Models\BusinessTaxType;
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

        $data = $this->returnCardReport(MnoReturn::class, 'mno', 'mno');

        $vars = $this->getSummaryData(MnoReturn::query());

        return view('returns.excise-duty.mno.index', compact('vars', 'data'));
    }

    public function show($id)
    {
        $return = MnoReturn::find(decrypt($id));
        return view('returns.excise-duty.mno.show', compact('return'));
    }
}
