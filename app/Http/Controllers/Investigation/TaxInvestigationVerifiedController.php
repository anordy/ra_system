<?php


namespace App\Http\Controllers\Investigation;

use App\Http\Controllers\Controller;
use App\Models\Investigation\TaxInvestigation;
use App\Models\TaxAssessments\TaxAssessment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class TaxInvestigationVerifiedController extends Controller
{
    public function index()
    {
        if (!Gate::allows('tax-investigation-approved-view')) {
            abort(403);
        }
        return view('investigation.verified.index');
    }

    public function show($id)
    {
        if (!Gate::allows('tax-investigation-view')) {
            abort(403);
        }
        try {
            $investigation = TaxInvestigation::with('assessment', 'officers')->findOrFail(decrypt($id));
            $taxAssessments = TaxAssessment::where('assessment_id', $investigation->id)
                ->where('assessment_type', get_class($investigation))->get();

            return view('investigation.approval.preview', compact('investigation', 'taxAssessments'));
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withError('Something went wrong Please contact your admin');
        }
    }
}
