<?php

namespace App\Http\Livewire\DriversLicense\Payment;

use App\Enum\CustomMessage;
use App\Enum\GeneralConstant;
use App\Models\DlFee;
use App\Services\ZanMalipo\GepgResponse;
use Exception;
use Livewire\Component;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use Illuminate\Support\Facades\Log;

class FeePayment extends Component
{
    use CustomAlert, PaymentsTrait, GepgResponse;

    public $license, $fee;

    public function mount($license){
        $this->license = $license;

        // Fetch the fee
        $this->fee = DlFee::select('id', 'amount')
            ->where('dl_license_duration_id', $this->license->license_duration_id)
            ->where('type', $license->type)
            ->first();

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
            session()->flash('success', CustomMessage::RECEIVE_PAYMENT_SHORTLY);
            return redirect(request()->header('Referer'));
        }
        $this->customAlert('error', CustomMessage::FAILED_TO_GENERATE_CONTROL_NUMBER);
    }

    /**
     * A Safety Measure to Generate a bill that has not been generated
     */
    public function generateBill(){
        try {

            if (empty($this->fee)) {
                $this->customAlert('error', CustomMessage::FEE_NOT_CONFIGURED);
                return;
            }

            if($this->license->type == GeneralConstant::ADD_CLASS){
                $classFactor = 1;
                if ($this->license->type == GeneralConstant::ADD_CLASS) {
                    $classFactor = $this->license->application_license_classes->count() -
                        $this->license->previousApplication->application_license_classes->count();
                }
                $this->generateDLicenseControlNumber($this->license, $this->fee, $classFactor);

            } else {
                $this->generateDLicenseControlNumber($this->license, $this->fee);
            }
            $this->customAlert('success', CustomMessage::RECEIVE_PAYMENT_SHORTLY);
            return redirect(request()->header('Referer'));
        } catch (Exception $e) {
            $this->customAlert('error', CustomMessage::FAILED_TO_GENERATE_CONTROL_NUMBER);
            Log::error('DRIVERS-LICENSE-PAYMENT-FEE-PAYMENT', [$e]);
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