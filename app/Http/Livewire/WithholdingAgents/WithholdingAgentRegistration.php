<?php

namespace App\Http\Livewire\WithholdingAgents;


use Exception;
use App\Models\Ward;
use App\Models\Region;
use App\Events\SendSms;
use Livewire\Component;
use App\Events\SendMail;
use App\Models\District;
use App\Models\Taxpayer;
use App\Models\WithholdingAgent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;


class WithholdingAgentRegistration extends Component
{
    use LivewireAlert;

    public $regions = [];
    public $districts = [];
    public $responsible_persons = [];
    public $wards = [];
    public $region_id;
    public $district_id;
    public $ward_id;
    public $tin;
    public $institution_name;
    public $institution_place;
    public $address;
    public $mobile;
    public $email;
    public $responsible_person_id;
    public $officer_id;
    public $title;
    public $position;
    public $date_of_commencing;


    protected $rules = [
        'tin' => 'required|integer',
        'institution_name' => 'required',
        'institution_place' => 'required',
        'email' => 'required|email|unique:withholding_agents,email',
        'mobile' => 'required|unique:withholding_agents,mobile|size:10',
        'address' => 'required',
        'responsible_person_id' => 'required',
        'region_id' => 'required',
        'district_id' => 'required',
        'ward_id' => 'required',
        'title' => 'required',
        'position' => 'required',
        'date_of_commencing' => 'required'
    ];

    public function mount()
    {
        $this->regions = Region::select('id', 'name')->get();
        $this->responsible_persons = Taxpayer::select('id', 'first_name', 'middle_name', 'last_name')->get();
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'region_id') {
            $this->districts = District::where('region_id', $this->region_id)->select('id', 'name')->get();
            $this->wards = [];
        }

        if ($propertyName === 'district_id') {
            $this->wards = [];
            $this->wards = Ward::where('district_id', $this->district_id)->select('id', 'name')->get();
        }
    }

    public function submit()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $withholding_agent = [
                'wa_number' => mt_rand(1000000000,9999999999),
                'tin' => $this->tin,
                'institution_name' => $this->institution_name,
                'institution_place' => $this->institution_place,
                'email' => $this->email,
                'mobile' => $this->mobile,
                'address' => $this->address,
                'date_of_commencing' => $this->date_of_commencing,
                'region_id' => $this->region_id,
                'district_id' => $this->district_id,
                'ward_id' => $this->ward_id,
            ];

            $withholding_agent = WithholdingAgent::create($withholding_agent);
            $withholding_agent_resp_person_data = [
                'responsible_person_id' => $this->responsible_person_id,
                'title' => $this->title,
                'position' => $this->position,
                'officer_id' => auth()->user()->id,
            ];
            $withholding_agent_resp_person = $withholding_agent->responsiblePersons()->create($withholding_agent_resp_person_data);
            
            DB::commit();

            event(new SendMail('withholding_agent_registration', $withholding_agent_resp_person->id));
            event(new SendSms('withholding_agent_registration', $withholding_agent_resp_person->id));

            return redirect()->to('/withholdingAgents/list')->with('success', "A notification for successful registration of a withholding agent for {$this->institution_name} has been sent to the responsible person.");
         
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            $this->alert('error', 'Something went wrong');
        }
    }


    public function render()
    {
        return view('livewire.withholding-agents.register');
    }
}