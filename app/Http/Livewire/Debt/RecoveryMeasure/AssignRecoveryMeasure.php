<?php

namespace App\Http\Livewire\Debt\RecoveryMeasure;

use Exception;
use Livewire\Component;
use App\Models\Debts\Debt;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class AssignRecoveryMeasure extends Component
{

    use LivewireAlert;

    public $debt;

    public function mount($debtId)
    {
        $this->debt = Debt::findOrFail($debtId);
    }

    public function render()
    {
        return view('livewire.debts.assign-recovery-measure');
    }
}
