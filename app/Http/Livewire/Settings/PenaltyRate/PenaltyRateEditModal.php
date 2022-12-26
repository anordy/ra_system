<?php

namespace App\Http\Livewire\Settings\PenaltyRate;

use Livewire\Component;
use App\Models\PenaltyRate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class PenaltyRateEditModal extends Component
{
    use LivewireAlert;

    public $penaltyRate;
    public $rate;
    public $year;
    public $name;
    public $code;


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
    }

    public function submit()
    {
        if (!Gate::allows('setting-penalty-rate-edit')) {
            abort(403);
       }
        $this->validate();
        try {
            $this->penaltyRate->update([
                'rate' => $this->rate,
            ]);
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong, Please contact our support desk for help');
        }
    }
    public function render()
    {
        return view('livewire.settings.penalty-rate.edit-modal');
    }
}
