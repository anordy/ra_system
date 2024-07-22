<?php

namespace App\Http\Livewire\WithholdingAgents;


use App\Models\WithholdingAgentResponsibleTitle;
use Exception;
use App\Models\Ward;
use App\Models\Region;
use App\Models\Street;
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
use App\Traits\CustomAlert;
use Livewire\WithFileUploads;

class WithholdingAgentRegistration extends Component
{
    use CustomAlert, WithFileUploads;

    public $regions = [];
    public $districts = [];
    public $responsible_persons = [], $titles = [];
    public $wards = [];
    public $streets = [];
    public $region_id, $district_id, $ward_id, $street_id, $tin, $institution_name, $institution_place, $address;
    public $fax, $alt_mobile, $mobile, $email, $responsible_person_id, $title, $position, $date_of_commencing;
    public $reference_no;
    public $search_triggered = false;
    public $taxpayer, $ztnNumber;
    public $approval_letter;
    public $business;

    protected $rules = [
        'tin' => 'required|numeric|digits:9',
        'institution_name' => 'required|strip_tag|alpha_num_space',
        'institution_place' => 'required|strip_tag|alpha_num_space',
        'email' => 'required|email|unique:withholding_agents,email',
        'mobile' => 'required|unique:withholding_agents,mobile|digits_between:10,10',
        'alt_mobile' => 'nullable|unique:withholding_agents,alt_mobile|digits_between:10,10',
        'fax' => 'nullable|strip_tag|alpha_num_space',
        'address' => 'required|strip_tag|alpha_num_space',
        'responsible_person_id' => 'required|numeric',
        'region_id' => 'required|numeric|exists:regions,id',
        'district_id' => 'required|numeric|exists:districts,id',
        'ward_id' => 'required|numeric|exists:wards,id',
        'street_id' => 'required|numeric|exists:streets,id',
        'title' => 'required|strip_tag|alpha_num_space',
        'approval_letter' => 'required|mimes:pdf|max:1024|max_file_name_length:100',
        'position' => 'required|strip_tag|alpha_num_space',
        'date_of_commencing' => 'required|strip_tag|date',
    ];

    public function mount()
    {
        $this->regions = Region::select('id', 'name')->get();
        $this->responsible_persons = Taxpayer::select('id', 'first_name', 'middle_name', 'last_name')->get();
        $this->titles = WithholdingAgentResponsibleTitle::select('id', 'name')->get();
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'region_id') {
            $this->districts = District::where('region_id', $this->region_id)->select('id', 'name')->get();
            $this->wards = [];
        }

        if ($propertyName === 'district_id') {
            $this->wards = Ward::where('district_id', $this->district_id)->select('id', 'name')->get();
        }

        if ($propertyName === 'ward_id') {
            $this->streets = Street::where('ward_id', $this->ward_id)->select('id', 'name')->get();
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
                'street_id' => $this->street_id,
                'approval_letter' => $this->approval_letter->store('/withholding_agents/approval_letter', "local-admin"),
            ];

            $withholding_agent = WithholdingAgent::create($withholding_agent);

            if (!$withholding_agent) throw new Exception('Failed to save withholding agent data');

            $withholding_agent->zwnGeneration();

            $withholding_agent_resp_person_data = [
                'responsible_person_id' => $this->responsible_person_id,
                'title' => $this->title,
                'position' => $this->position,
                'officer_id' => auth()->user()->id,
                'business_id' => $this->business->id ?? null
            ];

            $withholding_agent_resp_person = $withholding_agent->responsiblePersons()->create($withholding_agent_resp_person_data);

            if (!$withholding_agent_resp_person) throw new Exception('Failed to save withholding agent responsible person');

            DB::commit();

            event(new SendMail('withholding_agent_registration', $withholding_agent_resp_person->id));
            event(new SendSms('withholding_agent_registration', $withholding_agent_resp_person->id));

            return redirect()->to('/withholdingAgents/list')->with('success', "A notification for successful registration of a withholding agent for {$this->institution_name} has been sent to the responsible person.");
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function searchResponsibleDetails()
    {
        $this->validate(['ztnNumber' => 'nullable|strip_tag|alpha_num_space', 'reference_no' => 'required|strip_tag|alpha_num_space']);

        $taxpayer = Taxpayer::query()
            ->select('id', 'first_name', 'middle_name', 'last_name', 'tin', 'email', 'mobile', 'alt_mobile', 'zanid_no', 'nida_no', 'passport_no', 'country_id')
            ->where(['reference_no' => $this->reference_no])
            ->first();

        if ($this->ztnNumber) {
            $business = Business::query()
                ->select('id', 'name', 'mobile', 'email', 'tin', 'alt_mobile')
                ->where('ztn_number', $this->ztnNumber)
                ->first();

            if (!$business) {
                $this->customAlert('warning', "Business with ZTN No {$this->ztnNumber} not found");
                return;
            }

            $this->business = $business;
        }

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
