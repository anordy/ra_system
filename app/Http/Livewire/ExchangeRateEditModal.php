<?php

namespace App\Http\Livewire;

use App\Models\DualControl;
use App\Models\ExchangeRate;
use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ExchangeRateEditModal extends Component
{
    use LivewireAlert, DualControlActivityTrait;
    public $exchange_rate;
    public $mean;
    public $spot_buying;
    public $spot_selling;
    public $exchange_date;
    public $old_values;

    protected function rules()
    {
        return [
            'mean' => 'required|numeric',
            'spot_buying' => 'required|numeric',
            'spot_selling' => 'required|numeric',
            'exchange_date' => 'required|date',
        ];
    }

    public function submit()
    {
        $this->validate();
        if (!Gate::allows('setting-exchange-rate-edit')) {
            abort(403);
        }
        $payload = [
            'mean' => $this->mean,
            'spot_buying' => $this->spot_buying,
            'spot_selling' => $this->spot_selling,
            'exchange_date' => $this->exchange_date,
        ];

        DB::beginTransaction();
        try {
            $this->triggerDualControl(get_class($this->exchange_rate), $this->exchange_rate->id, DualControl::EDIT, 'editing exchange rate', json_encode($this->old_values), json_encode($payload));
            DB::commit();
            $this->alert('success', DualControl::SUCCESS_MESSAGE, ['timer' => 8000]);
            return redirect()->route('settings.exchange-rate.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('success', DualControl::ERROR_MESSAGE, ['timer' => 2000]);
            return redirect()->route('settings.exchange-rate.index');
        }
    }

    public function mount($id)
    {
        $id = decrypt($id);
        $this->exchange_rate = ExchangeRate::find($id);
        if(is_null($this->exchange_rate)){
            abort(404);
        }
        $this->spot_buying = $this->exchange_rate->spot_buying;
        $this->spot_selling = $this->exchange_rate->spot_selling;
        $this->mean = $this->exchange_rate->mean;
        $this->exchange_date = $this->exchange_rate->exchange_date;
        $this->old_values = [
            'mean' => $this->mean,
            'spot_buying' => $this->spot_buying,
            'spot_selling' => $this->spot_selling,
            'exchange_date' => $this->exchange_date,
        ];
    }

    public function render()
    {
        return view('livewire.exchange-rate-edit-modal');
    }
}
