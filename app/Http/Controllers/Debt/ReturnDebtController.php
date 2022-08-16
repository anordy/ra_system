<?php

namespace App\Http\Controllers\Debt;

use Carbon\Carbon;
use App\Models\TaxType;
use App\Models\Debts\Debt;
use App\Models\LumpSumReturn;
use App\Http\Controllers\Controller;
use App\Models\Returns\ReturnStatus;
use App\Models\Returns\BFO\BfoReturn;
use App\Models\Returns\Vat\VatReturn;
use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\MmTransferReturn;
use App\Models\Returns\EmTransactionReturn;
use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\Returns\StampDuty\StampDutyReturn;

class ReturnDebtController extends Controller
{

    public function index($taxType)
    {
        $taxType = decrypt($taxType);
        if ($taxType == TaxType::AIRPORT_SERVICE_SAFETY_FEE || $taxType == TaxType::SEA_SERVICE_TRANSPORT_CHARGE) {
            return view('debts.returns.port', compact('taxType'));
        } else {
            return view('debts.returns.index', compact('taxType'));
        }
    }

    public function show($id, $taxType)
    {
        $id = decrypt($id);
        if ($taxType == TaxType::HOTEL || $taxType == TaxType::RESTAURANT || $taxType == TaxType::TOUR_OPERATOR) {
            $return =  HotelReturn::find($id); 
        } else if ($taxType == TaxType::PETROLEUM) {
            $return =  PetroleumReturn::find($id);
        } else if ($taxType == TaxType::EXCISE_DUTY_BFO) {
            $return =  BfoReturn::find($id);
        }  else if ($taxType == TaxType::EXCISE_DUTY_MNO) {
            $return =  MnoReturn::find($id);
        } else if ($taxType == TaxType::STAMP_DUTY) {
            $return =  StampDutyReturn::find($id);
        } else if ($taxType == TaxType::VAT) {
            $return =  VatReturn::find($id);
        } else if ($taxType == TaxType::LUMPSUM_PAYMENT) {
            $return =  LumpSumReturn::find($id);
        } else if ($taxType == TaxType::SEA_SERVICE_TRANSPORT_CHARGE || $taxType == TaxType::AIRPORT_SERVICE_SAFETY_FEE) {
            $return =  PortReturn::find($id);
        } else if ($taxType == TaxType::ELECTRONIC_MONEY_TRANSACTION) {
            $return =  EmTransactionReturn::find($id);
        } else if ($taxType == TaxType::MOBILE_MONEY_TRANSFER) {
            $return =  MmTransferReturn::find($id);
        }  else if ($taxType == TaxType::MOBILE_MONEY_TRANSFER) {
            $return =  MmTransferReturn::find($id);
        }
        return view('debts.returns.show', compact('return', 'id'));
    }
    
    public function generateReturnsDebts()
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
                ' . $table_name . '.id,
                business_id,
                business_location_id,
                tax_type_id,
                financial_month_id,
                total_amount_due_with_penalties,
                financial_months.name
            ')
                ->leftJoin('financial_months', 'financial_months.id', '' . $table_name . '.financial_month_id')
                ->leftJoin('financial_years', 'financial_years.id', 'financial_months.financial_year_id')
                ->where('' . $table_name . '.status', '!=', ReturnStatus::COMPLETE)
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
