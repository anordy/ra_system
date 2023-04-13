<?php

namespace App\Http\Livewire;

use App\Models\Bank;
use App\Models\DualControl;
use App\Models\EducationLevel;
use App\Models\TransactionFee;
use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class TransactionFeesEditModal extends Component
{
    use CustomAlert, DualControlActivityTrait;

    public $data;
    public $min_amount;
    public $max_amount;
    public $fee;
    public $old_values;
    public $new_values;

    protected function rules()
    {
        return [
            'min_amount' => 'required|numeric',
            'max_amount' => 'sometimes|nullable|numeric',
            'fee' => 'required|numeric',
        ];
    }

    public function mount($id)
    {
        $data = TransactionFee::find(decrypt($id));
        if(is_null($data)){
            abort(404);
        }
        $this->min_amount = $data->minimum_amount;
        $this->max_amount = $data->maximum_amount ?? null;
        $this->fee = $data->fee;
        $this->data = $data;
        $this->old_values = [
            'minimum_amount' => $this->min_amount,
            'maximum_amount' => $this->max_amount,
            'fee' => $this->fee,
        ];
    }

    public function submit()
    {
        if (!Gate::allows('setting-transaction-fees-edit')) {
            abort(403);
        }

        $this->validate();
        $this->new_values = [
            'minimum_amount' => $this->min_amount,
            'maximum_amount' => $this->max_amount,
            'fee' => $this->fee,
        ];
        try {
            $this->data->update([
                'minimum_amount' => $this->min_amount,
                'maximum_amount' => $this->max_amount,
                'fee' => $this->fee,
                'is_approved' => 0,
                'created_by' => Auth::id()
            ]);

            $this->triggerDualControl(get_class($this->data), $this->data->id, DualControl::EDIT, 'Editing transaction fee', $this->old_values, $this->new_values);

            $this->flash('success', 'Fee updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $this->customAlert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.transaction-fees-edit-modal');
    }
}
