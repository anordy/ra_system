<?php

namespace App\Http\Livewire\Settings\PenaltyRate;

use App\Models\DualControl;
use App\Traits\DualControlActivityTrait;
use Livewire\Component;
use App\Models\PenaltyRate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class PenaltyRateEditModal extends Component
{
    use LivewireAlert, DualControlActivityTrait;

    public $penaltyRate;
    public $rate;
    public $year;
    public $name;
    public $code;
    public $old_values;


    protected function rules()
    {
        return [
            'rate' => 'required|numeric',
        ];
    }

    public function mount($id)
    {
        $this->penaltyRate = PenaltyRate::findOrFail(decrypt($id));
        $this->rate = number_format($this->penaltyRate->rate, 2);
        $this->name = $this->penaltyRate->name;
        $this->code = $this->penaltyRate->code;
        $this->year = $this->penaltyRate->year->code;
        $this->old_values = [
            'name' => $this->name,
            'code' => $this->code,
            'year' => $this->year,
            'rate' => $this->rate,
        ];
    }

    public function submit()
    {
        if (!Gate::allows('setting-penalty-rate-edit')) {
            abort(403);
        }
        $this->validate();
        try {
            $payload = [
                'name' => $this->name,
                'code' => $this->code,
                'year' => $this->year,
                'rate' => $this->rate,
            ];
            $this->triggerDualControl(get_class($this->penaltyRate), $this->penaltyRate->id, DualControl::EDIT, 'editing penalty rate', json_encode($this->old_values), json_encode($payload));
            $this->alert('success', DualControl::SUCCESS_MESSAGE, ['timer' => 8000]);
            return redirect()->route('settings.penalty-rates.index');
        } catch (\Exception $e) {
            Log::error($e);
            $this->alert('error', DualControl::ERROR_MESSAGE, ['timer' => 2000]);
            return redirect()->route('settings.penalty-rates.index');
        }
    }

    public function render()
    {
        return view('livewire.settings.penalty-rate.edit-modal');
    }
}
