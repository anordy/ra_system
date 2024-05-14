<?php


namespace App\Http\Controllers\Audit;

use App\Http\Controllers\Controller;
use App\Models\TaxAudit\TaxAudit;

class TaxAuditAssessmentController extends Controller
{
    public function index()
    {
        return view('audit.assessment.index');
    }

    public function show($id)
    {
        $audit = TaxAudit::with('assessment', 'officers', 'assessments')->findOrFail(decrypt($id));
        return view('audit.preview', compact('audit'));
    }
}
