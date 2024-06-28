<?php

namespace App\Http\Livewire\Mvr;


use App\Models\MvrOwnershipTransfer;
use App\Models\MvrRegistration;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class OwnershipTransferAssignPlateNumber extends Component
{

    use CustomAlert;

    public $ownerTransferId;
    public $plateNumber;

    public function mount($ownerTransferId)
    {
        $this->ownerTransferId = decrypt($ownerTransferId);
    }


    public function submit()
    {
        $this->validate([
            'plateNumber' => 'required|alpha_num'
        ]);

        $ownershipTransfer = MvrOwnershipTransfer::with('motor_vehicle')->findOrFail($this->ownerTransferId);

        $mvrReg = $ownershipTransfer->motor_vehicle;

        if ($mvrReg->plate_number === $this->plateNumber) {
            $this->customAlert('warning', 'The Entered plate number already exists');
            return;
        }

        if ($ownershipTransfer->old_plate_number) {
            $this->customAlert('warning', 'This transfer has plate number assigned to it');
            return;
        }

        try {
            DB::beginTransaction();
            $ownershipTransfer->old_plate_number = $mvrReg->plate_number;
            $ownershipTransfer->save();

            $mvrReg->plate_number = $this->plateNumber;
            $mvrReg->save();

            DB::commit();

            $this->flash('success', 'Plate number successfully assigned', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.mvr.ownership-transfer-assign-plate-number');
    }
}
