<?php

namespace App\Http\Controllers\Returns;

use App\Http\Controllers\Controller;
use App\Models\FinancialYear;
use App\Models\PortConfig;
use App\Models\Returns\BFO\BfoConfig;
use App\Models\Returns\EmTransactionConfig;
use App\Models\Returns\ExciseDuty\MnoConfig;
use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\HotelReturns\HotelReturnConfig;
use App\Models\Returns\MmTransferConfig;
use App\Models\Returns\Petroleum\PetroleumConfig;
use App\Models\Returns\StampDuty\StampDutyConfig;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\Returns\Vat\VatReturn;
use App\Models\Returns\Vat\VatReturnConfig;
use App\Models\TaxType;
use App\Traits\ReturnConfigurationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReturnController extends Controller
{
    use ReturnConfigurationTrait;

    public function index()
    {
        return view('returns.index');
    }

    public function config()
    {
        if (!Gate::allows('setting-return-configuration-view')) {
            abort(403);
        }
        return view('returns.return-configs.taxtypes');
    }

    public function create($id, $code)
    {
        if (!Gate::allows('setting-return-configuration-add')) {
            abort(403);
        }
        $taxtype_id = decrypt($id);
        $code       = decrypt($code);

        return view('returns.return-configs.create', compact('taxtype_id', 'code'));
    }

    public function edit($taxtype_id, $code, $config_id)
    {
        if (!Gate::allows('setting-return-configuration-edit')) {
            abort(403);
        }
        $taxtype_id = decrypt($taxtype_id);
        $code       = decrypt($code);
        $config_id  = decrypt($config_id);

        return view('returns.return-configs.edit', compact('taxtype_id', 'code', 'config_id'));
    }

    public function showReturnConfigs($id)
    {
        if (!Gate::allows('setting-return-configuration-view')) {
            abort(403);
        }

        $id = decrypt($id);

        $code  = $this->getTaxTypeCode($id);

        $configs = $this->getConfigs($this->getConfigModel($code));

        $code  = str_replace('-', ' ', $this->getTaxTypeCode($id));

        return view('returns.return-configs.index', compact('id', 'configs', 'code'));
    }

    public function getFinancialYear($id)
    {
        $year = FinancialYear::query()->where('id', $id)->value('code');

        return $year;
    }
}
