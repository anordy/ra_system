<?php

namespace App\Http\Livewire\Debt\Rollback;

use Exception;
use Livewire\Component;
use App\Traits\CustomAlert;
use App\Models\Returns\TaxReturn;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use App\Traits\RollbackReturnPenaltyTrait;

class RollbackReturn extends Component
{
    use RollbackReturnPenaltyTrait, CustomAlert;

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
            $this->customAlert('error', 'You cannot rollback more than once');
        } else {
            try {
                $this->rollBackLatestReturnDebtPenalty($tax_return);
                $this->customAlert('success', 'Penalty & Interest rolled back successful');
            } catch (Exception $e) {
                Log::error($e);
                $this->customAlert('warning', $e->getMessage());
            }
        }
    }

    public function confirmPopUpModal()
    {
        $this->customAlert('warning', 'Are you sure you want to complete this action?', [
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
