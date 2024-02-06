<?php

namespace App\Http\Livewire\Approval;

use App\Enum\TinVerificationStatus;
use App\Models\BusinessStatus;
use App\Models\Tra\Tin;
use App\Services\Api\TraInternalService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class TinVerification extends Component
{
    use CustomAlert;

    public $business;

    public $tin = [];

    public $requestSuccess;

    public function mount($business){
        $this->business = $business;
    }

    public function validateTinNumber()
    {
        try {
                $traService = new TraInternalService();
                $response = $traService->getTinNumber($this->business->tin);

                if ($response && $response['data']) {
                    $this->tin = $response['data'];
                } else if ($response && $response['data'] == null) {
                    $this->customAlert('warning', $response['message']);
                    return;
                } else {
                    $this->customAlert('error', 'Something went wrong');
                    return;
                }

        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            return;
        }
    }

    public function save(){
        try {
            $this->business->tin_verification_status = TinVerificationStatus::APPROVED;
            $this->business->save();

            $this->customAlert('success', 'TIN Verification Completed.');
        } catch (\Throwable $e) {
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            return;
        }
    }

    public function render()
    {
        return view('livewire.approval.tin-verification');
    }
}
