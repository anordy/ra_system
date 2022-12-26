<?php

namespace App\Http\Livewire\Returns;

use App\Models\Currency;
use App\Models\FinancialYear;
use App\Models\TaxType;
use App\Traits\ReturnConfigurationTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class AddReturnConfig extends Component
{
    use LivewireAlert, ReturnConfigurationTrait;

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
        $this->year = FinancialYear::query()->where('code', date('Y'))->first();
        $this->currencies = Currency::all();
        $code = $this->getTaxTypeCode($this->taxtype_id);
        $this->model = $this->getConfigModel($code);

    }

    public function updated($property)
    {
        $this->bank_account = str_replace(',', '', $this->bank_account);
        if (is_numeric($this->bank_account))
        {
            $this->bank_account = number_format($this->bank_account);
        }
        $this->config_code = strtoupper($this->get_avatar($this->name));

    }

//    public function formatBankAccount(){
//        $bank_account = $this->bank_account;
//        $bank_account = preg_replace('/[^0-9]+/', '', $bank_account);
//        $bank_account = substr($bank_account, 0, 20);
//        $length = strlen($bank_account);
//        $formatted = "";
//        for ($i = 0; $i < $length; $i++) {
//            $formatted .= $bank_account[$i];
//            if($i == 2 || $i == 5 || $i == 8 || $i == 11 || $i == 14 || $i == 17 || $i == 20){
//                $formatted .= ",";
//            }
//        }
//        $this->bank_account = $formatted;
//    }

    function get_avatar($str){
        $words = preg_split("/(\s|\-|\.)/", $str);
        $word = '';
        $acronym = '';
        foreach($words as $w) {
            $acronym .= substr($w,0,1);
        }
        $word = $word . $acronym ;
        return $word;
    }

    public function submit()
    {
        $validate = $this->validate([
            'name'=>'required',
            'row_type'=>'required',
            'value_calculated'=>'required',
            'col_type'=>'required',
            'rate_applicable'=>'required',
            'rate_type'=>'required',
            'currency'=>'required',
            'rate'=>'required',
            'rate_usd'=>'required',
        ]);

        DB::beginTransaction();
        try {
            $this->order = $this->model::query()->select('order')->orderByDesc('id')->first();
            if ($this->code == TaxType::VAT)
            {
                $payload = [
                    'name'=>$this->name,
                    'code'=>$this->config_code,
                    'order'=>$this->order->order + 1,
                    'vat_service_code'=>$this->service_code,
                    'financial_year_id'=>$this->year->id,
                    'row_type'=>$this->row_type,
                    'value_calculated'=>$this->value_calculated,
                    'col_type'=>$this->col_type,
                    'rate_applicable'=>$this->rate_applicable,
                    'rate_type'=>$this->rate_type,
                    'currency'=>$this->currency,
                    'rate_usd'=>$this->rate_usd,
                ];
            }
            else
            {
                $payload = [
                    'name'=>$this->name,
                    'code'=>$this->config_code,
                    'order'=>$this->order->order + 1,
                    'financial_year_id'=>$this->year->id,
                    'row_type'=>$this->row_type,
                    'value_calculated'=>$this->value_calculated,
                    'col_type'=>$this->col_type,
                    'rate_applicable'=>$this->rate_applicable,
                    'rate_type'=>$this->rate_type,
                    'currency'=>$this->currency,
                    'rate_usd'=>$this->rate_usd,
                ];
            }

            $this->model::query()->create($payload);
            DB::commit();
            $this->flash('success', 'Record updated successfully');
            redirect()->route('settings.return-config.show', encrypt($this->taxtype_id));
        }
        catch (\Throwable $exception)
        {
            DB::rollBack();
            Log::error($exception);
            $this->flash('warning', 'Something went wrong, please contact the administrator for help', [], redirect()->back()->getTargetUrl());
        }

    }

    public function render()
    {
        return view('livewire.returns.add-return-config');
    }
}
