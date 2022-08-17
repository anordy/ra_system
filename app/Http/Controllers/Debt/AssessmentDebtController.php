<?php

namespace App\Http\Controllers\Debt;

use Carbon\Carbon;
use App\Models\Business;
use App\Models\Debts\Debt;
use App\Models\TaxAudit\TaxAudit;
use App\Http\Controllers\Controller;
use App\Models\Debts\DebtWaiver;
use App\Models\Debts\DebtWaiverAttachment;
use App\Models\Returns\ReturnStatus;
use Illuminate\Support\Facades\Auth;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\Verification\TaxVerification;
use App\Models\Investigation\TaxInvestigation;

class AssessmentDebtController extends Controller
{

    // TODO: Verify if assesment debts are triggered after 21 days

    public function waivers()
    {
        return view('debts.waivers.index');
    }

    public function approval($waiverId)
    {
        $waiver = DebtWaiver::findOrFail(decrypt($waiverId));
        $debt = Debt::find($waiver->debt_id);
        $business = Business::find($waiver->business_id);
        $files = DebtWaiverAttachment::where('debt_id', $waiver->id)->get();
        return view('debts.waivers.approval', compact('waiver', 'files', 'business', 'debt'));
    }

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
        $assesment = $debt->debt_type::find($debt->debt_type_id);

        return view('debts.audits.show', compact('assesment', 'id', 'debt'));
    }

    

    
}
