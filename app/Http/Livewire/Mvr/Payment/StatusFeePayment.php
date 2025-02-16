<?php

namespace App\Http\Livewire\Mvr\Payment;

use App\Models\MvrFee;
use App\Models\MvrFeeType;
use App\Models\MvrRegistration;
use App\Models\MvrRegistrationParticularChange;
use App\Models\MvrRegistrationStatusChange;
use App\Services\ZanMalipo\GepgResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use Illuminate\Support\Facades\Log;

class StatusFeePayment extends Component
{
    use CustomAlert, PaymentsTrait, GepgResponse;

    public $motorVehicle, $fee, $feeType;

    public function mount($motorVehicle)
    {
        $this->motorVehicle = $motorVehicle;

        if (get_class($this->motorVehicle) == MvrRegistrationStatusChange::class) {
            $this->feeType = MvrFeeType::query()->firstOrCreate(['type' => MvrFeeType::TYPE_REGISTRATION]);
        } elseif (get_class($this->motorVehicle) == MvrRegistration::class) {
            $this->feeType = MvrFeeType::query()->firstOrCreate(['type' => MvrFeeType::TYPE_REGISTRATION]);
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
            session()->flash('success', 'Your request was submitted, you will receive your payment information shortly.');
            return redirect(request()->header('Referer'));
        }
        $this->customAlert('error', 'Control number could not be generated, please try again later.');
    }

    /**
     * A Safety Measure to Generate a bill that has not been generated
     */
    public function generateBill()
    {
        try {

            if (empty($this->fee)) {
                $this->customAlert('error', "Fee for the selected registration type is not configured");
                DB::rollBack();
                return;
            }

            $this->generateMvrStatusChangeConntrolNumber($this->motorVehicle, $this->fee);
            $this->customAlert('success', 'Your request was submitted, you will receive your payment information shortly.');
            return redirect(request()->header('Referer'));
        } catch (Exception $e) {
            $this->customAlert('error', 'Bill could not be generated, please try again later.');
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    public function getGepgStatus($code)
    {
        return $this->getResponseCodeStatus($code)['message'];
    }

    public function render()
    {
        return view('livewire.mvr.payment.status-fee-payment');
    }
}
