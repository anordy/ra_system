<?php

namespace App\Http\Controllers\Debt;

use Carbon\Carbon;
use App\Models\Debts\Debt;
use App\Http\Controllers\Controller;
use App\Models\Returns\ReturnStatus;
use App\Models\Verification\TaxVerification;

class VerificationDebtController extends Controller
{

    public function index()
    {
        return view('debts.verifications.index');
    }


    public function show($id)
    {
        $id = decrypt($id);
        $debt = Debt::findOrFail($id);
        $assesment = $debt->debt_type::find($debt->debt_type_id);

        return view('debts.verifications.show', compact('assesment', 'id', 'debt'));
    }
    
    public function generateAssesmentDebts()
    {
        $now = Carbon::now();

        // Assesment Debts
        $assessments = TaxVerification::selectRaw('
             tax_verifications.business_id,
             tax_verifications.location_id,
             tax_verifications.tax_type_id, 
             tax_return_type,
             tax_return_id,
             tax_assessments.principal_amount,
             tax_assessments.penalty_amount,
             tax_assessments.interest_amount,
             tax_assessments.id as assesment_id
         ')
         ->join('tax_assessments', 'tax_assessments.assessment_id', 'tax_verifications.id')
            ->where("tax_assessments.assessment_type", TaxVerification::class)
            ->where("tax_assessments.status", '!=', ReturnStatus::COMPLETE)
            ->whereRaw("DATEDIFF('". $now->format("Y-m-d") . "', tax_assessments.created_at  ) >= 21")
            ->get()->toArray();


        $assesment_calculations = array_map(function ($assessments) {
            return array(
                'tax_type_id' => $assessments['tax_type_id'],
                'debt_type' => TaxVerification::class,
                'debt_type_id' => $assessments['assesment_id'],
                'business_id' => $assessments['business_id'],
                'location_id' => $assessments['location_id'],
                'category' => 'assesment',
                'due_date' => $assessments['tax_return_type']::find($assessments['tax_return_id'])->financialMonth->due_date->format('Y-m-d'),
                'financial_month_id' => $assessments['tax_return_type']::find($assessments['tax_return_id'])->financial_month_id,
                'total' => $assessments['principal_amount'] + $assessments['penalty_amount'] + $assessments['interest_amount']
            );
        }, $assessments);

        return $assesment_calculations;
    }
}
