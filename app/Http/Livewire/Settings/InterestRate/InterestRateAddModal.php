<?php

namespace App\Http\Livewire\Settings\InterestRate;

use App\Models\InterestRate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Exception;

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
        $this->validate();
        try {
            InterestRate::create([
                'year' => $this->year,
                'rate' => $this->rate,
            ]);
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.settings.interest-rate.add-modal');
    }
}
