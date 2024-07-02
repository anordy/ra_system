<?php


namespace App\Http\Controllers\Audit;

use App\Http\Controllers\Controller;
use App\Models\BusinessLocation;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxAudit\TaxAudit;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class TaxAuditApprovalController extends Controller
{
    public function index()
    {
        if (!Gate::allows('tax-auditing-approval-view')) {
            abort(403);
        }


        return view('audit.approval.index');
    }
    public function business()
    {
        if (!Gate::allows('itu-view-business-with-risk')) {
            abort(403);
        }


        return view('audit.approval.businesses');
    }
    public function showBusiness($id)
    {
        if (!Gate::allows('itu-view-business-with-risk')) {
            abort(403);
        }

        $location = BusinessLocation::whereHas('taxVerifications', function ($query) {
            $query->with('riskIndicators'); // Eager load risk indicators with tax verification
        })->findOrFail(decrypt($id));

        $taxReturns = $location->taxVerifications->map(function ($verification) {
            return $verification->taxReturn;
        });

        return view('audit.business.show', compact('location', 'taxReturns'));
    }

    public function edit($id)
    {
        try {
            $audit = TaxAudit::findOrFail(decrypt($id));

            $taxAssessments = TaxAssessment::where('assessment_id', $audit->id)
                ->where('assessment_type', get_class($audit))->get();

            return view('audit.approval.approval', compact('audit', 'taxAssessments'));
        } catch (\Exception $e) {
            report($e);
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('warning', 'An error has occured . Please contact your administrator');
            return back();
        }
    }

    public function show($id)
    {
        try {
            $audit = TaxAudit::with('assessment', 'officers', 'business')->findOrFail(decrypt($id));

            $taxAssessments = TaxAssessment::where('assessment_id', $audit->id)
                ->where('assessment_type', get_class($audit))->get();

            return view('audit.preview', compact('audit', 'taxAssessments'));
        } catch (\Exception $e) {
            report($e);
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('warning', 'The selected audit was not found. Please contact your administrator');
            return back();
        }
    }
}
