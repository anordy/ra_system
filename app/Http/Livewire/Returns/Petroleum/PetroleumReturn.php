<?php

namespace App\Http\Livewire\Returns\Petroleum;

use App\Models\Returns\PetroleumConfig;
use Livewire\Component;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class PetroleumReturn extends Component
{
    public $configs = [];
    public $petroleumConfigs = [];

    public function mount()
    {
        $this->petroleumConfigs = PetroleumConfig::orderBy('order')->get()->toArray();
        $data = collect();
        foreach ($this->petroleumConfigs as $config) {
            $config['value'] = '0';
            $config['vat'] = '0';
            $data->push($config);
        }

        $this->configs = $data;
    }



    protected function singleVatCalculate($key)
    {
        $config = $this->configs[$key];
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

    protected function totalVatCalculate($formular)
    {

        $formulars =  explode('_',str_replace(['+','-','/','*'], '_', $formular));
        $configs = collect($this->configs);
        $codeConfigs = $configs->whereIn('code', $formulars)->all();


        $formulaExpression = $formular;

        foreach ($codeConfigs as $config) {
            $value = strval($config['vat']);
            $formulaExpression = str_replace($config['code'], $value, $formulaExpression);
        }
        $expressionLanguage = new ExpressionLanguage();

        return $expressionLanguage->evaluate($formulaExpression);
    }
    public function render()
    {
        return view('livewire.returns.petroleum.petroleum-return');
    }
}
