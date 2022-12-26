<?php

namespace App\Http\Livewire\RoadInspectionOffence;

use App\Models\Bank;
use App\Models\BusinessLocation;
use App\Models\DlDriversLicense;
use App\Models\MvrFee;
use App\Models\MvrFeeType;
use App\Models\MvrMotorVehicle;
use App\Models\MvrMotorVehicleRegistration;
use App\Models\MvrPlateNumberStatus;
use App\Models\MvrRegistrationChangeRequest;
use App\Models\MvrRegistrationStatus;
use App\Models\MvrRegistrationType;
use App\Models\MvrRequestStatus;
use App\Models\RioRegister;
use App\Models\RioRegisterOffence;
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

class RegisterCreate extends Component
{

    use LivewireAlert;

    public $plate_number;
    public $lin;
    public $license;
    public $block_type;
    public $mvr;
    public $license_lookup_fired = false;
    public $plate_lookup_fired = false;
    public $offences = [];

    public function submit()
    {
        if (empty($this->mvr)){
            $this->alert('error', 'Not provided a valid plate number');
            return;
        }else if (empty($this->license)){
            $this->alert('error', 'Not provided a valid license number');
            return;
        }else if (empty($this->offences)){
            $this->alert('error', 'Offenses not selected');
            return;
        }else if (empty($this->block_type)){
            $this->alert('error', 'Select Restriction Type');
            return;
        }

        try {
            DB::beginTransaction();
            $register = RioRegister::query()->create([
                'dl_drivers_license_owner_id'=>$this->license->dl_drivers_license_owner_id,
                'mvr_motor_vehicle_registration_id'=>$this->mvr->id,
                'date'=>Carbon::now(),
                'block_type' => $this->block_type,
                'block_status'=>($this->block_type == 'NONE'?null:'ACTIVE'),
                'created_by'=>auth()->user()->id
            ]);
            foreach ($this->offences as $offence){
                RioRegisterOffence::query()->create([
                   'rio_register_id'=> $register->id,
                   'rio_offence_id'=> $offence,
                ]);
            }
            DB::commit();
            redirect()->to(route('rio.register.show', encrypt($register->id)));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact our support desk for help');
        }
    }

    public function licenseLookup(){
        $this->license = DlDriversLicense::query()->where(['license_number'=>$this->lin])->first();
        $this->license_lookup_fired = true;
    }

    public function plateNumberLookup(){
        $this->mvr = MvrMotorVehicleRegistration::query()->where(['plate_number'=>$this->plate_number])->first();
        $this->plate_lookup_fired = true;
    }


    public function render()
    {
        return view('livewire.road-inspection-offence.register-create');
    }
}
