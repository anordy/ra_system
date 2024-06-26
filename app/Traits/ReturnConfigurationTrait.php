<?php

namespace App\Traits;

use App\Models\PortConfig;
use App\Models\Returns\BFO\BfoConfig;
use App\Models\Returns\EmTransactionConfig;
use App\Models\Returns\ExciseDuty\MnoConfig;
use App\Models\Returns\HotelReturns\HotelReturnConfig;
use App\Models\Returns\LumpSum\LumpSumConfig;
use App\Models\Returns\MmTransferConfig;
use App\Models\Returns\Petroleum\PetroleumConfig;
use App\Models\Returns\StampDuty\StampDutyConfig;
use App\Models\Returns\Vat\VatReturnConfig;
use App\Models\TaxType;
use Illuminate\Support\Facades\Log;

trait ReturnConfigurationTrait
{
    public function getTaxTypeCode($id)
    {
        try {
            $taxType = TaxType::findOrFail($id, ['code']);
            return $taxType->code;
        } catch (\Exception $exception) {
            Log::error('TRAITS-RETURN-CONFIGURATION-TRAIT-GET-TAX-TYPE-CODE', [$exception]);
            throw $exception;
        }
    }

    public function getConfigModel($code)
    {
        try {
            switch ($code) {
                case TaxType::HOTEL:
                case TaxType::RESTAURANT:
                case TaxType::TOUR_OPERATOR:
                case TaxType::AIRBNB:
                    $model = HotelReturnConfig::class;
                    return $model;

                case TaxType::VAT:
                    $model = VatReturnConfig::class;
                    return $model;

                case TaxType::STAMP_DUTY:
                    $model = StampDutyConfig::class;
                    return $model;

                case TaxType::EXCISE_DUTY_MNO:
                    $model = MnoConfig::class;
                    return $model;

                case TaxType::EXCISE_DUTY_BFO:
                    $model = BfoConfig::class;
                    return $model;

                case TaxType::PETROLEUM:
                    $model = PetroleumConfig::class;
                    return $model;

                case TaxType::AIRPORT_SERVICE_SAFETY_FEE:
                case TaxType::SEAPORT_SERVICE_TRANSPORT_CHARGE:
                    $model = PortConfig::class;
                    return $model;

                case TaxType::ELECTRONIC_MONEY_TRANSACTION:
                    $model = EmTransactionConfig::class;
                    return $model;

                case TaxType::MOBILE_MONEY_TRANSFER:
                    $model = MmTransferConfig::class;
                    return $model;

                case TaxType::LUMPSUM_PAYMENT:
                    $model = LumpSumConfig::class;
                    return $model;

                default:
                    abort(404);
            }

        } catch (\Exception $exception) {
            Log::error('TRAITS-RETURN-CONFIGURATION-TRAIT-GET-CONFIG-MODAL', [$exception]);
            throw $exception;
        }
    }

    public function getConfigs($model)
    {
        try {
            return $model::query()->where('col_type', '=', 'normal')->get();
        } catch (\Exception $exception) {
            Log::error('TRAITS-RETURN-CONFIGURATION-TRAIT-GET-CONFIGS', [$exception]);
            throw $exception;
        }
    }
}
