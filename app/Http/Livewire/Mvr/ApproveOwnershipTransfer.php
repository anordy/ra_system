<?php

namespace App\Http\Livewire\Mvr;

use App\Enum\GeneralConstant;
use App\Models\MvrOwnershipTransfer;
use App\Traits\CustomAlert;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ApproveOwnershipTransfer extends Component
{

    use CustomAlert;

    public $transfer_category_id;
    public $request_id;


    protected function rules()
    {
        return [
            'transfer_category_id' => 'required|numeric',
        ];
    }

    public function mount($request_id)
    {
        $this->request_id = decrypt($request_id);
    }


    public function submit()
    {
        $this->validate();
        try {
            $request = MvrOwnershipTransfer::query()->findOrFail($this->request_id);
            $request->update(['mvr_transfer_category_id'=>$this->transfer_category_id]);
            return redirect()->to(route('mvr.transfer-ownership.approve', encrypt($this->request_id)));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('MVR-APPROVE-OWNERSHIP-TRANSFER', [$e]);
            $this->customAlert(GeneralConstant::ERROR, 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.mvr.approve-ownership-transfer-modal');
    }
}
