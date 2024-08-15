<?php

namespace App\Http\Livewire\Settings\InterestRate;

use App\Models\DualControl;
use App\Traits\DualControlActivityTrait;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\InterestRate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use App\Traits\CustomAlert;

class InterestRateEditModal extends Component
{
    use CustomAlert, DualControlActivityTrait;

    public $rate;
    public $year;
    public $interestRate;
    public $old_values;

    protected function rules()
    {
        return [
            'rate' => 'required|strip_tag|numeric|min:0',
            'year' => 'required|unique:interest_rates,year,' . $this->interestRate->id,
        ];
    }

    public function mount($id)
    {
        $data = InterestRate::find(decrypt($id));
        if (is_null($data)) {
            abort(404);
        }
        $this->interestRate = $data;
        $this->rate = number_format($data->rate, 4);
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
            $this->customAlert('success', DualControl::SUCCESS_MESSAGE, ['timer' => 8000]);
            return redirect()->route('settings.interest-rates.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', DualControl::ERROR_MESSAGE, ['timer' => 2000]);
            return redirect()->route('settings.interest-rates.index');
        }
    }

    public function render()
    {
        return view('livewire.settings.interest-rate.edit-modal');
    }
}
