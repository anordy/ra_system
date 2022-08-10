<?php

namespace App\Http\Controllers\Verification;

use App\Http\Controllers\Controller;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\LampSum\LampSumReturn;
use App\Models\Returns\MmTransferReturn;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\Returns\Vat\VatReturn;
use App\Models\Returns\BFO\BFOReturn;
use App\Models\Returns\EmTransactionReturn;
use App\Models\Verification\TaxVerification;
use App\Models\Returns\HotelReturns\HotelReturn;

class TaxVerificationApprovalController extends Controller
{
    public function index()
    {
        return view('verification.approval.index');
    }

    public function edit($id)
    {
        $verification = TaxVerification::with('assessment', 'officers')->find(decrypt($id));

        $return = $verification->taxReturn;
        if ($return instanceof PetroleumReturn) {
            $viewRender = 'returns.petroleum.filing.details';

            return view('verification.approval.approval', compact('return', 'verification', 'viewRender'));
        } elseif ($return instanceof LampSumReturn) {
            $viewRender = 'returns.lumpsum.details';

            return view('verification.approval.approval', compact('return', 'verification', 'viewRender'));
        } elseif ($return instanceof HotelReturn) {
            $viewRender = 'returns.hotel.details';

            return view('verification.approval.approval', compact('return', 'verification', 'viewRender'));
        } elseif ($return instanceof StampDutyReturn) {
            $viewRender = 'returns.stamp-duty.details';

            return view('verification.approval.approval', compact('return', 'verification', 'viewRender'));
        } elseif ($return instanceof VatReturn) {
            $viewRender = 'returns.vat_returns.details';

            return view('verification.approval.approval', compact('return', 'verification', 'viewRender'));
        } elseif ($return instanceof BFOReturn) {
            $viewRender = 'returns.excise-duty.bfo.details';

            return view('verification.approval.approval', compact('return', 'verification', 'viewRender'));
        } elseif ($return instanceof EmTransactionReturn) {
            $viewRender = 'returns.excise-duty.em-transaction.details';

            return view('verification.approval.approval', compact('return', 'verification', 'viewRender'));
        } elseif ($return instanceof MmTransferReturn) {
            $viewRender = 'returns.excise-duty.mm-transfer.details';

            return view('verification.approval.approval', compact('return', 'verification', 'viewRender'));
        } elseif ($return instanceof PortReturn) {
            $viewRender = 'returns.port.details';
            return view('verification.approval.approval', compact('return', 'verification', 'viewRender'));
        }
    }

    public function show($id)
    {
        $verification = TaxVerification::with('assessment', 'officers')->find(decrypt($id));

        $return = $verification->taxReturn;
        if ($return instanceof PetroleumReturn) {
            $viewRender = 'returns.petroleum.filing.details';

            return view('verification.approval.preview', compact('return', 'verification', 'viewRender'));
        } elseif ($return instanceof LampSumReturn) {
            $viewRender = 'returns.lumpsum.details';

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
        } elseif ($return instanceof PortReturn) {
            $viewRender = 'returns.port.details';
            return view('verification.approval.preview', compact('return', 'verification', 'viewRender'));
        }
    }
}
