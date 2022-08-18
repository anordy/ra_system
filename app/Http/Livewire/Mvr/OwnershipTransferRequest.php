<?php

namespace App\Http\Livewire\Mvr;

use App\Models\MvrDeRegistrationRequest;
use App\Models\MvrMotorVehicle;
use App\Models\MvrOwnershipTransfer;
use App\Models\MvrOwnershipTransferReason;
use App\Models\MvrRequestStatus;
use App\Models\Taxpayer;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class OwnershipTransferRequest extends Component
{

    use LivewireAlert;

    public $motor_vehicle_id;
    public $category_id;
    public $reason_id;
    public $transfer_reason;
    public $date_received;
    public $sale_date;
    public $date_delivered;
    public $agent_z_number;
    public $agent_name;
    public $owner_z_number;
    public $owner_name;
    public $market_value;
    public $agent_taxpayer_id;
    public $owner_taxpayer_id;


    protected function rules()
    {
        return [
            'reason_id' => 'required',
            'category_id' => 'required',
            'date_received' => 'required|date',
            'date_delivered' => 'required|date',
            'market_value' => 'required',
            'agent_taxpayer_id' => 'required',
            'owner_taxpayer_id' => 'required',
        ];
    }

    public function mount($motor_vehicle_id)
    {
        $this->motor_vehicle_id = $motor_vehicle_id;
    }


    public function submit()
    {
        $rules = $this->rules();
        if (MvrOwnershipTransferReason::query()->firstOrCreate(['name'=> MvrOwnershipTransferReason::TRANSFER_REASON_SOLD])->id != $this->reason_id){
            $this->sale_date = null;
        }else{
            $rules = array_merge($rules,['sale_date' => 'required']);
        }
        if (MvrOwnershipTransferReason::query()->firstOrCreate(['name'=> MvrOwnershipTransferReason::TRANSFER_REASON_OTHER])->id != $this->reason_id){
            $this->transfer_reason = null;
        }else{
            $rules = array_merge($rules,['transfer_reason' => 'required']);
        }

        $this->validate($rules);

        try {
            DB::beginTransaction();

            $ot_req = MvrOwnershipTransfer::query()->create([
                'mvr_request_status_id' => MvrRequestStatus::query()->firstOrCreate([
                    'name'=>MvrRequestStatus::STATUS_RC_INITIATED
                ])->id,
                'agent_taxpayer_id' => $this->agent_taxpayer_id,
                'owner_taxpayer_id' => $this->owner_taxpayer_id,
                'mvr_motor_vehicle_id'=>$this->motor_vehicle_id,
                'mvr_ownership_transfer_reason_id'=>$this->reason_id,
                'mvr_transfer_category_id'=>$this->reason_id,
                'transfer_reason'=>$this->transfer_reason,
                'market_value'=>$this->market_value,
                'sale_date'=>$this->date_received,
                'category_id'=>$this->category_id,
                'delivered_date'=>$this->date_received,
                'application_date'=>Carbon::now(),
            ]);
            DB::commit();
            return redirect()->to(route('mvr.transfer-ownership.show', encrypt($ot_req->id)));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function agentLookup(){
        $agent = Taxpayer::query()->where(['reference_no'=>$this->agent_z_number])->first();
        if (!empty($agent)){
            $this->agent_name = $agent->fullname();
            $this->agent_taxpayer_id = $agent->id;
        }else{
            $this->agent_name = null;
            $this->agent_taxpayer_id = null;
        }
    }

    public function ownerLookup(){
        $owner = Taxpayer::query()->where(['reference_no'=>$this->owner_z_number])->first();
        if (!empty($owner)){
            $this->owner_name = $owner->fullname();
            $this->owner_taxpayer_id = $owner->id;
        }else{
            $this->owner_name = null;
            $this->owner_taxpayer_id = null;
        }
    }

    public function render()
    {
        return view('livewire.mvr.ownership-transfer-request-modal');
    }
}
