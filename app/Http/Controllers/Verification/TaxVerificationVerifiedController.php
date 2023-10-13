<?php

namespace App\Http\Controllers\Verification;

use App\Http\Controllers\Controller;
use App\Models\Returns\BFO\BfoReturn;
use App\Models\Returns\EmTransactionReturn;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Models\Returns\MmTransferReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\Returns\Vat\VatReturn;
use App\Models\Verification\TaxVerification;
use Illuminate\Support\Facades\Gate;

class TaxVerificationVerifiedController extends Controller
{
    public function index()
    {
        if (!Gate::allows('verification-approved-view')) {
            abort(403);
        }
        $tableName ='verification.verification-verified-table';

        return view('verification.verified.index', compact('tableName'));
    }

    public function show($id)
    {
        if (!Gate::allows('verification-view')) {
            abort(403);
        }
        $verification = TaxVerification::with('assessment', 'officers')->findOrFail(decrypt($id));

        $return = $verification->taxReturn;
        if ($return instanceof PetroleumReturn) {
            $viewRender = 'returns.petroleum.filing.details';

            return view('verification.approval.preview', compact('return', 'verification', 'viewRender'));
        } elseif ($return instanceof LumpSumReturn) {
            $viewRender = 'returns.lump-sum.details';

            return view('verification.approval.preview', compact('return', 'verification', 'viewRender'));
        } elseif ($return instanceof HotelReturn) {
            $viewRender = 'returns.hotel.details';

            return view('verification.approval.preview', compact('return', 'verification', 'viewRender'));
        } elseif ($return instanceof StampDutyReturn) {
            $viewRender = 'returns.stamp-duty.details';

            return view('verification.approval.preview', compact('return', 'verification', 'viewRender'));
        } elseif ($return instanceof VatReturn) {
            $viewRender = 'returns.vat_returns.details';

            return view('verification.approval.preview', compact('return', 'verification', 'viewRender'));
        } elseif ($return instanceof MmTransferReturn) {
            $viewRender = 'returns.excise-duty.mobile-money-transfer.details';

            return view('verification.approval.preview', compact('return', 'verification', 'viewRender'));
        } elseif ($return instanceof EmTransactionReturn) {
            $viewRender = 'returns.em-transaction.details';

            return view('verification.approval.preview', compact('return', 'verification', 'viewRender'));
        } elseif ($return instanceof BfoReturn) {
            $viewRender = 'returns.excise-duty.bfo.details';

            return view('verification.approval.preview', compact('return', 'verification', 'viewRender'));
        } elseif ($return instanceof PortReturn) {
            $viewRender = 'returns.port.details';

            return view('verification.approval.preview', compact('return', 'verification', 'viewRender'));
        }
    }
}
