<?php

namespace App\Http\Controllers\Debt;

use App\Models\Debts\DebtWaiver;
use App\Http\Controllers\Controller;
use App\Models\Debts\DebtWaiverAttachment;
use App\Models\TaxAssessments\TaxAssessment;

class AssessmentDebtController extends Controller
{

    public function index()
    {
        return view('debts.assessments.index');
    }

    public function show($assessment_id)
    {
        $assessment_id = decrypt($assessment_id);
        $assessment = TaxAssessment::findOrFail($assessment_id);
        return view('debts.assessments.show', compact('assessment'));
    }

    public function approval($waiverId)
    {
        $waiver = DebtWaiver::findOrFail(decrypt($waiverId));
        $files = DebtWaiverAttachment::where('debt_id', $waiver->id)->get();
        return view('debts.assessments.waivers.approval', compact('waiver', 'files'));
    }

    public function waive($assessment_id)
    {
        $assessment_id = decrypt($assessment_id);
        return view('debts.assessments.waive', compact('assessment_id'));
    }

    public function showWaiver($waiver_id)
    {
        $waiver = DebtWaiver::findOrFail(decrypt($waiver_id));
        return view('debts.assessments.waiver.show', compact('waiver'));
    }

    
}
