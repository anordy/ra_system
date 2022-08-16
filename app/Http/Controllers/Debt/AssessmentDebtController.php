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

    public function waivers()
    {
        return view('debts.waivers.index');
    }

    public function approval($waiverId)
    {
        $waiver = DebtWaiver::findOrFail(decrypt($waiverId));
        $assesment = TaxAssessment::find($waiver->assesment_id);
        $business = Business::find($waiver->business_id);
        $files = DebtWaiverAttachment::where('dispute_id', $waiver->id)->get();
        return view('debts.waivers.approval', compact('waiver', 'files', 'business','assesment'));
    }

    public function verification()
    {
        $businesses = Business::query()->orWhere('taxpayer_id', Auth::id())->orWhere('responsible_person_id',Auth::id())->get()->pluck('id')->toArray();
        
        // TODO: Filter by not complete status
        $assessmentDebts = TaxAssessment::query()
            ->where('assessment_type', TaxVerification::class)
            ->get();
            
        return view('debts.verifications.index', compact('assessmentDebts'));
    }

    public function audit()
    {
        $businesses = Business::query()->orWhere('taxpayer_id', Auth::id())->orWhere('responsible_person_id',Auth::id())->get()->pluck('id')->toArray();
        
        $assessmentDebts = TaxAssessment::query()
            ->where('assessment_type', TaxAudit::class)
            ->get();

        return view('debts.audits.index', compact('assessmentDebts'));
    }

    public function investigation()
    {
        $businesses = Business::query()->orWhere('taxpayer_id', Auth::id())->orWhere('responsible_person_id',Auth::id())->get()->pluck('id')->toArray();
        
        $assessmentDebts = TaxAssessment::query()
            ->where('assessment_type', TaxInvestigation::class)
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
