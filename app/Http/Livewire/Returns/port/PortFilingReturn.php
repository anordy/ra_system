<?php

namespace App\Http\Livewire\Returns\Port;

use App\Models\BusinessLocation;
use App\Models\FinancialMonth;
use App\Models\Returns\Port\PortConfig;
use App\Models\Returns\Port\PortReturn;
use App\Models\Taxpayer;
use App\Traits\PenaltyTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class PortFilingReturn extends Component
{

    use PenaltyTrait,  LivewireAlert;
    // Declare Variables

    public $configs = [];
    public $portConfig = [];
    public $submitted = false;
    public $penalties_usd;
    public $penalties_tzs;
    public $total_vat_payable_tzs;
    public $total_vat_payable_usd;

    public $business_location_id;
    public $tax_type_id;
    public $location_id;

    // Data options
    public $business;

    public function mount($business_location_id, $tax_type_id, $filling_month_id)
    {
        $r = new \ReflectionClass(PortReturn::class);
        $this->modelName = $r->name;

        $this->filling_month_id = decrypt($filling_month_id);
        $this->fillingMonth = FinancialMonth::findOrFail($this->filling_month_id);

        $this->tax_type_id = decrypt($tax_type_id);
        $this->business_location_id = decrypt($business_location_id);
        $this->business = BusinessLocation::find($this->business_location_id)->business;
        $this->portConfig = PortConfig::where('tax_type_id', $this->tax_type_id)->orderBy('order')->get()->toArray();
        $data = collect();
        foreach ($this->portConfig as $config) {
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
        $r = new \ReflectionClass(Taxpayer::class);
        DB::beginTransaction();
        try {
            $port_return = new PortReturn();
            $port_return->business_location_id = $this->business_location_id;
            $port_return->business_id = $this->business->id;
            $port_return->filled_type = $r->name;
            $port_return->filed_by_id = auth()->user()->id;
            $port_return->tax_type_id = $this->tax_type_id;
            $port_return->financial_year_id = $this->fillingMonth->financial_year_id;
            $port_return->financial_month_id = $this->fillingMonth->id;
            $port_return->infrastructure = $configs->firstWhere('code', 'IT')['vat'] ?? 0;
            $port_return->infrastructure_znz_tm = $configs->firstWhere('code', 'ITTM')['vat'] ?? 0;
            $port_return->infrastructure_znz_znz = $configs->firstWhere('code', 'ITZNZ')['vat'] ?? 0;
            if ($this->tax_type_id == 9) {
                $port_return->total_vat_payable_tzs = $configs->firstWhere('code', 'TLATZS')['vat'] ?? 0;
                $port_return->total_vat_payable_usd = $configs->firstWhere('code', 'TLAUSD')['vat'] ?? 0;
            } elseif ($this->tax_type_id == 10) {
                $port_return->total_vat_payable_tzs = $configs->firstWhere('code', 'TLSTZS')['vat'] ?? 0;
                $port_return->total_vat_payable_usd = $configs->firstWhere('code', 'TLSUSD')['vat'] ?? 0;
            }

            $port_return->save();
            
            $this->total_vat_payable_usd = $port_return->total_vat_payable_usd;
            $this->total_vat_payable_tzs = $port_return->total_vat_payable_tzs;

            foreach ($this->configs as $config) {
                $values[] = [
                    'config_id' => $config['id'],
                    'value' => $config['value'] ?? 0,
                    'vat' => $config['vat'] ?? 0,
                ];
            }

            $port_return->configReturns()->createMany($values);

            DB::commit();
            $this->alert('success', 'Record saved successfully');
            return redirect()->route('returns.port.index');

        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error($exception);
            $this->alert('error', 'Something went wrong, Please contact our support desk for help');
        }

    }

    public function toggleSummary($value)
    {
        $this->submitted = $value;
    }

    public function render()
    {
        if ($this->submitted) {

            if ($financialMonth = $this->getFilingMonth($this->business_location_id, $this->modelName)) {
                    $this->penalties_tzs = $this->getTotalPenalties($financialMonth, $this->total_vat_payable_tzs, 'TZS');
                    $this->penalties_usd = $this->getTotalPenalties($financialMonth, $this->total_vat_payable_usd, 'USD');
            
            } else {
                // Kama hana, pamoja
                $this->penalties_tzs = [];
                $this->penalties_usd = [];

            }
            $actionsView = 'livewire.returns.port.includes.file-actions';
            return view('livewire.returns.port.port-penalty-summary', ['penalties_tzs' => $this->penalties_tzs, 'penalties_usd' => $this->penalties_usd, 'actionsView' => $actionsView]);
        }

        return view('livewire.returns.port.port-filing-return');
    }
}
