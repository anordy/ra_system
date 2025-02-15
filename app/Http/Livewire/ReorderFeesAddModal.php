<?php

namespace App\Http\Livewire;

use App\Models\DualControl;
use App\Models\MvrReorderPlateNumberFee;
use App\Traits\DualControlActivityTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use App\Traits\CustomAlert;

class ReorderFeesAddModal extends Component
{
    use CustomAlert, DualControlActivityTrait;
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

    public function submit()
    {
        $this->validate();

        if (!Gate::allows('setting-transaction-fees-add')) {
            abort(403);
        }

        DB::beginTransaction();

        try {
            $reorder_fee = new MvrReorderPlateNumberFee();
            $reorder_fee->amount = $this->amount;
            $reorder_fee->quantity = $this->quantity;
            $reorder_fee->is_rfid = $this->is_rfid;
            $reorder_fee->is_plate_sticker = $this->is_plate_sticker;
            $reorder_fee->gfs_code = 11011;
            $reorder_fee->created_by = Auth::id();
            $reorder_fee->save();

            $this->triggerDualControl(get_class($reorder_fee), $reorder_fee->id, DualControl::ADD, 'adding reorder fee');

            DB::commit();
            $this->customAlert('success', 'Fee added successfully');
            return redirect()->route('settings.reorder-fees.index');
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error($exception);
            $this->customAlert('error', 'Something went wrong');
            return redirect()->route('settings.reorder-fees.index');
        }
    }

    public function render()
    {
        return view('livewire.reorder-fees-add-modal');
    }
}
