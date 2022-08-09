<?php

namespace App\Http\Controllers\Verification;

use App\Http\Controllers\Controller;
use App\Models\Returns\LampSum\LampSumReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\Verification\TaxVerification;

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
        }
    }

    public function show($id)
    {
        $verification = TaxVerification::with('assessment', 'officers')->find(decrypt($id));

        $return = $verification->taxReturn;
        dd($return);
        if ($return instanceof PetroleumReturn) {
            $viewRender = 'returns.petroleum.filing.details';

            return view('verification.approval.preview', compact('return', 'verification', 'viewRender'));
        } elseif ($return instanceof LampSumReturn) {
            $viewRender = 'returns.lumpsum.details';

            return view('verification.approval.preview', compact('return', 'verification', 'viewRender'));
        }
    }
}
