<?php

namespace App\Http\Controllers\TaxClearance;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\Investigation\TaxInvestigation;
use App\Models\Returns\BFO\BfoReturn;
use App\Models\Returns\EmTransactionReturn;
use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Models\Returns\MmTransferReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\ReturnStatus;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\Returns\Vat\VatReturn;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxAudit\TaxAudit;
use App\Models\Verification\TaxVerification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaxClearanceController extends Controller
{
    //
    public function requestList(){
        return view('tax-clearance.requests');

    }

    public function viewRequest($id){
        
        $business_location_id = decrypt($id);
        $businessLocation = BusinessLocation::where('id', $business_location_id)->with('business')->first();

        $returnDebts = $this->generateReturnsDebts($business_location_id);

        $verificationDebts = TaxAssessment::query()
            ->where('assessment_type', TaxVerification::class)
            ->where('location_id', $business_location_id)
            ->get();
        $auditDebts = TaxAssessment::query()
            ->where('assessment_type', TaxAudit::class)
            ->where('location_id', $business_location_id)
            ->get();

        $investigationDebts = TaxAssessment::query()
            ->where('assessment_type', TaxInvestigation::class)
            ->where('location_id', $business_location_id)
            ->get();
        // return $investigationDebts;
        return view('tax-clearance.clearance-request', compact('businessLocation', 'returnDebts', 'verificationDebts', 'auditDebts', 'investigationDebts'));

    }

    public function generateReturnsDebts($business_location_id)
    {
        $now = Carbon::now();

        $returnModels = [
            StampDutyReturn::class,
            MnoReturn::class,
            VatReturn::class,
            MmTransferReturn::class,
            HotelReturn::class,
            PetroleumReturn::class,
            PortReturn::class,
            EmTransactionReturn::class,
            BfoReturn::class,
            LumpSumReturn::class
        ];
        // dd($returnModels);
        $return_debts = [];

        foreach ($returnModels as $model) {
            
            if ($model == PortReturn::class) {
                $fields = 'total_amount_due_with_penalties_tzs, total_amount_due_with_penalties_usd, total_vat_payable_tzs, total_vat_payable_usd, interest_usd, interest_tzs, penalty_usd, penalty_tzs';
            } else if ($model == MmTransferReturn::class || $model == EmTransactionReturn::class) {
                $fields = 'total_amount_due_with_penalties, total_amount_due';
            } else {
                $fields = 'total_amount_due_with_penalties, total_amount_due, interest, penalty';
            }

            $table_name = $model::query()->getQuery()->from;

            $returns = $model::selectRaw('
                ' . $table_name . '.id,
                business_id,
                business_location_id,
                tax_type_id,
                currency,
                '. $fields . ',
                financial_months.name
            ')
                ->leftJoin('financial_months', 'financial_months.id', '' . $table_name . '.financial_month_id')
                ->leftJoin('financial_years', 'financial_years.id', 'financial_months.financial_year_id')
                ->where('business_location_id', $business_location_id)
                ->where('' . $table_name . '.status', '!=', ReturnStatus::COMPLETE)
                ->where('financial_months.due_date', '<', $now)
                ->get();

            foreach ($returns as $return) {
                $return_debts[] = $return;
            }
        }

        return $return_debts;
    }
}
