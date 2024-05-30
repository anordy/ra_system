<?php

namespace App\Http\Livewire\Returns;

use App\Models\Currency;
use App\Models\FinancialYear;
use App\Models\TaxType;
use App\Traits\CustomAlert;
use App\Traits\ReturnConfigurationTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class AddReturnConfig extends Component
{
    use CustomAlert, ReturnConfigurationTrait;

    public $taxtype_id, $code, $model, $currencies, $year;
    public $service_code;
    public $name;
    public $config_code;
    public $row_type;
    public $value_calculated;
    public $col_type;
    public $rate_applicable;
    public $rate_type;
    public $currency;
    public $rate;
    public $rate_usd;
    public $order;
    public $bank_account;

    public function mount()
    {
        $this->year = FinancialYear::query()->select('id')->where('code', date('Y'))->first();
        if (is_null($this->year)) {
            abort(404, 'Financial year not found.');
        }
        $this->currencies = Currency::all();
        $code = $this->getTaxTypeCode(decrypt($this->taxtype_id));
        $this->model = $this->getConfigModel($code);
    }

    public function updated($property)
    {
        $this->bank_account = str_replace(',', '', $this->bank_account);
        if (is_numeric($this->bank_account)) {
            $this->bank_account = number_format($this->bank_account);
        }
        $this->config_code = strtoupper($this->get_avatar($this->name));

    }


    function get_avatar($str)
    {
        $words = preg_split("/(\s|\-|\.)/", $str);
        $word = '';
        $acronym = '';
        foreach ($words as $w) {
            $acronym .= substr($w, 0, 1);
        }
        $word = $word . $acronym;
        return $word;
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|alpha_gen',
            'row_type' => 'required|alpha',
            'value_calculated' => 'required|alpha',
            'col_type' => 'required|alpha',
            'rate_applicable' => 'required|alpha',
            'rate_type' => 'required|alpha',
            'currency' => 'required|alpha',
            'rate' => 'required|numeric',
            'rate_usd' => 'required|numeric',
        ]);

        try {
            $this->order = $this->model::query()->select('order')->orderByDesc('id')->firstOrFail();
            if ($this->code == TaxType::VAT) {
                $payload = [
                    'name' => $this->name,
                    'code' => $this->config_code,
                    'order' => $this->order->order + 1,
                    'vat_service_code' => $this->service_code,
                    'financial_year_id' => $this->year->id,
                    'row_type' => $this->row_type,
                    'value_calculated' => $this->value_calculated,
                    'col_type' => $this->col_type,
                    'rate_applicable' => $this->rate_applicable,
                    'rate_type' => $this->rate_type,
                    'currency' => $this->currency,
                    'rate_usd' => $this->rate_usd,
                ];
            } else {
                $payload = [
                    'name' => $this->name,
                    'code' => $this->config_code,
                    'order' => $this->order->order + 1,
                    'financial_year_id' => $this->year->id,
                    'row_type' => $this->row_type,
                    'value_calculated' => $this->value_calculated,
                    'col_type' => $this->col_type,
                    'rate_applicable' => $this->rate_applicable,
                    'rate_type' => $this->rate_type,
                    'currency' => $this->currency,
                    'rate_usd' => $this->rate_usd,
                ];
            }

            $this->model::query()->create($payload);
            $this->flash('success', 'Record updated successfully');
            redirect()->route('settings.return-config.show', encrypt($this->taxtype_id));
        } catch (\Throwable $exception) {
            Log::error($exception);
            $this->flash('warning', 'Something went wrong, please contact the administrator for help', [], redirect()->back()->getTargetUrl());
        }

    }

    public function render()
    {
        return view('livewire.returns.add-return-config');
    }
}
