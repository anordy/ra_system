<?php

namespace App\Http\Livewire\Mvr\Payment;

use App\Enum\GeneralConstant;
use App\Models\MvrFee;
use App\Models\MvrFeeType;
use App\Services\ZanMalipo\GepgResponse;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class DeregistrationFeePayment extends Component
{
    use CustomAlert, PaymentsTrait, GepgResponse;

    public $deregistration, $fee, $feeType;

    public function mount($deregistration)
    {
        $this->deregistration = $deregistration;

        $this->feeType = MvrFeeType::query()->firstOrCreate(['type' => MvrFeeType::TYPE_DE_REGISTRATION]);

        $this->fee = MvrFee::query()->where([
            'mvr_registration_type_id' => $this->deregistration->registration->mvr_registration_type_id,
            'mvr_fee_type_id' => $this->feeType->id,
            'mvr_class_id' => $this->deregistration->registration->mvr_class_id
        ])->first();

    }

    public function refresh()
    {
        $this->deregistration = get_class($this->deregistration)::find($this->deregistration->id);
        if (is_null($this->deregistration)) {
            abort(404);
        }
    }

    public function regenerate()
    {
        $response = $this->regenerateControlNo($this->deregistration->bill);
        if ($response) {
            session()->flash(GeneralConstant::SUCCESS, 'Your request was submitted, you will receive your payment information shortly.');
            return redirect(request()->header('Referer'));
        }
        $this->customAlert(GeneralConstant::ERROR, 'Control number could not be generated, please try again later.');
    }

    /**
     * A Safety Measure to Generate a bill that has not been generated
     */
    public function generateBill()
    {
        try {
            if (empty($this->fee)) {
                $this->customAlert(GeneralConstant::ERROR, "Fee for the selected registration type is not configured");
                return;
            }

            $this->generateMvrDeregistrationControlNumber($this->deregistration, $this->fee);
            $this->customAlert(GeneralConstant::SUCCESS, 'Your request was submitted, you will receive your payment information shortly.');
            return redirect(request()->header('Referer'));
        } catch (Exception $exception) {
            $this->customAlert(GeneralConstant::ERROR, 'Bill could not be generated, please try again later.');
            Log::error('DE-REGISTRATION-FEE-PAYMENT-GN-BILL', [$exception]);
        }
    }

    public function getGepgStatus($code)
    {
        return $this->getResponseCodeStatus($code)['message'];
    }

    public function render()
    {
        return view('livewire.mvr.payment.deregistration-payment');
    }
}