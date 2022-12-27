<?php

namespace App\Http\Livewire\Settings\InterestRate;

use App\Models\Bank;
use App\Models\DualControl;
use App\Traits\DualControlActivityTrait;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\InterestRate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class InterestRateEditModal extends Component
{
    use LivewireAlert, DualControlActivityTrait;

    public $rate;
    public $year;
    public $interestRate;
    public $old_values;

    protected function rules()
    {
        return [
            'rate' => 'required',
            'year' => 'required|unique:interest_rates,year,' . $this->interestRate->id,
        ];
    }

    public function mount($id)
    {
        $data = InterestRate::find($id);
        $this->interestRate = $data;
        $this->rate = $data->rate;
        $this->year = $data->year;
        $this->old_values = [
            'rate' => $this->rate,
            'year' => $this->year,
        ];

    }

    public function submit()
    {
        if (!Gate::allows('setting-interest-rate-edit')) {
            abort(403);
        }
        $this->validate();
        $payload = [
            'rate' => $this->rate,
            'year' => $this->year,
        ];
        DB::beginTransaction();
        try {
            $this->triggerDualControl(get_class($this->interestRate), $this->interestRate->id, DualControl::EDIT, 'editing interest rate', json_encode($this->old_values), json_encode($payload));
            DB::commit();
            $this->alert('success', DualControl::SUCCESS_MESSAGE, ['timer' => 8000]);
            return;
        } catch (\Exception $e) {
            Log::error($e);
            $this->alert('error', DualControl::ERROR_MESSAGE, ['timer' => 2000]);
        }
    }

    public function render()
    {
        return view('livewire.settings.interest-rate.edit-modal');
    }
}
