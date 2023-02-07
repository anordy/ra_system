<?php

namespace App\Http\Livewire\Debt\Rollback;

use Exception;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;
use App\Traits\RollbackReturnPenaltyTrait;
use App\Models\TaxAssessments\TaxAssessment;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class RollbackAssessment extends Component
{
    use LivewireAlert, RollbackReturnPenaltyTrait;

    public $assessment_id;

    protected $listeners = [
        'rollback'
    ];

    public function mount($assessment_id)
    {
        $this->assessment_id = decrypt($assessment_id);
    }

    public function rollback() {
        if (!Gate::allows('debt-management-debt-rollback')) {
            abort(403);
        }   
        $assessment = TaxAssessment::findOrFail($this->assessment_id);

        if ($assessment->rollback) {
            $this->alert('error', 'You cannot rollback more than once');
        } else {
            try {
                $this->rollBackLatestAssessmentDebtPenalty($assessment);
                $this->alert('success', 'Penalty & Interest rolled back successful');
            } catch (Exception $e) {
                $this->alert('warning', 'Something went wrong, please contact support for assistance.');
            }
        }
    }

    public function confirmPopUpModal()
    {
        $this->alert('warning', 'Are you sure you want to complete this action?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Confirm',
            'onConfirmed' => 'rollback',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => null
        ]);
    }

    public function render()
    {
        return view('livewire.debts.rollback.button');
    }
}
