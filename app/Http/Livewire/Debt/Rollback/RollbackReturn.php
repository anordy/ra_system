<?php

namespace App\Http\Livewire\Debt\Rollback;

use Exception;
use Livewire\Component;
use App\Models\Returns\TaxReturn;
use Illuminate\Support\Facades\Gate;
use App\Traits\RollbackReturnPenaltyTrait;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class RollbackReturn extends Component
{
    use RollbackReturnPenaltyTrait, LivewireAlert;

    public $return_id;

    protected $listeners = [
        'rollback'
    ];

    public function mount($return_id)
    {
        $this->return_id = decrypt($return_id);
    }

    public function rollback() {
        if (!Gate::allows('debt-management-debt-rollback')) {
            abort(403);
        }   
        $tax_return = TaxReturn::findOrFail($this->return_id);

        if ($tax_return->rollback) {
            $this->alert('error', 'You cannot rollback more than once');
        } else {
            try {
                $this->rollBackLatestReturnDebtPenalty($tax_return);
                $this->alert('success', 'Penalty & Interest rolled back successful');
            } catch (Exception $e) {
                $this->alert('warning', $e->getMessage());
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
