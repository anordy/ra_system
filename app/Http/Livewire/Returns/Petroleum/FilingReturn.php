<?php

namespace App\Http\Livewire\Returns\Petroleum;

use App\Models\Returns\Petroleum\PetroleumConfig;
use App\Models\Returns\Petroleum\PetroleumConfigHead;
use App\Models\Returns\Petroleum\PetroleumReturn;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;


class FilingReturn extends Component
{
    use LivewireAlert;

    public $configs = [];
    public $petroleumConfigs = [];

    public $taxt_type_id;
    public $business_id;
    public $location_id;

    public $business;

    public function mount($location, $tax_type, $business)
    {
        $this->location_id = decrypt($location);
        $this->tax_type_id = decrypt($tax_type);
        $this->business_id = decrypt($business);

        $this->petroleumConfigs = PetroleumConfig::orderBy('order')->get()->toArray();
        $data = collect();
        foreach ($this->petroleumConfigs as $config) {
            $config['value'] = '0';
            $config['vat'] = '0';

            if ($config['col_type'] == 'heading') {
                $config['headings'] = PetroleumConfigHead::where('petroleum_config_id', $config['id'])->get()->toArray();
            }

            $data->push($config);
        }

        $this->configs = $data;
    }


    public function valueCalculated($key, $formular)
    {
        if ($formular == '') return 0;
        $configs = collect($this->configs);
        $formulaExpression = $formular;

        foreach ($configs as $config) {
            $configValue = $config['value'];

            if (!is_numeric($configValue)) {
                $configValue = 0;
            }

            if ($config['rate_type'] == 'fixed') {
                if ($config['currency'] == 'USD') {
                    $configValue = $configValue * $config['rate_usd'];
                }
            } else {
                $configValue = ($configValue * $config['rate']) / 100;
            }

            $value = strval($configValue);
            $formulaExpression = str_replace($config['code'], $value, $formulaExpression);
        }
        $expressionLanguage = new ExpressionLanguage();
        $data = $expressionLanguage->evaluate($formulaExpression);
        $config = $this->configs[$key];
        $config['value'] = $data;
        $this->configs[$key] = $config;
        return $data;
    }


    public function singleVatCalculate($key)
    {
        $config = $this->configs[$key];

        if ($config['value_calculated']) {
            $config['vat'] = $config['value'];
            $this->configs[$key] = $config;
            return $config['vat'];
        }
        if (is_numeric($config['value'])) {
            $config['vat'] = $config['value'] * $config['rate'];
            $this->configs[$key] = $config;
            return $config['vat'];
        } else {
            $config['vat'] = 0;
            $this->configs[$key] = $config;
            return 0;
        }
    }

    public function totalVatCalculate($key, $formular)
    {

        if ($formular == '') return 0;
        $configs = collect($this->configs);
        $formulaExpression = $formular;


        foreach ($configs as $config) {
            $configValue = $config['value'];

            if (!is_numeric($configValue)) {
                $configValue = 0;
            }

            if ($config['rate_type'] == 'fixed') {
                if ($config['currency'] == 'USD') {
                    $configValue = $configValue * $config['rate_usd'];
                }
            } else {
                $configValue = ($configValue * $config['rate']) / 100;
            }

            $value = strval($configValue);
            $formulaExpression = str_replace($config['code'], $value, $formulaExpression);
        }

        $expressionLanguage = new ExpressionLanguage();
        $data = $expressionLanguage->evaluate($formulaExpression);
        $config = $this->configs[$key];
        $config['vat'] = $data;
        $this->configs[$key] = $config;
        return $data;
    }

    public function save()
    {
        $configs = collect($this->configs);
        DB::beginTransaction();
        try {
            $payload = [
                'business_location_id' => $this->location_id,
                'business_id' => $this->business_id,
                'filed_by_type' => get_class(auth()->user()),
                'filed_by_id' => auth()->user()->id,
                'tax_type_id' => $this->tax_type_id,
                'total' => $configs->firstWhere('code', 'TOTAL')['vat'] ?? 0,
                'petroleum_levy' => $configs->firstWhere('code', 'PTL')['vat'] ?? 0,
                'infrastructure_tax' => $configs->firstWhere('code', 'IFT')['vat'] ?? 0,
                'rdf_tax' => $configs->firstWhere('code', 'RDF')['vat'] ?? 0,
                'road_lincence_fee' => $configs->firstWhere('code', 'RLF')['vat'] ?? 0,
                'financial_year_id' => 1
            ];

            $return = PetroleumReturn::create($payload);

            $values = [];
            foreach ($this->configs as $config) {
                $values[] = [
                    'config_id' => $config['id'],
                    'value' => $config['value'] ?? 0,
                    'vat' => $config['vat'] ?? 0,
                ];
            }

            $return->configReturns()->createMany($values);

            DB::commit();
            session()->flash('success', 'Return has been filed');
            $this->redirect(route('returns.filing'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong, Please contact our support desk for help');
        }
    }


    public function submit()
    {
        $this->save();
    }

    public function render()
    {
        return view('livewire.returns.petroleum.add');
    }
}
