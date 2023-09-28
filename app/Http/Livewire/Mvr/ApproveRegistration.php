<?php

namespace App\Http\Livewire\Mvr;

use App\Models\Bank;
use App\Models\MvrFee;
use App\Models\MvrFeeType;
use App\Models\MvrMotorVehicle;
use App\Models\MvrMotorVehicleRegistration;
use App\Models\MvrPersonalizedPlateNumberRegistration;
use App\Models\MvrPlateNumberStatus;
use App\Models\MvrRegistrationStatus;
use App\Models\MvrRegistrationType;
use App\Models\Tra\Tin;
use App\Models\ZmBill;
use App\Services\TRA\ServiceRequest;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Traits\CustomAlert;
use Livewire\Component;

class ApproveRegistration extends Component
{

    use CustomAlert;

    public $motor_vehicle_id;
    public $registration_type_id;
    public $plate_number_size_id;
    public $plate_number;
    public $agent_z_number;


    protected function rules()
    {
        return [
            'registration_type_id' => 'required|strip_tag',
            'plate_number_size_id' => 'required|strip_tag',
            'plate_number' => 'nullable|unique:mvr_motor_vehicle_registration,plate_number|strip_tag',
        ];
    }

    public function mount($motor_vehicle_id)
    {
        $this->motor_vehicle_id = $motor_vehicle_id;
    }


    public function submit()
    {
        $this->validate();
        $plate_status = MvrPlateNumberStatus::query()->firstOrCreate(['name' => MvrPlateNumberStatus::STATUS_NOT_ASSIGNED]);
        $personalized = null;
        if($this->registration_type_id == MvrRegistrationType::query()->where(['name'=> MvrRegistrationType::TYPE_PRIVATE_PERSONALIZED])->first()->id){
            $personalized = $this->plate_number;
            $this->plate_number = null;
        }elseif(!(MvrRegistrationType::query()->whereIn('name',[
                MvrRegistrationType::TYPE_PRIVATE_PERSONALIZED,
                MvrRegistrationType::TYPE_PRIVATE_GOLDEN,
                MvrRegistrationType::TYPE_DIPLOMATIC
            ])->where(['id'=>$this->registration_type_id])->exists() || MvrRegistrationType::query()->find($this->registration_type_id)->external_defined==1)) {
            $this->plate_number = null;
        }
        try {
            DB::beginTransaction();
            $registration_id = MvrMotorVehicleRegistration::query()->create([
                'mvr_plate_size_id' => $this->plate_number_size_id,
                'mvr_plate_number_status_id' => $plate_status->id,
                'mvr_motor_vehicle_id' => $this->motor_vehicle_id,
                'plate_number' => $this->plate_number,
                'mvr_registration_type_id' => $this->registration_type_id
            ])->id;

            if (!empty($personalized)){
                MvrPersonalizedPlateNumberRegistration::query()->create([
                    'plate_number'=>$personalized,
                    'status'=>'PENDING',
                    'mvr_motor_vehicle_registration_id'=>$registration_id
                ]);
            }
            /** @var MvrMotorVehicle $mv */
            $mv = MvrMotorVehicle::query()->find($this->motor_vehicle_id);
            $mv->update([
                'mvr_registration_status_id' => MvrRegistrationStatus::query()->firstOrCreate([
                    'name' => MvrRegistrationStatus::STATUS_PENDING_PAYMENT
                ])->id
            ]);

            //Generate control number
            $registration = MvrMotorVehicleRegistration::query()->find($registration_id);
            $fee_type = MvrFeeType::query()->firstOrCreate(['type' => 'Registration']);

            $fee = MvrFee::query()->where([
                'mvr_registration_type_id' => $this->registration_type_id,
                'mvr_fee_type_id' => $fee_type->id,
            ])->first();

            if (empty($fee)) {
                $this->customAlert('error', "Registration fee for selected registration type is not configured");
                DB::rollBack();
                Log::error($fee);
                return;
            }
            $exchange_rate = 1;
            $amount = $fee->amount;
            $gfs_code = $fee->gfs_code;

            $tin = Tin::where('tin', $mv->chassis->importer_tin)->first();

            if (!$tin) {
                $this->customAlert('error', 'Importer TIN information is not verified');
                return;
            }

            $zmBill = ZmCore::createBill(
                $registration->id,
                get_class($registration),
                6,
                $tin->tin,
                'TIN',
                $mv->chassis->importer_name,
                $tin->email,
                ZmCore::formatPhone($tin->mobile),
                Carbon::now()->addDays(7)->format('Y-m-d H:i:s'),
                $fee->description,
                ZmCore::PAYMENT_OPTION_EXACT,
                'TZS',
                1,
                auth()->user()->id,
                get_class(auth()->user()),
                [
                    [
                        'billable_id' => $registration->id,
                        'billable_type' => get_class($registration),
                        'fee_id' => $fee->id,
                        'fee_type' => get_class($fee),
                        'tax_type_id' => 6,
                        'amount' => $amount,
                        'currency' => 'TZS',
                        'exchange_rate' => $exchange_rate,
                        'equivalent_amount' => $exchange_rate * $amount,
                        'gfs_code' => $gfs_code
                    ]
                ]
            );
            if (config('app.env') != 'local') {
                $response = ZmCore::sendBill($zmBill->id);
                if ($response->status === ZmResponse::SUCCESS) {
                    session()->flash('success', 'A control number request was sent successful.');
                } else {
                    session()->flash('error', 'Control number generation failed, try again later');
                }
            }else {
                $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $zmBill->zan_status = 'pending';
                $zmBill->control_number = rand(2000070001000, 2000070009999);
                $zmBill->save();
                $this->flash('success', 'A control number for this verification has been generated successfully');
            }
            DB::commit();
            return redirect()->to(route('mvr.show', encrypt($this->motor_vehicle_id)));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.mvr.approve-registration-modal');
    }
}
