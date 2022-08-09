<?php


namespace App\Http\Controllers\Investigation;

use App\Http\Controllers\Controller;
use App\Models\Investigation\TaxInvestigation;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;

class TaxInvestigationAssessmentController extends Controller
{
    public function index()
    {
        return view('investigation.assessment.index');
    }

    public function show($id){
        $investigation = TaxInvestigation::with('assessment', 'officers')->find(decrypt($id));
        $viewRender = "";
        return view('investigation.approval.preview', compact('investigation', 'viewRender'));

    }
}
