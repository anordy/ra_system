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
        $traService = new TraInternalService();

        try {
            $response = $traService->getTinNumber($this->business->tin);

            if ($response && $response['data']) {
                $this->tin = $response['data'];
            } else if ($response && $response['data'] == null) {
                $this->customAlert('warning', 'No TIN information found');
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
            DB::beginTransaction();

            $this->business->tin_verification_status = TinVerificationStatus::APPROVED;
            $this->business->save();

            Tin::create([
                'tin' => $this->tin['tin'],
                'date_of_birth' => Carbon::create($this->tin['date_of_birth'])->format('Y-m-d'),
                'first_name' => $this->tin['first_name'],
                'middle_name' => $this->tin['middle_name'],
                'last_name' => $this->tin['last_name'],
                'gender' => $this->tin['gender'],
                'taxpayer_name' => $this->tin['taxpayer_name'],
                'trading_name' => $this->tin['trading_name'],
                'postal_address' => $this->tin['postal_address'],
                'street' => $this->tin['street'],
                'plot_number' => $this->tin['plot_number'],
                'district' => $this->tin['district'],
                'region' => $this->tin['region'],
                'postal_code' => $this->tin['postal_code'],
                'mobile' => $this->tin['mobile'],
                'email' => $this->tin['email'],
                'vat_registration_number' => $this->tin['vat_registration_number'],
                'biometric' => $this->tin['biometric'],
            ]);

            DB::commit();
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
