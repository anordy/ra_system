<?php

namespace App\Http\Livewire\Returns\Port;

use App\Models\Returns\Port\PortConfig;
use App\Models\Returns\Port\PortReturn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class PortFilingEditReturn extends Component
{

    use LivewireAlert;

    public $configs = [];
    public $portConfig = [];
    public $currentPortConfig = [];

    public $return_id;
    public $return;
    public $tax_type_id;

    public function mount($return)
    {
        $this->return_id = decrypt($return);
        $this->return = PortReturn::find($this->return_id);
        $this->tax_type_id = $this->return->tax_type_id;
        $this->currentPortConfig = $this->return->configReturns;

        $this->portConfig = PortConfig::where('tax_type_id', $this->tax_type_id)->orderBy('order')->get()->toArray();
        $data = collect();
        foreach ($this->portConfig as $config) {
            $current_values = $this->currentPortConfig->where('config_id', $config['id'])->first();
            $config['value'] = $current_values->value ?? 0;
            $config['vat'] = $current_values->vat ?? 0;
            $data->push($config);

        }

        $this->configs = $data;
    }

    protected function singleVatCalculate($key)
    {
        $config = $this->configs[$key];
        if (is_numeric($config['value'])) {
            if ($config['rate_type'] == 'fixed') {
                if ($config['currency'] == 'USD') {
                    $config['vat'] = $config['value'] * $config['rate_usd'];
                } else {
                    $config['vat'] = $config['value'] * $config['rate'];
                }
                $this->configs[$key] = $config;
            } else {
                $config['vat'] = (($config['value'] * $config['rate']) * 0.01);
                $this->configs[$key] = $config;
            }

            return $config['vat'];
        } else {
            $config['vat'] = 0;
            $this->configs[$key] = $config;
            return 0;
        }
    }

    protected function totalVatCalculate($key, $formular)
    {

        $formulars = explode('_', str_replace(['+', '-', '/', '*'], '_', $formular));
        $configs = collect($this->configs);
        $codeConfigs = $configs->whereIn('code', $formulars)->all();

        $formulaExpression = $formular;

        foreach ($codeConfigs as $config) {
            $value = strval($config['vat']);
            $formulaExpression = str_replace($config['code'], $value, $formulaExpression);
        }
        $expressionLanguage = new ExpressionLanguage();
        $data = $expressionLanguage->evaluate($formulaExpression);
        $config = $this->configs[$key];
        $config['vat'] = $data;
        $this->configs[$key] = $config;
        return $data;

    }

    public function submit()
    {
        $configs = collect($this->configs);
        DB::beginTransaction();

        try {

            $payload = [
                'filled_type' => 'App\Models\User',
                'filed_by_id' => auth()->user()->id,
                'total_vat_payable_tzs' => $configs->firstWhere('code', 'TLATZS')['vat'] ?? 0,
                'total_vat_payable_usd' => $configs->firstWhere('code', 'TLAUSD')['vat'] ?? 0,
                'infrastructure' => $configs->firstWhere('code', 'IT')['vat'] ?? 0,
                'infrastructure_znz_tm' => $configs->firstWhere('code', 'ITTM')['vat'] ?? 0,
                'infrastructure_znz_znz' => $configs->firstWhere('code', 'ITZNZ')['vat'] ?? 0,
            ];

            $this->return->update($payload);

            foreach ($this->configs as $config) {
                $values[] = [
                    'config_id' => $config['id'],
                    'value' => $config['value'] ?? 0,
                    'vat' => $config['vat'] ?? 0,
                ];
            }
            $this->return->configReturns()->delete();
            $this->return->configReturns()->createMany($values);

            DB::commit();
            $this->alert('success', 'Record saved successfully');
            return redirect()->route('returns.port.index');

        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error($exception);
            $this->alert('error', 'Something went wrong, please contact the administrator for help');
        }

    }
    public function render()
    {
        return view('livewire.returns.port.port-filing-edit-return');
    }
}
