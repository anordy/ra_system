<?php

namespace App\Http\Controllers\Returns\Vat;

use App\Http\Controllers\Controller;
use App\Models\BusinessStatus;
use App\Models\Returns\Vat\VatReturn;
use App\Models\Returns\Vat\VatReturnPenalty;
use App\Traits\PaymentsTrait;
use App\Traits\ReturnCardReport;
use App\Traits\ReturnSummaryCardTrait;

class VatReturnController extends Controller
{
    use ReturnCardReport, PaymentsTrait,ReturnSummaryCardTrait;

    public function index()
    {
        $paidData = $this->returnCardReportForPaidReturns(VatReturn::class, VatReturn::getTableName(), VatReturnPenalty::getTableName());

        $unpaidData = $this->returnCardReportForUnpaidReturns(VatReturn::class, VatReturn::getTableName(), VatReturnPenalty::getTableName());

        $vars = $this->getSummaryData(VatReturn::query());

        return view('returns.vat_returns.index', compact('vars', 'paidData', 'unpaidData'));
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
