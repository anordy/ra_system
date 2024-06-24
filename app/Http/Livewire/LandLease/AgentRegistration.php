<?php

namespace App\Http\Livewire\LandLease;

use App\Models\LandLeaseAgent;
use App\Models\Taxpayer;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Traits\CustomAlert;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;

class AgentRegistration extends Component
{

    use CustomAlert;


    public $taxpayerRefNo;
    public $taxpayer;
    public $lookup_fired = false;


    protected function rules()
    {
        return [
            'taxpayerRefNo' => 'exists:taxpayers,reference_no|required',
        ];
    }

    protected $messages = [
        'taxpayerRefNo.exists' => 'The Taxpayer reference Number is invalid',
    ];

    public function submit()
    {
        $this->validate();
        if(!Gate::allows('land-lease-register-agent')){
            abort(403);
        }
        if(LandLeaseAgent::where('taxpayer_id',$this->taxpayer->id)->exists()){
            $this->customAlert('error', 'Taxpayer already registered as Agent');
            return; 
        }
        try {
            DB::beginTransaction();
            $last_agent = LandLeaseAgent::query()->lockForUpdate()->latest()->first();
            if (!empty($last_agent)){
                $last_number = preg_replace('/LLA\d{4}(\d{4})/','$1',$last_agent->agent_number);
                $yy = preg_replace('/LLA(\d{4})(\d{4})/','$1',$last_agent->agent_number);
                $last_number+=1;
                if ($yy!=Carbon::now()->format('Y')){
                    $last_number = 1;
                }   
            }else{
                $last_number = 1;
            }
            $agent_number = 'LLA'.Carbon::now()->format('Y').sprintf('%04s',$last_number);
            LandLeaseAgent::query()->insert(
                [
                    'taxpayer_id'=>$this->taxpayer->id,
                    'agent_number'=>$agent_number,
                    'status'=>'ACTIVE',
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ]
            );
            DB::commit();
            $this->customAlert('success', 'Agent Registered Succesfully');
            return redirect()->route('land-lease.agents');
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.land-lease.agent-registration');
    }

    public function lookup(){
        $this->taxpayer = Taxpayer::query()->where('reference_no',$this->taxpayerRefNo)->first();
        $this->lookup_fired = true;
    }
}
