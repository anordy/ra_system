<?php

namespace App\Traits;

use App\Models\PortConfig;
use App\Models\Returns\BFO\BfoConfig;
use App\Models\Returns\EmTransactionConfig;
use App\Models\Returns\ExciseDuty\MnoConfig;
use App\Models\Returns\HotelReturns\HotelReturnConfig;
use App\Models\Returns\MmTransferConfig;
use App\Models\Returns\Petroleum\PetroleumConfig;
use App\Models\Returns\StampDuty\StampDutyConfig;
use App\Models\Returns\Vat\VatReturnConfig;
use App\Models\TaxType;
use Exception;
use Carbon\Carbon;
use App\Models\Audit;
use Illuminate\Support\Facades\Log;

trait ReturnConfigurationTrait
{
    public function getTaxTypeCode($id)
    {
        $this->taxtype = TaxType::query()->findOrFail($id);
        $code = $this->taxtype->code;
        return $code;
    }

    public function getConfigModel($code)
    {
        switch ($code) {
            case TaxType::HOTEL:
            case TaxType::RESTAURANT:
            case TaxType::TOUR_OPERATOR:
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
            case TaxType::SEA_SERVICE_TRANSPORT_CHARGE:
                $model = PortConfig::class;
                return $model;

            case TaxType::ELECTRONIC_MONEY_TRANSACTION:
                $model = EmTransactionConfig::class;
                return $model;

            case TaxType::MOBILE_MONEY_TRANSFER:
                $model = MmTransferConfig::class;
                return $model;

            default:
                abort(404);
        }
    }

    public function getConfigs($model)
    {
        $configs = $model::query()->where('col_type', '=','normal')->get();
        return $configs;
    }
}
