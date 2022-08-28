<?php

namespace App\Http\Controllers\Debt;

use Carbon\Carbon;
use App\Models\Debts\Debt;
use App\Models\TaxAudit\TaxAudit;
use App\Http\Controllers\Controller;
use App\Models\Returns\ReturnStatus;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\Verification\TaxVerification;
use App\Models\Investigation\TaxInvestigation;

class AssessmentDebtController extends Controller
{

    public function verification()
    {
        $now = Carbon::now();
        
        $assessmentDebts = TaxAssessment::query()
            ->where('assessment_type', TaxVerification::class)
            ->where('status', '!=', ReturnStatus::COMPLETE)
            ->whereRaw("DATEDIFF('". $now->format("Y-m-d") . "', tax_assessments.created_at  ) >= 21")
            ->get();
            
        return view('debts.verifications.index', compact('assessmentDebts'));
    }

    public function audit()
    {
        $now = Carbon::now();

        $assessmentDebts = TaxAssessment::query()
            ->where('assessment_type', TaxAudit::class)
            ->where('status', '!=', ReturnStatus::COMPLETE)
            ->whereRaw("DATEDIFF('". $now->format("Y-m-d") . "', tax_assessments.created_at  ) >= 21")
            ->get();

        return view('debts.audits.index', compact('assessmentDebts'));
    }

    public function investigation()
    {
        $now = Carbon::now();

        $assessmentDebts = TaxAssessment::query()
            ->where('assessment_type', TaxInvestigation::class)
            ->where('status', '!=', ReturnStatus::COMPLETE)
            ->whereRaw("DATEDIFF('". $now->format("Y-m-d") . "', tax_assessments.created_at  ) >= 21")
            ->get();

        return view('debts.investigations.index', compact('assessmentDebts'));
    }
    

    public function show($id)
    {
        $id = decrypt($id);
        $debt = Debt::findOrFail($id);
        $assesment = $debt->debt_type::find($debt->debt_id);

        return view('debts.audits.show', compact('assesment', 'id', 'debt'));
    }

    

    
}
