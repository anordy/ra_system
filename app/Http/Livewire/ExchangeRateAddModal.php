<?php

namespace App\Http\Livewire;

use App\Models\ExchangeRate;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ExchangeRateAddModal extends Component
{

    use LivewireAlert;

    public $currency;
    public $mean;
    public $spot_buying;
    public $spot_selling;
    public $exchange_date;


    protected function rules()
    {
        return [
            'currency' => 'required|min:2|unique:exchange_rates',
            'mean' => 'required',
            'spot_buying' => 'required',
            'spot_selling' => 'required',
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
            ExchangeRate::create([
                'currency' => $this->currency,
                'mean' => $this->mean,
                'spot_buying' => $this->spot_buying,
                'spot_selling' => $this->spot_selling,
                'exchange_date' => $this->exchange_date,
            ]);
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        }catch(Exception $e){
            Log::error($e);

            $this->alert('error', 'Something went wrong, Please contact our support desk for help');
        }
    }

    public function render()
    {
        return view('livewire.exchange-rate-add-modal');
    }
}
