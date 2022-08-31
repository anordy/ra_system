<?php

namespace App\Http\Livewire\Mvr;

use App\Models\Bank;
use App\Models\BusinessLocation;
use App\Models\MvrFee;
use App\Models\MvrFeeType;
use App\Models\MvrMotorVehicle;
use App\Models\MvrMotorVehicleRegistration;
use App\Models\MvrPlateNumberStatus;
use App\Models\MvrRegistrationChangeRequest;
use App\Models\MvrRegistrationStatus;
use App\Models\MvrRegistrationType;
use App\Models\MvrRequestStatus;
use App\Models\Taxpayer;
use App\Models\ZmBill;
use App\Services\TRA\ServiceRequest;
use App\Services\ZanMalipo\ZmCore;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class RegistrationChangeRequest extends Component
{

    use LivewireAlert;

    public $motor_vehicle_id;
    public $registration_type_id;
    public $plate_number_size_id;
    public $custom_plate_number;
    public $agent_z_number;
    public $agent_name;
    public $agent_id;


    protected function rules()
    {
        return [
            'registration_type_id' => 'required',
            'plate_number_size_id' => 'required',
            'agent_id' => 'required',
            'custom_plate_number' => 'nullable|unique:mvr_motor_vehicle_registration,plate_number',
        ];
    }

    public function mount($motor_vehicle_id)
    {
        $this->motor_vehicle_id = $motor_vehicle_id;
    }


    public function submit()
    {
        $this->validate();
        $mv = MvrMotorVehicle::query()->find($this->motor_vehicle_id);

        try {
            DB::beginTransaction();
            $change_req = MvrRegistrationChangeRequest::query()->create([
                'mvr_plate_size_id' => $this->plate_number_size_id,
                'current_registration_id' => $mv->current_registration->id,
                'custom_plate_number' => $this->custom_plate_number ?? '',
                'requested_registration_type_id' => $this->registration_type_id,
                'date' => Carbon::now(),
                'mvr_request_status_id' => MvrRequestStatus::query()->firstOrCreate([
                    'name'=>MvrRequestStatus::STATUS_RC_PENDING_APPROVAL
                ])->id,
                'mvr_agent_id' => $this->agent_id
            ]);
            DB::commit();
            return redirect()->to(route('mvr.reg-change-requests.show', encrypt($change_req->id)));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function agentLookup(){
        $agent = Taxpayer::query()->where(['reference_no'=>$this->agent_z_number])->first();
        if (!empty($agent->transport_agent)){
            $this->agent_name = $agent->fullname();
            $this->agent_id = $agent->transport_agent->id;
        }else{
            $this->agent_name = null;
            $this->agent_id = null;
        }
    }

    public function render()
    {
        return view('livewire.mvr.registration-change-modal');
    }
}
