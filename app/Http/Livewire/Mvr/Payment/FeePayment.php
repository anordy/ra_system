<?php

namespace App\Http\Livewire\Mvr\Payment;

use App\Models\MvrFee;
use App\Models\MvrFeeType;
use App\Models\MvrPlateNumberStatus;
use App\Models\MvrRegistration;
use App\Models\MvrRegistrationType;
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

        $this->feeType = MvrFeeType::query()->firstOrCreate(['type' => MvrFeeType::TYPE_REGISTRATION]);

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

            $this->generateMvrControlNumber($this->motorVehicle, $this->fee);
            $this->customAlert('success', 'Your request was submitted, you will receive your payment information shortly.');
            return redirect(request()->header('Referer'));
        } catch (Exception $e) {
            $this->customAlert('error', 'Bill could not be generated, please try again later.');
            Log::error($e);
        }
    }


    public function processRegistrationPlateNumber()
    {
        $regType = $this->motorVehicle->regtype;
        try {
            DB::beginTransaction();

            if ($regType->name == MvrRegistrationType::TYPE_PRIVATE_GOLDEN
                || $regType->name == MvrRegistrationType::TYPE_PRIVATE_PERSONALIZED
                || $regType->name == MvrRegistrationType::TYPE_DIPLOMATIC) {
                $plateNumber = $this->motorVehicle->plate_number;
            } else if ($regType->external_defined != 1) {
                $plateNumber = MvrRegistration::getNexPlateNumber($regType, $this->motorVehicle->class);

                if (!$plateNumber) {
                    $this->customAlert('warning', 'Failed to generate plate number, please make sure initial plate number for this registration has been created');
                    return;
                }
            } else {
                throw new Exception('Invalid mvr registration type');
            }

            $this->motorVehicle->update([
                'plate_number' => $this->motorVehicle->plate_number ?? $plateNumber,
                'registration_number' => 'Z-' . str_pad($this->motorVehicle->id, 6, "0", STR_PAD_LEFT),
                'mvr_plate_number_status' => MvrPlateNumberStatus::STATUS_GENERATED,
                'registered_at' => date('Y-m-d')
            ]);

            DB::commit();

            return redirect(request()->header('Referer'));

            // TODO: Send Registration status as a job
//            $this->mvrService->postPlateNumber($this->motorVehicle->chassis->chassis_number, $plateNumber, 'registration');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, Please contact administrator for support');
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