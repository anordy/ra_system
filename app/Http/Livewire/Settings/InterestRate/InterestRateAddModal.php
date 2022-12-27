<?php

namespace App\Http\Livewire\Settings\InterestRate;

use App\Models\DualControl;
use App\Traits\DualControlActivityTrait;
use Exception;
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
        try {
            $interest_rate  = InterestRate::create([
                'year' => $this->year,
                'rate' => $this->rate,
            ]);
            $this->triggerDualControl(get_class($interest_rate), $interest_rate->id, DualControl::ADD, 'adding interest rate');

            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.settings.interest-rate.add-modal');
    }
}
