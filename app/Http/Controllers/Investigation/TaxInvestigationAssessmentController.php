<?php


namespace App\Http\Controllers\Investigation;

use App\Http\Controllers\Controller;
use App\Models\Investigation\TaxInvestigation;
use App\Models\SystemSetting;
use App\Models\TaxAssessments\TaxAssessment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use PDF;

class TaxInvestigationAssessmentController extends Controller
{
    public function index()
    {
        if (!Gate::allows('tax-investigation-assessment-view')) {
            abort(403);
        }
        return view('investigation.assessment.index');
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

    public function getNotice($id)
    {
        $investigation = TaxInvestigation::findOrFail(decrypt($id));

        $totalInterest = $investigation->assessments->sum('interest_amount') ?? 0;
        $totalPenalty = $investigation->assessments->sum('penalty_amount') ?? 0;
        $totalAmount = $investigation->assessments->sum('total_amount') ?? 0;

        $signaturePath = SystemSetting::certificatePath();
        $commissionerFullName = SystemSetting::commissinerFullName();

        $pdf = PDF::loadView('investigation.assessment.notice', compact('investigation', 'signaturePath', 'commissionerFullName', 'totalInterest', 'totalPenalty', 'totalAmount'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif', 'isRemoteEnabled' => true]);

        return $pdf->stream();
    }
}
