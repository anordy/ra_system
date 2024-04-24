<?php

namespace App\Http\Livewire\Returns;

use App\Models\Currency;
use App\Traits\DualControlActivityTrait;
use App\Traits\ReturnConfigurationTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;
use Exception;

class EditReturnConfig extends Component
{
    use CustomAlert, ReturnConfigurationTrait, DualControlActivityTrait;

    public $taxtype_id, $config_id, $model, $currencies, $configs;
    public $name;
    public $row_type;
    public $value_calculated;
    public $col_type;
    public $rate_applicable;
    public $rate_type;
    public $currency;
    public $rate;
    public $rate_usd;
    public $value_formular;

    public function mount()
    {
        $this->currencies = Currency::all();
        $code  = $this->getTaxTypeCode($this->taxtype_id);
        $this->model = $this->getConfigModel($code);
        $this->configs = $this->model::where('id', $this->config_id)->first();
        $this->name = $this->configs->name;
        $this->row_type = $this->configs->row_type;
        $this->value_calculated = $this->configs->value_calculated;
        $this->col_type = $this->configs->col_type;
        $this->rate_applicable = $this->configs->rate_applicable;
        $this->rate_type = $this->configs->rate_type;
        $this->currency = $this->configs->currency;
        $this->rate = $this->configs->rate;
        $this->rate_usd = $this->configs->rate_usd;
        $this->value_formular = $this->configs->value_formular;
    }

    public function update()
    {
        if (!Gate::allows('setting-return-configuration-edit')) {
            abort(403);
        }

        try {
            DB::beginTransaction();

            $payload = [
                'name'=>$this->name,
                'row_type'=>$this->row_type,
                'value_calculated'=>$this->value_calculated,
                'col_type'=>$this->col_type,
                'rate_applicable'=>$this->rate_applicable,
                'rate_type'=>$this->rate_type,
                'currency'=>$this->currency,
                'rate'=>$this->rate,
                'rate_usd'=>$this->rate_usd,
            ];

            if (key_exists('value_formular', $this->configs->attributesToArray())){
                $payload['value_formular'] = $this->value_formular;
            }

            $this->configs->update($payload);
            DB::commit();
            $this->alert('success', 'Record updated successfully');
            redirect()->route('settings.return-config.show', encrypt($this->taxtype_id));
        }
        catch (Exception $exception)
        {
            DB::rollBack();
            Log::error($exception);
            $this->alert('warning', 'Something went wrong, please contact the administrator for help', [], redirect()->back()->getTargetUrl());
        } catch (\Throwable $throwable){
            DB::rollBack();
            Log::error($throwable);
            $this->alert('warning', 'Something went wrong, please contact the administrator for help', [], redirect()->back()->getTargetUrl());
        }
    }

    public function render()
    {
        return view('livewire.returns.edit-return-config');
    }
}
