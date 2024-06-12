<?php

namespace App\Http\Livewire\Settings\InterestRate;

use App\Models\DualControl;
use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\InterestRate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use App\Traits\CustomAlert;

class InterestRateAddModal extends Component
{
    use CustomAlert, DualControlActivityTrait;

    public $year;
    public $rate;


    protected function rules()
    {
        return [
            'year' => 'required|unique:interest_rates,year|strip_tag',
            'rate' => 'required|strip_tag',
        ];
    }


    public function submit()
    {
        if (!Gate::allows('setting-interest-rate-add')) {
            abort(403);
        }
        $this->validate();
        DB::beginTransaction();
        try {
            $interest_rate  = InterestRate::create([
                'year' => $this->year,
                'rate' => $this->rate,
            ]);
            $this->triggerDualControl(get_class($interest_rate), $interest_rate->id, DualControl::ADD, 'adding interest rate');
            DB::commit();
            $this->customAlert('success', DualControl::SUCCESS_MESSAGE, ['timer' => 8000]);
            return redirect()->route('settings.interest-rates.index');
        } catch (Exception $e) {
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
        return view('livewire.settings.interest-rate.add-modal');
    }
}
