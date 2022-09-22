<?php

namespace App\Http\Livewire;

use App\Models\ExchangeRate;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ExchangeRateEditModal extends Component
{
    use LivewireAlert;
    public $exchange_rate;
    public $mean;
    public $spot_buying;
    public $spot_selling;
    public $exchange_date;

    protected function rules()
    {
        return [
            'mean' => 'required',
            'spot_buying' => 'required',
            'spot_selling' => 'required',
            'exchange_date' => 'required|date',
        ];
    }

    public function submit()
    {
        $this->validate();
        if (!Gate::allows('setting-exchange-rate-edit')) {
            abort(403);
        }

        try {
            $this->exchange_rate->update([
                'mean' => $this->mean,
                'spot_buying' => $this->spot_buying,
                'spot_selling' => $this->spot_selling,
                'exchange_date' => $this->exchange_date,
            ]);
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function mount($id)
    {
        $data = ExchangeRate::find($id);
        $this->exchange_rate = $data;
        $this->spot_buying = $data->spot_buying;
        $this->spot_selling = $data->spot_selling;
        $this->mean = $data->mean;
        $this->exchange_date = $data->exchange_date;
    }

    public function render()
    {
        return view('livewire.exchange-rate-edit-modal');
    }
}
