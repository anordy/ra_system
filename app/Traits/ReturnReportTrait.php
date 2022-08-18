<?php

namespace App\Traits;

use App\Models\Returns\BFO\BfoReturn;
use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\Returns\Vat\VatReturn;
use App\Models\TaxType;

trait ReturnReportTrait
{
    public function getRecords($model, $parameters)
    {
        $tableName = $model->getModel()->getTable();
        if ($parameters['type'] == 'Filing') {
            if ($parameters['filing_report_type'] == 'On-Time-Filings') {
                $returns = $model->where('filing_due_date', '>=', "${tableName}.created_at");
            } elseif ($parameters['filing_report_type'] == 'Late-Filings') {
                $returns = $model->where('filing_due_date', '<', "${tableName}.created_at");
            } elseif ($parameters['filing_report_type'] == 'All-Filings') {
                $returns = $model;
            }
        } elseif ($parameters['type'] == 'Payment') {
            $returns = $model->whereNotNull('paid_at');
            if ($parameters['payment_report_type'] == 'On-Time-Paid-Returns') {
                $returns = $returns->where('payment_due_date', '>=', 'paid_at');
            } elseif ($parameters['payment_report_type'] == 'Late-Paid-Returns') {
                $returns = $returns->where('payment_due_date', '<', 'paid_at', );
            } elseif ($parameters['payment_report_type'] == 'Unpaid-Returns') {
                $returns = $model->whereNull('paid_at');
            } elseif ($parameters['payment_report_type'] == 'All-Paid-Returns') {
                $returns = $model->whereNotNull('paid_at');
            }
        }
        if ($returns->count() < 1) {
            return $returns;
        }
        return $this->getSelectedRecords($returns, $parameters, $tableName);
    }

    public function getSelectedRecords($records, $parameters, $tableName)
    {
        $dates = $parameters['dates'];
        if ($dates == []) {
            return $records->orderBy("${tableName}.created_at", 'asc');
        }
        if ($dates['startDate'] == null || $dates['endDate'] == null) {
            return $records->orderBy("${tableName}.created_at", 'asc');
        }
        return $records->whereBetween("${tableName}.created_at", [$dates['startDate'], $dates['endDate']])->orderBy("${tableName}.created_at", 'asc');
    }

    public function getModelData($parameters)
    {
        // $parameters = $this->getParameters();
        switch ($parameters['tax_type_code']) {
            case 'excise-duty-mno':
                return [
                    'returnName' => 'Excise Duty MNO',
                    'model' => MnoReturn::query(),
                ]; 
                break;
            case 'excise-duty-bfo':
                return [
                    'returnName' => 'Excise Duty BFO',
                    'model' => BfoReturn::query(),
                ]; 
                break;
            case 'hotel-levy':
                $taxType = TaxType::where('code',TaxType::HOTEL)->first();
                return [
                    'returnName' => 'Hotel Levy',
                    'model' => HotelReturn::query()->where('tax_type_id',$taxType->id),
                ];
                break;
            case 'restaurant-levy':
                $taxType = TaxType::where('code',TaxType::RESTAURANT)->first();
                return [
                    'returnName' => 'Restaurant Levy',
                    'model' => HotelReturn::query()->where('tax_type_id',$taxType->id),
                ];
                break;
            case 'tour-operator-levy':
                $taxType = TaxType::where('code',TaxType::TOUR_OPERATOR)->first();
                return [
                    'returnName' => 'Tour Operator Levy',
                    'model' => HotelReturn::query()->where('tax_type_id',$taxType->id),
                ];
            break;
            case 'vat':
                $taxType = TaxType::where('code',TaxType::VAT)->first();
                return [
                    'returnName' => 'Vat Return',
                    'model' => VatReturn::query(),
                ];
            break;
            case 'petroleum-levy':
                dd('petroleum-levy');
                break;
            case 'airport-service-safety-fee':
                dd('airport-service-safety-fee');
                break;
            case 'mobile-money-transfer':
                dd('mobile-money-transfer');
                break;
            case 'electronic-money-transaction':
                dd('electronic-money-transaction');
                break;
            case 'lumpsum-payment':
                return [
                    'returnName'=> 'Lump Sum',
                    'model' => LumpSumReturn::query(),
                ];
                break;
            case 'sea-service-transport-charge':
                $taxType = TaxType::where('code', 'sea-service-transport-charge')->first();
                return [
                    'returnName' => 'Sea Service Transport Charge',
                    'model' => PortReturn::query()->where('tax_type_id', $taxType->id),
                ];
                break;
            case 'stamp-duty':
                return [
                    'returnName' => 'Stamp Duty',
                    'model' => StampDutyReturn::query(),
                ]; 
                break;
        }
    }    
}
