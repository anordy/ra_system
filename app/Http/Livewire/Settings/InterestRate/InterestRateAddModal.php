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
use Jantinnerezo\LivewireAlert\LivewireAlert;

class InterestRateAddModal extends Component
{
    use LivewireAlert, DualControlActivityTrait;

    public $year;
    public $rate;


    protected function rules()
    {
        return [
            'year' => 'required|unique:interest_rates,year',
            'rate' => 'required',
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
            $this->alert('success', DualControl::SUCCESS_MESSAGE, ['timer' => 8000]);
            return redirect()->route('settings.interest-rates.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', DualControl::ERROR_MESSAGE, ['timer' => 2000]);
            return redirect()->route('settings.interest-rates.index');
        }
    }

    public function render()
    {
        return view('livewire.settings.interest-rate.add-modal');
    }
}
