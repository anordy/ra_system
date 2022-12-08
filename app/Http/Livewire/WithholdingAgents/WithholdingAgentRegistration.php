<?php

namespace App\Http\Livewire\WithholdingAgents;


use Exception;
use App\Models\Ward;
use App\Models\Region;
use App\Events\SendSms;
use Livewire\Component;
use App\Events\SendMail;
use App\Models\Business;
use App\Models\District;
use App\Models\Taxpayer;
use App\Models\WithholdingAgent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;


class WithholdingAgentRegistration extends Component
{
    use LivewireAlert;

    public $regions = [];
    public $districts = [];
    public $responsible_persons = [];
    public $wards = [];
    public $region_id, $district_id, $ward_id, $tin, $institution_name, $institution_place, $address;
    public $fax, $alt_mobile, $mobile, $email, $responsible_person_id, $officer_id, $title, $position, $date_of_commencing;
    public $reference_no;
    public $search_triggered = false;
    public $taxpayer, $ztnNumber;

    protected $rules = [
        'tin' => 'required|integer|min:8',
        'institution_name' => 'required',
        'institution_place' => 'required',
        'email' => 'required|email|unique:withholding_agents,email',
        'mobile' => 'required|unique:withholding_agents,mobile|digits_between:10,10',
        'alt_mobile' => 'nullable|unique:withholding_agents,alt_mobile|digits_between:10,10',
        'fax' => 'nullable',
        'address' => 'required',
        'responsible_person_id' => 'required',
        'region_id' => 'required',
        'district_id' => 'required',
        'ward_id' => 'required',
        'title' => 'required',
        'position' => 'required',
        'date_of_commencing' => 'required',
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
        if (!Gate::allows('withholding-agents-registration')) {
            abort(403);
        }
        $this->validate();
        DB::beginTransaction();
        try {
            $withholding_agent = [
                'wa_number' => mt_rand(1000000000, 9999999999),
                'tin' => $this->tin,
                'institution_name' => $this->institution_name,
                'institution_place' => $this->institution_place,
                'email' => $this->email,
                'mobile' => $this->mobile,
                'fax' => $this->fax,
                'alt_mobile' => $this->alt_mobile,
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
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function searchResponsibleDetails()
    {
        $this->validate(['reference_no' => 'required']);

        $taxpayer = Taxpayer::where(['reference_no' => $this->reference_no])->first();

        if (!empty($taxpayer)) {
            $this->taxpayer = $taxpayer;
            $this->responsible_person_id = $this->taxpayer->id ?? null;
        } else {
            $this->taxpayer = null;
        }

        $this->search_triggered = true;
        
    }

    public function render()
    {
        return view('livewire.withholding-agents.register');
    }
}
