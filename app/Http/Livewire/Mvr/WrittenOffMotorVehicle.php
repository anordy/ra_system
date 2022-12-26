<?php

namespace App\Http\Livewire\Mvr;

use App\Models\BusinessLocation;
use App\Models\MvrDeRegistrationRequest;
use App\Models\MvrMotorVehicle;
use App\Models\MvrPlateNumberStatus;
use App\Models\MvrRegistrationStatus;
use App\Models\MvrRequestStatus;
use App\Models\MvrWrittenOff;
use App\Models\Taxpayer;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class WrittenOffMotorVehicle extends Component
{

    use LivewireAlert,WithFileUploads;


    public $date;
    public $motor_vehicle_id;


    protected function rules()
    {
        return [
            'date' => 'required|date'
        ];
    }

    public function mount($motor_vehicle_id)
    {
        $this->motor_vehicle_id = $motor_vehicle_id;
    }


    public function submit()
    {
        $this->validate();
        $plate_status = MvrPlateNumberStatus::query()->firstOrCreate(['name' => MvrPlateNumberStatus::STATUS_RETIRED]);

        try {
            DB::beginTransaction();
            $mvwo = MvrWrittenOff::query()->create([
                'date' => $this->date,
                'mvr_motor_vehicle_id'=>$this->motor_vehicle_id,
            ]);
            $mvwo->motor_vehicle->update(['mvr_registration_status_id'=>MvrRegistrationStatus::query()->firstOrCreate(['name'=>MvrRegistrationStatus::STATUS_DE_REGISTERED])->id]);
            $mvr_reg = $mvwo->motor_vehicle->current_registration;
            $mvr_reg->update(['mvr_plate_number_status_id'=>$plate_status->id]);
            DB::commit();
            DB::commit();
            return redirect()->to(route('mvr.written-off'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact the administrator for help');
        }
    }



    public function render()
    {
        return view('livewire.mvr.written-off-modal');
    }
}
