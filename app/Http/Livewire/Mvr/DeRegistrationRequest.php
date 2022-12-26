<?php

namespace App\Http\Livewire\Mvr;

use App\Models\BusinessLocation;
use App\Models\MvrDeRegistrationRequest;
use App\Models\MvrMotorVehicle;
use App\Models\MvrRequestStatus;
use App\Models\Taxpayer;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class DeRegistrationRequest extends Component
{

    use LivewireAlert,WithFileUploads;

    public $motor_vehicle_id;
    public $reason_id;
    public $date_received;
    public $description;
    public $agent_z_number;
    public $agent_name;
    public $agent_id;
    public $inspection_report;
    private ?string $inspection_report_path = null;


    protected function rules()
    {
        return [
            'reason_id' => 'required',
            'description' => 'required',
            'date_received' => 'required|date',
            'agent_id' => 'required',
            'inspection_report'=>'required|mimes:pdf'
        ];
    }

    public function mount($motor_vehicle_id)
    {
        $this->motor_vehicle_id = $motor_vehicle_id;
    }


    public function submit()
    {
        $this->validate();
        try {
            $mv = MvrMotorVehicle::query()->find($this->motor_vehicle_id);
            $inspection_report_path = $this->inspection_report->storePubliclyAs('MVR', "Inspection-Report-{$mv->chassis_number}-".date('YmdHis').'-'.random_int(10000,99999).'.'.$this->inspection_report->extension());
            DB::beginTransaction();

            $de_register_req = MvrDeRegistrationRequest::query()->create([
                'mvr_request_status_id' => MvrRequestStatus::query()->firstOrCreate([
                    'name'=>MvrRequestStatus::STATUS_RC_PENDING_APPROVAL
                ])->id,
                'mvr_agent_id' => $this->agent_id,
                'mvr_motor_vehicle_id'=>$this->motor_vehicle_id,
                'mvr_de_registration_reason_id'=>$this->reason_id,
                'description'=>$this->description,
                'inspection_report_path'=>$inspection_report_path,
                'date_received'=>$this->date_received
            ]);
            DB::commit();
            return redirect()->to(route('mvr.de-register-requests.show', encrypt($de_register_req->id)));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact our support desk for help');
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
        return view('livewire.mvr.de-registration-request-modal');
    }
}
