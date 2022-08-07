<?php


namespace App\Http\Controllers\Verification;

use App\Http\Controllers\Controller;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\Verification\TaxVerification;

class TaxVerificationVerifiedController extends Controller
{
    public function index()
    {
        return view('verification.verified.index');
    }

    public function edit($id)
    {

        $verification = TaxVerification::find(decrypt($id));
        $return = $verification->taxReturn;
        if ($return instanceof PetroleumReturn) {
            return view('verification.petroleum.show', compact('return', 'verification'));
        }
    }
    public function show($id)
    {
        $verification = TaxVerification::with('assessment', 'officers')->find(decrypt($id));

        $return = $verification->taxReturn;
        if ($return instanceof PetroleumReturn) {
            $viewRender = "returns.petroleum.filing.details";
            return view('verification.approval.preview', compact('return', 'verification', 'viewRender'));
        }
    }
}
