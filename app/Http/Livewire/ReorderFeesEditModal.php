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

class ReorderFeesEditModal extends Component
{
    use CustomAlert, DualControlActivityTrait;

    public $data;
    public $old_values;
    public $new_values;
    public $amount;
    public $quantity;
    public $is_rfid;
    public $is_plate_sticker;

    protected function rules()
    {
        return [
            'amount' => 'required|numeric'
        ];
    }

    public function mount($id)
    {
        $data = TransactionFee::find(decrypt($id));
        if(is_null($data)){
            abort(404);
        }
        $this->amount = $data->amount;
        $this->is_rfid = $data->is_rfid ?? null;
        $this->is_plate_sticker = $data->is_plate_sticker;
        $this->data = $data;
        $this->old_values = [
            'amount' => $this->amount,
            'is_rfid' => $this->is_rfid,
            'is_plate_sticker' => $this->is_plate_sticker,
        ];
    }

    public function submit()
    {
        if (!Gate::allows('setting-transaction-fees-edit')) {
            abort(403);
        }

        $this->validate();
        $this->new_values = [
            'amount' => $this->amount,
            'is_rfid' => $this->is_rfid,
            'is_plate_sticker' => $this->is_plate_sticker,
        ];
        try {
            $this->data->update([
                'amount' => $this->amount,
                'is_rfid' => $this->is_rfid,
                'is_plate_sticker' => $this->is_plate_sticker,
                'is_approved' => 0,
                'created_by' => Auth::id()
            ]);

            $this->triggerDualControl(get_class($this->data), $this->data->id, DualControl::EDIT, 'Editing reorder fee', $this->old_values, $this->new_values);

            $this->flash('success', 'Fee updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $this->customAlert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.reorder-fees-edit-modal');
    }
}
