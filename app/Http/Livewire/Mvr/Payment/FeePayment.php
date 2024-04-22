<?php

namespace App\Http\Livewire\Mvr\Payment;

use App\Enum\GeneralConstant;
use App\Enum\MvrRegistrationStatus;
use App\Models\MvrFee;
use App\Models\MvrFeeType;
use App\Models\MvrOwnershipTransfer;
use App\Models\MvrRegistration;
use App\Models\MvrRegistrationParticularChange;
use App\Models\MvrRegistrationStatusChange;
use App\Models\MvrTransferFee;
use App\Services\ZanMalipo\GepgResponse;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class FeePayment extends Component
{
    use CustomAlert, PaymentsTrait, GepgResponse;

    public $motorVehicle, $fee, $feeType;

    public function mount($motorVehicle)
    {
        $this->motorVehicle = $motorVehicle;


        if (get_class($this->motorVehicle) == MvrRegistrationStatusChange::class) {
            $this->feeType = MvrFeeType::query()->firstOrCreate(['type' => MvrFeeType::STATUS_CHANGE]);
        } elseif (get_class($this->motorVehicle) == MvrRegistration::class) {
            if ($this->motorVehicle->origin == MvrRegistrationStatus::STATUS_CHANGE) {
                $this->feeType = MvrFeeType::query()->firstOrCreate(['type' => MvrFeeType::STATUS_CHANGE]);
            } else {
                $this->feeType = MvrFeeType::query()->firstOrCreate(['type' => MvrFeeType::TYPE_REGISTRATION]);
            }
        } else if (get_class($this->motorVehicle) == MvrRegistrationParticularChange::class) {
            $this->feeType = MvrFeeType::query()->firstOrCreate(['type' => MvrFeeType::TYPE_CHANGE_REGISTRATION]);
        } else {
            $this->feeType = MvrFeeType::query()->firstOrCreate(['type' => MvrFeeType::TYPE_REGISTRATION]);
        }

        $this->fee = MvrFee::query()->where([
            'mvr_registration_type_id' => $this->motorVehicle->mvr_registration_type_id,
            'mvr_fee_type_id' => $this->feeType->id,
            'mvr_class_id' => $this->motorVehicle->mvr_class_id
        ])->first();

        if (get_class($this->motorVehicle) == MvrOwnershipTransfer::class) {
            $this->fee = MvrTransferFee::query()->where([
                'mvr_transfer_category_id' => $this->motorVehicle->mvr_transfer_category_id,
            ])->first();
        }

    }

    public function refresh()
    {
        $this->motorVehicle = get_class($this->motorVehicle)::find($this->motorVehicle->id);
        if (is_null($this->motorVehicle)) {
            abort(404);
        }
    }

    public function regenerate()
    {
        $response = $this->regenerateControlNo($this->motorVehicle->bill);
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

            if (get_class($this->motorVehicle) != MvrOwnershipTransfer::class) {
                $this->generateMvrControlNumber($this->motorVehicle, $this->fee);
            } else {
                $this->generateMvrTransferOwnershipControlNumber($this->motorVehicle, $this->fee);
            }
            $this->customAlert(GeneralConstant::SUCCESS, 'Your request was submitted, you will receive your payment information shortly.');
            return redirect(request()->header('Referer'));
        } catch (Exception $exception) {
            $this->customAlert(GeneralConstant::ERROR, 'Bill could not be generated, please try again later.');
            Log::error('MVR-FEE-PAYMENT-GN-BILL', [$exception]);
        }
    }

    public function getGepgStatus($code)
    {
        return $this->getResponseCodeStatus($code)['message'];
    }

    public function render()
    {
        return view('livewire.mvr.payment.payment');
    }
}