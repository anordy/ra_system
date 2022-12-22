<?php

namespace App\Http\Livewire\Mvr;

use App\Models\Bank;
use App\Models\MvrFee;
use App\Models\MvrFeeType;
use App\Models\MvrMotorVehicle;
use App\Models\MvrMotorVehicleRegistration;
use App\Models\MvrOwnershipTransfer;
use App\Models\MvrPlateNumberStatus;
use App\Models\MvrRegistrationStatus;
use App\Models\MvrRegistrationType;
use App\Models\MvrRequestStatus;
use App\Models\ZmBill;
use App\Services\TRA\ServiceRequest;
use App\Services\ZanMalipo\ZmCore;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ApproveOwnershipTransfer extends Component
{

    use LivewireAlert;

    public $transfer_category_id;
    public $request_id;


    protected function rules()
    {
        return [
            'transfer_category_id' => 'required',
        ];
    }

    public function mount($request_id)
    {
        $this->request_id = $request_id;
    }


    public function submit()
    {
        $this->validate();
        try {
            $request = MvrOwnershipTransfer::query()->find($this->request_id);
            $request->update(['mvr_transfer_category_id'=>$this->transfer_category_id]);
            return redirect()->to(route('mvr.transfer-ownership.approve', encrypt($this->request_id)));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong, Could you please contact our administrator for assistance?');
        }
    }

    public function render()
    {
        return view('livewire.mvr.approve-ownership-transfer-modal');
    }
}
