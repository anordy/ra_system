<?php

namespace App\Http\Livewire\Debt\RecoveryMeasure;

use Livewire\Component;
use App\Models\Returns\TaxReturn;
use App\Traits\CustomAlert;

class AssignRecoveryMeasure extends Component
{

    use CustomAlert;

    public $debt;

    public function mount($debtId)
    {
        $this->debt = TaxReturn::findOrFail(decrypt($debtId));
    }

    public function render()
    {
        return view('livewire.debts.assign-recovery-measure');
    }
}
