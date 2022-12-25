<?php

namespace App\Http\Livewire;

use App\Models\Bank;
use App\Models\EducationLevel;
use App\Models\TransactionFee;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class TransactionFeesEditModal extends Component
{
    use LivewireAlert;

    public $data;
    public $min_amount;
    public $max_amount;
    public $fee;

    protected function rules()
    {
        return [
            'min_amount' => 'required|numeric',
            'max_amount' => 'sometimes|nullable|numeric',
            'fee'        => 'required|numeric',
        ];
    }

    public function mount($id)
    {
        $data = TransactionFee::find($id);
        $this->min_amount = $data->minimum_amount;
        $this->max_amount = $data->maximum_amount ?? null;
        $this->fee = $data->fee;
        $this->data = $data;
    }

    public function submit()
    {
        if (!Gate::allows('setting-transaction-fees-edit')) {
            abort(403);
        }

        $this->validate();
        try {
            $this->data->update([
                'minimum_amount' => $this->min_amount,
                'maximum_amount' => $this->max_amount,
                'fee' => $this->fee,
                'created_by' => Auth::id()
            ]);
            $this->flash('success', 'Fee updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $this->alert('error', 'Something went wrong');
        }
    }
    public function render()
    {
        return view('livewire.transaction-fees-edit-modal');
    }
}
