<?php

namespace App\Http\Livewire;

use App\Models\DualControl;
use App\Models\ExchangeRate;
use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class ExchangeRateAddModal extends Component
{

    use CustomAlert, DualControlActivityTrait;

    public $currency;
    public $mean;
    public $spot_buying;
    public $spot_selling;
    public $exchange_date;


    protected function rules()
    {
        return [
            'currency' => 'required|min:2',
            'mean' => 'required|numeric',
            'spot_buying' => 'required|numeric',
            'spot_selling' => 'required|numeric',
            'exchange_date' => 'required|date',
        ];
    }


    public function submit()
    {
        if (!Gate::allows('setting-exchange-rate-add')) {
            abort(403);
        }

        $this->validate();
        try{
           $exchange_rate = ExchangeRate::create([
                'currency' => $this->currency,
                'mean' => $this->mean,
                'spot_buying' => $this->spot_buying,
                'spot_selling' => $this->spot_selling,
                'exchange_date' => $this->exchange_date,
            ]);
            $this->triggerDualControl(get_class($exchange_rate), $exchange_rate->id, DualControl::ADD, 'adding exchange rate');
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        }catch(Exception $e){
            Log::error($e);

            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.exchange-rate-add-modal');
    }
}
