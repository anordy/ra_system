<?php

namespace App\Http\Controllers\Debt;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Debts\Debt;
use App\Models\Returns\ReturnStatus;
use App\Models\Verification\TaxVerification;

class DebtController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        
        $assessments = TaxVerification::selectRaw('tax_type_id, tax_return_type,tax_return_id')
        ->join('tax_verification_assessments', 'tax_verification_assessments.verification_id', 'tax_verifications.id')
        ->leftJoin('objections', 'objections.assesment_id', 'tax_verification_assessments.id')
        ->whereNull('objections.assesment_id')
        ->where("tax_verification_assessments.status", '!=', ReturnStatus::COMPLETE)
        ->whereRaw("DATEDIFF('". $now->format("Y-m-d") . "', tax_verification_assessments.created_at  ) >= 21")
        ->get()->toArray();
        
        $calculations = array_map(function ($assessments) {
            return array(
                'tax_type_id' => $assessments['tax_type_id'],
                'debt_type' => $assessments['tax_return_type'],
                'debt_type_id' => $assessments['tax_return_id'],
                'category' => 'assesment',
                'due_date' => '2020-08-11'
            );
        }, $assessments);

        // dd($calculations->toArray());
        $debts = Debt::insert($calculations);

        return view('debts.index');
    }


    public function objection($id)
    {
        $now = Carbon::now();
        $id = decrypt($id);
        
        $objection = TaxVerification::selectRaw('*')
        ->join('tax_verification_assessments', 'tax_verification_assessments.verification_id', 'tax_verifications.id')
        ->leftJoin('objections', 'objections.assesment_id', 'tax_verification_assessments.id')
        ->join('businesses', 'businesses.id', 'tax_verifications.business_id')
        ->where('tax_verifications.id',$id)->first();
        // dd($objection);
        
        return view('debts.objection.show',compact('objection','id'));
    }

    
}
