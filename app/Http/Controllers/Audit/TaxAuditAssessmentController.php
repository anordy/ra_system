<?php


namespace App\Http\Controllers\Audit;

use App\Http\Controllers\Controller;
use App\Models\Verification\TaxVerification;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\TaxAudit\TaxAudit;

class TaxAuditAssessmentController extends Controller
{
    public function index()
    {
        return view('audit.assessment.index');
    }

    public function show($id){
        $audit = TaxAudit::with('assessment', 'officers')->find(decrypt($id));

        $return = $audit->taxReturn;
        if($return instanceof PetroleumReturn){
            $viewRender = "returns.petroleum.filing.details";
            return view('audit.approval.preview', compact('return', 'audit', 'viewRender'));
        } else if($return instanceof HotelReturn){
            $viewRender = "returns.hotel.details";
            return view('audit.approval.preview', compact('return', 'audit', 'viewRender'));
        }
    }
}
