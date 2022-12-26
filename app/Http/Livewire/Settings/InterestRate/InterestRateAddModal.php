<?php

namespace App\Http\Livewire\Settings\InterestRate;

use Exception;
use Livewire\Component;
use App\Models\InterestRate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class InterestRateAddModal extends Component
{
    use LivewireAlert;

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
            InterestRate::create([
                'year' => $this->year,
                'rate' => $this->rate,
            ]);
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong, Please contact our support desk for help');
        }
    }

    public function render()
    {
        return view('livewire.settings.interest-rate.add-modal');
    }
}
