<?php

namespace App\Http\Controllers\Debt;

use Carbon\Carbon;
use App\Models\Debts\Debt;
use App\Models\LumpSumPayment;
use App\Http\Controllers\Controller;
use App\Models\LumpSumReturn;
use App\Models\Returns\ReturnStatus;
use App\Models\Returns\BFO\BfoReturn;
use App\Models\Returns\Vat\VatReturn;
use App\Models\Returns\MmTransferReturn;
use App\Models\Returns\EmTransactionReturn;
use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Models\Verification\TaxVerification;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\Verification\TaxVerificationAssessment;

class DebtController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        $debts = Debt::truncate();
        
        // Assesment Debts
        $assessments = TaxVerification::selectRaw('
                tax_verifications.business_id,
                tax_verifications.location_id,
                tax_type_id, 
                tax_return_type,
                tax_return_id,
                tax_verification_assessments.principal_amount,
                tax_verification_assessments.penalty_amount,
                tax_verification_assessments.interest_amount,
                tax_verification_assessments.id as assesment_id
            ')
                ->join('tax_verification_assessments', 'tax_verification_assessments.verification_id', 'tax_verifications.id')
                ->leftJoin('objections', 'objections.assesment_id', 'tax_verification_assessments.id')
                ->whereNull('objections.assesment_id')
                ->where("tax_verification_assessments.status", '!=', ReturnStatus::COMPLETE)
                ->whereRaw("DATEDIFF('". $now->format("Y-m-d") . "', tax_verification_assessments.created_at  ) >= 21")
                ->get()->toArray();


        $assesment_calculations = array_map(function ($assessments) {
            return array(
                'tax_type_id' => $assessments['tax_type_id'],
                'debt_type' => TaxVerificationAssessment::class,
                'debt_type_id' => $assessments['assesment_id'],
                'business_id' => $assessments['business_id'],
                'location_id' => $assessments['location_id'],
                'category' => 'assesment',
                'due_date' => $assessments['tax_return_type']::find($assessments['tax_return_id'])->financialMonth->due_date->format('Y-m-d'),
                'financial_month_id' => $assessments['tax_return_type']::find($assessments['tax_return_id'])->financial_month_id,
                'total' => $assessments['principal_amount'] + $assessments['penalty_amount'] + $assessments['interest_amount']
            
            );
        }, $assessments);


        $returns = $this->getReturnsDebts();

        // TODO: Objections Debts

        // Insert returns and objections debts
        $debts->insert($assesment_calculations);
        $debts->insert($returns);

        return view('debts.index');
    }

    public function showReturnDebt($id)
    {
        $id = decrypt($id);
        $debt = Debt::findOrFail($id);
        $return = $debt->debt_type::find($debt->debt_type_id);
        return view('debts.return.show',compact('return','id'));
    }

    public function showAssesmentDebt($id)
    {
        $id = decrypt($id);
        $debt = Debt::findOrFail($id);
        $assesment = $debt->debt_type::find($debt->debt_type_id);

        return view('debts.assesment.show',compact('assesment','id', 'debt'));
    }

    public function showObjection($id)
    {
        $now = Carbon::now();
        $id = decrypt($id);
        
        $objection = TaxVerification::selectRaw('*')
        ->join('tax_verification_assessments', 'tax_verification_assessments.verification_id', 'tax_verifications.id')
        ->leftJoin('objections', 'objections.assesment_id', 'tax_verification_assessments.id')
        ->join('businesses', 'businesses.id', 'tax_verifications.business_id')
        ->where('tax_verifications.id',$id)->first();
        
        return view('debts.objection.show',compact('objection','id'));
    }

    public function getReturnsDebts() 
    {
        $now = Carbon::now();

        $returnModels = [
            StampDutyReturn::class,
            MnoReturn::class,
            VatReturn::class,
            MmTransferReturn::class,
            HotelReturn::class,
            // PetroleumReturn::class,
            // PortReturn::class,
            EmTransactionReturn::class,
            BfoReturn::class,
            LumpSumReturn::class
        ];

        $return_debts = [];

        foreach ($returnModels as $model) {
            $table_name = $model::query()->getQuery()->from;
            $returns = $model::selectRaw('
                '.$table_name.'.id,
                business_id,
                business_location_id,
                tax_type_id,
                financial_month_id,
                total_amount_due_with_penalties
            ')
                ->leftJoin('financial_months', 'financial_months.id', ''.$table_name.'.financial_month_id')
                ->leftJoin('financial_years', 'financial_years.id', 'financial_months.financial_year_id')
                ->where(''.$table_name.'.status', '!=', ReturnStatus::COMPLETE)
                ->where('financial_months.due_date', '<', $now)
                ->get();


            foreach ($returns as $return) {
                $return_debts[] = $return;
            }
        }


        $returns_calculations = array_map(function ($return_debts) {
            return array(
                'tax_type_id' => $return_debts['tax_type_id'],
                'debt_type' => get_class($return_debts),
                'debt_type_id' => $return_debts['id'],
                'business_id' => $return_debts['business_id'],
                'location_id' => $return_debts['business_location_id'],
                'category' => 'return',
                'due_date' => get_class($return_debts)::find($return_debts['id'])->financialMonth->due_date->format('Y-m-d'),
                'financial_month_id' => get_class($return_debts)::find($return_debts['id'])->financial_month_id,
                'total' => $return_debts['total_amount_due_with_penalties']
            );
        }, $return_debts);

        return $returns_calculations;
      
    }




    
}
