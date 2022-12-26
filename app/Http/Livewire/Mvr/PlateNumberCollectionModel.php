<?php

namespace App\Http\Livewire\Mvr;

use App\Models\BusinessLocation;
use App\Models\MvrDeRegistrationRequest;
use App\Models\MvrMotorVehicle;
use App\Models\MvrMotorVehicleRegistration;
use App\Models\MvrOwnershipTransfer;
use App\Models\MvrOwnershipTransferReason;
use App\Models\MvrPlateNumberCollection;
use App\Models\MvrPlateNumberStatus;
use App\Models\MvrRegistrationStatus;
use App\Models\MvrRequestStatus;
use App\Models\Taxpayer;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class PlateNumberCollectionModel extends Component
{

    use LivewireAlert;

    public $mvr_registration_id;
    public $collection_date;
    public $collector_name;
    public $collector_phone;

    protected function rules()
    {
        return [
            'collector_phone' => 'required|between:9,15',
            'collector_name' => 'required',
            'collection_date' => 'required|date|before_or_equal:today',
        ];
    }

    public function mount($mvr_registration_id)
    {
        $this->mvr_registration_id = $mvr_registration_id;
    }


    public function submit()
    {
        $this->validate();
        try {
            DB::beginTransaction();
            MvrPlateNumberCollection::query()->create([
                'mvr_registration_id' => $this->mvr_registration_id,
                'collector_name' => $this->collector_name,
                'collector_phone' => $this->collector_phone,
                'collection_date'=>$this->collection_date,
            ]);

            $plate_status = MvrPlateNumberStatus::query()->firstOrCreate(['name'=>MvrPlateNumberStatus::STATUS_ACTIVE]);
            $mvr = MvrMotorVehicleRegistration::query()->find($this->mvr_registration_id);
            $mvr->update([
                'mvr_plate_number_status_id' => $plate_status->id
            ]);
            MvrMotorVehicleRegistration::query()
                ->where(['mvr_motor_vehicle_id'=>$mvr->mvr_motor_vehicle_id])
                ->whereKeyNot($mvr->id)
                ->update([
                    'mvr_plate_number_status_id' =>  MvrPlateNumberStatus::query()->firstOrCreate(['name'=>MvrPlateNumberStatus::STATUS_RETIRED])->id
                ]);
            //update registration status
            $reg_status = MvrRegistrationStatus::query()->firstOrCreate(['name'=>MvrRegistrationStatus::STATUS_REGISTERED]);
            MvrMotorVehicle::query()
                ->find($mvr->mvr_motor_vehicle_id)
                ->update([
                    'mvr_registration_status_id'=>$reg_status->id
                ]);
            DB::commit();
            return redirect()->to(route('mvr.plate-numbers'));
        } catch (Exception $e) {
            report($e);
            $this->alert('error', 'Something went wrong, Please contact our support desk for help');
            DB::rollBack();
        }
    }

    public function render()
    {
        return view('livewire.mvr.plate-number-collection-modal');
    }
}
