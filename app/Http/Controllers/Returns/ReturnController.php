<?php

namespace App\Http\Controllers\Returns;

use App\Http\Controllers\Controller;
use App\Models\FinancialYear;
use App\Traits\ReturnConfigurationTrait;
use Illuminate\Support\Facades\Gate;

class ReturnController extends Controller
{
    use ReturnConfigurationTrait;

    public function index()
    {
        return view('returns.index');
    }

    public function taxTypes()
    {
        if (!Gate::allows('setting-return-tax-type-view')) {
            abort(403);
        }
        return view('returns.return-configs.taxtypes');
    }

    public function editTaxType($id)
    {
        if (!Gate::allows('setting-return-tax-type-edit')) {
            abort(403);
        }
        return view('returns.return-configs.edit-tax-type', compact('id'));
    }

    public function create($id, $code)
    {
        if (!Gate::allows('setting-return-configuration-add')) {
            abort(403);
        }
        $taxtype_id = decrypt($id);
        $code = decrypt($code);

        return view('returns.return-configs.create', compact('taxtype_id', 'code'));
    }

    public function edit($taxtype_id, $code, $config_id)
    {
        if (!Gate::allows('setting-return-configuration-edit')) {
            abort(403);
        }
        $taxtype_id = decrypt($taxtype_id);
        $code = decrypt($code);
        $config_id = decrypt($config_id);

        return view('returns.return-configs.edit', compact('taxtype_id', 'code', 'config_id'));
    }

    public function showReturnConfigs($id)
    {
        if (!Gate::allows('setting-return-configuration-view')) {
            abort(403);
        }

        $id = decrypt($id);

        $code = $this->getTaxTypeCode($id);

        $configs = $this->getConfigs($this->getConfigModel($code));

        $code = str_replace('-', ' ', $this->getTaxTypeCode($id));

        return view('returns.return-configs.index', compact('id', 'configs', 'code'));
    }

    public static function getFinancialYear($id)
    {
        $year = FinancialYear::query()->where('id', $id)->value('code');

        return $year;
    }
}
