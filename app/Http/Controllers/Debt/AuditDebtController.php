<?php

namespace App\Http\Controllers\Debt;

use Carbon\Carbon;
use App\Models\Debts\Debt;
use App\Http\Controllers\Controller;
use App\Models\Returns\ReturnStatus;
use App\Models\TaxAudit\TaxAudit;
use App\Models\TaxAudit\TaxAuditAssessment;

class AuditDebtController extends Controller
{

    public function index()
    {
        $debts = Debt::truncate();
        $assesments = $this->generateAuditsDebts();

        // Insert assesments into debts
        $debts->insert($assesments);
        return view('debts.audits.index');
    }


    public function show($id)
    {
        $id = decrypt($id);
        $debt = Debt::findOrFail($id);
        $assesment = $debt->debt_type::find($debt->debt_type_id);

        return view('debts.audits.show', compact('assesment', 'id', 'debt'));
    }

    
    public function generateAuditsDebts()
    {
        $now = Carbon::now();

        // Assesment Debts
        $assessments = TaxAudit::selectRaw('
             tax_audits.business_id,
             tax_audits.location_id,
             tax_type_id, 
             tax_audit_assessments.principal_amount,
             tax_audit_assessments.penalty_amount,
             tax_audit_assessments.interest_amount,
             tax_audit_assessments.id as assesment_id
         ')
            ->join('tax_audit_assessments', 'tax_audit_assessments.audit_id', 'tax_audits.id')
            ->leftJoin('objections', 'objections.assesment_id', 'tax_audit_assessments.id')
            ->whereNull('objections.assesment_id')
            ->where("tax_audits.status", '!=', ReturnStatus::COMPLETE)
            ->whereRaw("DATEDIFF('" . $now->format("Y-m-d") . "', tax_audit_assessments.created_at  ) >= 21")
            ->get()->toArray();

            

        $assesment_calculations = array_map(function ($assessments) {
            return array(
                'tax_type_id' => $assessments['tax_type_id'],
                'debt_type' => TaxAuditAssessment::class,
                'debt_type_id' => $assessments['assesment_id'],
                'business_id' => $assessments['business_id'],
                'location_id' => $assessments['location_id'],
                'category' => 'audit',
                'total' => $assessments['principal_amount'] + $assessments['penalty_amount'] + $assessments['interest_amount']
            );
        }, $assessments);

        return $assesment_calculations;
    }

    
}
