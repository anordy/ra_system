<?php

namespace App\Http\Livewire\Mvr;

use App\Models\Bank;
use App\Models\BusinessLocation;
use App\Models\MvrAgent;
use App\Models\MvrFee;
use App\Models\MvrFeeType;
use App\Models\MvrMotorVehicle;
use App\Models\MvrMotorVehicleRegistration;
use App\Models\MvrPersonalizedPlateNumberRegistration;
use App\Models\MvrPlateNumberStatus;
use App\Models\MvrRegistrationStatus;
use App\Models\MvrRegistrationType;
use App\Models\Taxpayer;
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

class AgentRegistration extends Component
{

    use CustomAlert;


    public $zin;
    public $taxpayer;
    public $lookup_fired = false;


    protected function rules()
    {
        return [
            'zin' => 'required|strip_tag',
        ];
    }


    public function submit()
    {
        $this->validate();
        if (empty($this->taxpayer)){
            $this->customAlert('error', 'Please provide valid Z-Number and confirm details by doing lookup');
            return;
        }
        if (!empty($this->taxpayer->transport_agent)){
            $this->customAlert('error', 'This taxpayer has already been registered as Transport Agent');
            return;
        }
        try {
            DB::beginTransaction();
            $last_agent = MvrAgent::query()->lockForUpdate()->latest()->first();
            if (!empty($last_agent)){
                $last_number = preg_replace('/ZTA\d{2}(\d{6})/','$1',$last_agent->agent_number);
                $yy = preg_replace('/ZTA(\d{2})(\d{6})/','$1',$last_agent->agent_number);
                if ($yy!=date('y')){
                    $last_number = 1;
                }
            }else{
                $last_number = 1;
            }
            $agent_number = 'ZTA'.date('y').sprintf('%06s',$last_number+1);
            MvrAgent::query()->create(
                [
                    'taxpayer_id'=>$this->taxpayer->id,
                    'registration_date'=>Carbon::now(),
                    'agent_number'=>$agent_number,
                    'status'=>'ACTIVE'
                ]
            );
            DB::commit();
            return redirect()->to(route('mvr.agent'));
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.mvr.agent-registration');
    }

    public function lookup(){
        $this->lookup_fired = true;
        $this->taxpayer = Taxpayer::query()->where(['reference_no'=>$this->zin])
            ->first();
    }
}
