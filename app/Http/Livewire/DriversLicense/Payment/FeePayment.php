<?php

namespace App\Http\Livewire\DriversLicense\Payment;

use App\Models\DlFee;
use App\Models\MvrFee;
use App\Models\MvrFeeType;
use App\Models\MvrRegistration;
use App\Models\MvrRegistrationStatusChange;
use App\Services\ZanMalipo\GepgResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use Illuminate\Support\Facades\Log;

class FeePayment extends Component
{
    use CustomAlert, PaymentsTrait, GepgResponse;

    public $license, $fee, $feeType;

    public function mount($license){
        $this->license = $license;

        // Fetch the fee
        $this->fee = DlFee::query()->where('dl_license_duration_id', $this->license->license_duration_id)->first();

    }

    public function refresh(){
        $this->license = get_class($this->license)::find($this->license->id);
        if(is_null($this->license)){
            abort(404);
        }
    }

    public function regenerate(){
        $response = $this->regenerateControlNo($this->license->get_latest_bill);
        if ($response){
            session()->flash('success', 'Your request was submitted, you will receive your payment information shortly.');
            return redirect(request()->header('Referer'));
        }
        $this->customAlert('error', 'Control number could not be generated, please try again later.');
    }

    /**
     * A Safety Measure to Generate a bill that has not been generated
     */
    public function generateBill(){
        try {

            if (empty($this->fee)) {
                $this->customAlert('error', "Fee for the selected registration type is not configured");
                DB::rollBack();
                return;
            }

            $this->generateDLicenseControlNumber($this->license, $this->fee);
            $this->customAlert('success', 'Your request was submitted, you will receive your payment information shortly.');
            return redirect(request()->header('Referer'));
        } catch (Exception $e) {
            $this->customAlert('error', 'Bill could not be generated, please try again later.');
            Log::error($e);
        }
    }

    public function getGepgStatus($code)
    {
        return $this->getResponseCodeStatus($code)['message'];
    }

    public function render(){
        return view('livewire.drivers-license.payment.payment');
    }
}