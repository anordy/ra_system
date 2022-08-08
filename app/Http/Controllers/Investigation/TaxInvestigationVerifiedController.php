<?php


namespace App\Http\Controllers\Investigation;

use App\Http\Controllers\Controller;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\Verification\TaxVerification;

class TaxInvestigationVerifiedController extends Controller
{
    public function index()
    {
        return view('investigation.verified.index');
    }

    public function show($id)
    {
        $investigation = TaxVerification::with('assessment', 'officers')->find(decrypt($id));

        $return = $investigation->taxReturn;
        if ($return instanceof PetroleumReturn) {
            $viewRender = "returns.petroleum.filing.details";
            return view('investigation.approval.preview', compact('return', 'investigation', 'viewRender'));
        }
    }
}
