<?php

namespace App\Http\Controllers\Debt;

use App\Models\Debts\DebtWaiver;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Debts\DebtWaiverAttachment;
use App\Models\TaxAssessments\TaxAssessment;

class AssessmentDebtController extends Controller
{
    public function index()
    {
        if (!Gate::allows('debt-management-assessment-debt-view')) {
            abort(403);
        }   
        return view('debts.assessments.index');
    }

    public function show($assessment_id)
    {
        if (!Gate::allows('debt-management-assessment-debt-view')) {
            abort(403);
        }   
        $assessment_id = decrypt($assessment_id);
        $assessment = TaxAssessment::findOrFail($assessment_id);
        return view('debts.assessments.show', compact('assessment'));
    }

    public function approval($waiverId)
    {
        if (!Gate::allows('debt-management-debts-waive')) {
            abort(403);
        }
        $waiver = DebtWaiver::findOrFail(decrypt($waiverId));
        $files = DebtWaiverAttachment::where('debt_id', $waiver->id)->get();
        return view('debts.assessments.waivers.approval', compact('waiver', 'files'));
    }

    public function showWaiver($waiver_id)
    {
        if (!Gate::allows('debt-management-waiver-debt-view')) {
            abort(403);
        } 
        $waiver = DebtWaiver::findOrFail(decrypt($waiver_id));
        return view('debts.assessments.waivers.show', compact('waiver'));
    }

    
}
