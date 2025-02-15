<?php

namespace App\Http\Livewire\Mvr;

use App\Enum\GeneralConstant;
use App\Models\MvrDeregistration;
use App\Models\MvrFee;
use App\Models\MvrFeeType;
use App\Models\MvrOwnershipTransfer;
use App\Models\MvrRegistration;
use App\Models\MvrRegistrationParticularChange;
use App\Models\MvrRegistrationStatusChange;
use App\Models\MvrReorderPlateNumber;
use App\Models\MvrReorderPlateNumberFee;
use App\Services\ZanMalipo\GepgResponse;
use App\Traits\CustomAlert;
use App\Traits\MvrRegistrationTrait;
use App\Traits\PaymentsTrait;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class FeePayment extends Component
{
    use CustomAlert, PaymentsTrait, GepgResponse, MvrRegistrationTrait;

    public $motorVehicle, $fee, $feeType;

    public function mount($motorVehicle)
    {
        $this->motorVehicle = $motorVehicle;
        $this->getFee();
    }

    public function getFee()
    {
        switch (get_class($this->motorVehicle)) {
            case MvrRegistration::class:
                $this->feeType = MvrFeeType::query()->firstOrCreate(['type' => MvrFeeType::TYPE_REGISTRATION]);

                $this->fee = MvrFee::query()->where([
                    'mvr_registration_type_id' => $this->motorVehicle->mvr_registration_type_id,
                    'mvr_class_id' => $this->motorVehicle->mvr_class_id,
                    'mvr_fee_type_id' => $this->feeType->id,
                    'mvr_plate_number_type_id' => $this->motorVehicle->mvr_plate_number_type_id
                ])->first();
                break;

            case MvrRegistrationStatusChange::class:
                $this->feeType = MvrFeeType::query()->firstOrCreate(['type' => MvrFeeType::STATUS_CHANGE]);
                $this->fee = MvrFee::query()->where([
                    'mvr_registration_type_id' => $this->motorVehicle->mvr_registration_type_id,
                    'mvr_class_id' => $this->motorVehicle->mvr_class_id,
                    'mvr_fee_type_id' => $this->feeType->id,
                    'mvr_plate_number_type_id' => $this->motorVehicle->mvr_plate_number_type_id
                ])->first();
                break;

                case MvrReorderPlateNumber::class:
                    $payload = [
                        'quantity' => $this->motorVehicle->quantity,
                         'is_rfid' => $this->motorVehicle->is_rfid
                         ];
                    $this->fee = MvrReorderPlateNumberFee::query()->where($payload)->first();
                    break;

            case MvrOwnershipTransfer::class:
                $this->feeType = MvrFeeType::query()->firstOrCreate(['type' => MvrFeeType::TRANSFER_OWNERSHIP]);

                $this->fee = MvrFee::query()->where([
                    'mvr_registration_type_id' => $this->motorVehicle->motor_vehicle->mvr_registration_type_id,
                    'mvr_class_id' => $this->motorVehicle->motor_vehicle->mvr_class_id,
                    'mvr_fee_type_id' => $this->feeType->id,
                    'mvr_plate_number_type_id' => $this->motorVehicle->motor_vehicle->mvr_plate_number_type_id
                ])->first();
                break;

            case MvrRegistrationParticularChange::class:
                $this->feeType = MvrFeeType::query()->firstOrCreate(['type' => MvrFeeType::TYPE_CHANGE_REGISTRATION]);

                $this->fee = MvrFee::query()->where([
                    'mvr_registration_type_id' => $this->motorVehicle->mvr_registration_type_id,
                    'mvr_class_id' => $this->motorVehicle->mvr_class_id,
                    'mvr_fee_type_id' => $this->feeType->id,
                    'mvr_plate_number_type_id' => $this->motorVehicle->mvr_plate_number_type_id
                ])->first();
                break;

            case MvrDeregistration::class:
                $this->feeType = MvrFeeType::query()->firstOrCreate(['type' => MvrFeeType::TYPE_DE_REGISTRATION]);
            
                $this->fee = MvrFee::query()->where([
                    'mvr_registration_type_id' => $this->motorVehicle->registration->mvr_registration_type_id,
                    'mvr_fee_type_id' => $this->feeType->id,
                    'mvr_class_id' => $this->motorVehicle->registration->mvr_class_id,
                    'mvr_plate_number_type_id' => $this->motorVehicle->registration->mvr_plate_number_type_id
                ])->first();
                break;

            default:
                $this->fee = null;
                $this->feeType = null;
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
        if (is_null($this->motorVehicle->latestBill)) {
            $this->customAlert(GeneralConstant::ERROR, 'No bill found for this request, please try again later.');
            return;
        }

        $response = $this->regenerateControlNo($this->motorVehicle->latestBill);
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
            switch (get_class($this->motorVehicle)){
                case MvrRegistration::class:
                    case MvrRegistrationStatusChange::class:
                        case MvrReorderPlateNumber::class:
                            case MvrRegistrationParticularChange::class:
                    $this->generateMvrControlNumber($this->motorVehicle, $this->fee);
                    break;

                case MvrOwnershipTransfer::class:
                    $this->generateMvrTransferOwnershipControlNumber($this->motorVehicle, $this->fee);
                    break;

                case MvrDeregistration::class:
                    $this->generateMvrDeregistrationControlNumber($this->motorVehicle, $this->fee);
                    break;

                default:
                    throw new Exception("Failed to find MVR registration type.");
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

    public function processRegistrationPlateNumber()
    {
        try {
            $this->updateNextPlateNumber($this->motorVehicle->regtype, $this->motorVehicle->class, $this->motorVehicle);
        } catch (Exception $e) {
            Log::error('MVR-FEE-PAYMENT-PROCESS-PLATE-NUMBER', [$e]);
            $this->customAlert(GeneralConstant::ERROR, 'Failed to Generate Plate Number, please try again later.');
        }
    }

    public function render()
    {
        return view('livewire.mvr.fee-payment');
    }
}