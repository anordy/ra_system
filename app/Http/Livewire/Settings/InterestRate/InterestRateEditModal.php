<?php

namespace App\Http\Livewire\Settings\InterestRate;

use App\Models\Bank;
use App\Models\InterestRate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class InterestRateEditModal extends Component
{
    use LivewireAlert;

    public $rate;
    public $year;
    public $interestRate;

    protected function rules()
    {
        return [
            'rate' => 'required',
            'year' => 'required|unique:interest_rates,year,'.$this->interestRate->id,
        ];
    }

    public function mount($id)
    {
        $data = InterestRate::find($id);
        $this->interestRate = $data;
        $this->rate = $data->rate;
        $this->year = $data->year;
    }

    public function submit()
    {
        $this->validate();
        try {
            $this->interestRate->update([
                'rate' => $this->rate,
                'year' => $this->year,
            ]);
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }
    public function render()
    {
        return view('livewire.settings.interest-rate.edit-modal');
    }
}
