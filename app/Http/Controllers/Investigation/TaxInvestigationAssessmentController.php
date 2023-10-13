<?php


namespace App\Http\Controllers\Investigation;

use App\Http\Controllers\Controller;
use App\Models\Investigation\TaxInvestigation;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;
use Illuminate\Support\Facades\Gate;

class TaxInvestigationAssessmentController extends Controller
{
    public function index()
    {
        if (!Gate::allows('tax-investigation-assessment-view')) {
            abort(403);
        }
        return view('investigation.assessment.index');
    }

    public function show($id){
        if (!Gate::allows('tax-investigation-view')) {
            abort(403);
        }
        $investigation = TaxInvestigation::with('assessment', 'officers')->findOrFail(decrypt($id));
        $viewRender = "";
        return view('investigation.approval.preview', compact('investigation', 'viewRender'));

    }
}
