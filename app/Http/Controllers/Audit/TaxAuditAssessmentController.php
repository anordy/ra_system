<?php


namespace App\Http\Controllers\Audit;

use App\Http\Controllers\Controller;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\Verification\TaxVerification;

class TaxAuditAssessmentController extends Controller
{
    public function index()
    {
        return view('audit.assessment.index');
    }

    public function show($id){
        $audit = TaxVerification::with('assessment', 'officers')->find(decrypt($id));

        $return = $audit->taxReturn;
        if($return instanceof PetroleumReturn){
            $viewRender = "returns.petroleum.filing.details";
            return view('audit.approval.preview', compact('return', 'audit', 'viewRender'));
        }
    }
}
