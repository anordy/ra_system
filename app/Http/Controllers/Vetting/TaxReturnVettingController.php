<?php

namespace App\Http\Controllers\Vetting;

use App\Models\Returns\BFO\BfoReturn;
use App\Models\Returns\TaxReturn;
use App\Http\Controllers\Controller;
use App\Models\Returns\EmTransactionReturn;
use Illuminate\Support\Facades\Gate;
use App\Models\Returns\Vat\VatReturn;
use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\MmTransferReturn;
use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\Returns\StampDuty\StampDutyReturn;

class TaxReturnVettingController extends Controller
{
    public function index() {
        if (!Gate::allows('tax-returns-vetting-view')) {
            abort(403);
        }

        return view('vetting.submitted');
    }

    public function corrected() {
        if (!Gate::allows('tax-returns-vetting-view')) {
            abort(403);
        }

        return view('vetting.corrected');
    }

    public function onCorrection() {
        if (!Gate::allows('tax-returns-vetting-view')) {
            abort(403);
        }

        return view('vetting.on-correction');
    }

    public function vetted() {
        if (!Gate::allows('tax-returns-vetting-view')) {
            abort(403);
        }

        return view('vetting.vetted');
    }

    public function show($return_id) {

        if (!Gate::allows('tax-returns-vetting-view')) {
            abort(403);
        }

        $tax_return = TaxReturn::findOrFail(decrypt($return_id));

        $return = $tax_return->return;

        $returnHistories = $tax_return->editReturnHistories;


        if ($return instanceof PetroleumReturn) {
            $viewRender = 'returns.petroleum.filing.details';

        } elseif ($return instanceof LumpSumReturn) {
            $viewRender = 'returns.lump-sum.details';

        } elseif ($return instanceof HotelReturn) {
            $viewRender = 'returns.hotel.details';

        } elseif ($return instanceof StampDutyReturn) {
            $viewRender = 'returns.stamp-duty.details';

        } elseif ($return instanceof VatReturn) {
            $viewRender = 'returns.vat_returns.details';

        } elseif ($return instanceof MmTransferReturn) {
            $viewRender = 'returns.excise-duty.mobile-money-transfer.details';

        } elseif ($return instanceof PortReturn) {
            $viewRender = 'returns.port.details';

        } elseif ($return instanceof MnoReturn) {
            $viewRender = 'returns.excise-duty.mno.details';

        }
        elseif ($return instanceof BfoReturn) {
            $viewRender = 'returns.excise-duty.bfo.details';

        } elseif ($return instanceof EmTransactionReturn) {
            $viewRender = 'returns.excise-duty.em-transaction.details';

        } else {
            abort(404);
        }

        return view('vetting.show', compact('return', 'viewRender', 'tax_return', 'returnHistories'));
    }
}
