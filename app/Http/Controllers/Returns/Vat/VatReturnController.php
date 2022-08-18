<?php

namespace App\Http\Controllers\Returns\Vat;

use App\Http\Controllers\Controller;
use App\Models\BusinessStatus;
use App\Models\Returns\Vat\VatReturn;
use App\Traits\PaymentsTrait;
use App\Traits\ReturnCardReport;
use App\Traits\ReturnSummaryCardTrait;

class VatReturnController extends Controller
{
    use ReturnCardReport, PaymentsTrait,ReturnSummaryCardTrait;

    public function index()
    {
        $data = $this->returnCardReport(VatReturn::class, 'vat', 'vat_return');

        $vars = $this->getSummaryData(VatReturn::query());

        return view('returns.vat_returns.index', compact('vars', 'data'));
    }
    public function show($id)
    {
        $return = VatReturn::query()->findOrFail(decrypt($id));
        $egovernmentFee = $this->getTransactionFee(
            $return->total_amount_due_with_penalties,
            $return->currency,
            2300
        );
        return view('returns.vat_returns.show', compact('return', 'id', 'egovernmentFee'));
    }
}
