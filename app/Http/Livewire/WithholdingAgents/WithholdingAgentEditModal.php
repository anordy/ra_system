<?php

namespace App\Http\Livewire\WithholdingAgents;


use Exception;
use Carbon\Carbon;
use App\Models\Ward;
use App\Models\Region;
use App\Models\Street;
use Livewire\Component;
use App\Models\District;
use App\Models\Taxpayer;
use App\Models\WithholdingAgent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use App\Traits\CustomAlert;


class WithholdingAgentEditModal extends Component
{
    use CustomAlert;

    public $regions = [];
    public $districts = [];
    public $responsible_persons = [];
    public $wards = [];
    public $streets = [];
    public $withholding_agent;
    public $region_id;
    public $district_id, $street_id;
    public $ward_id;
    public $tin;
    public $institution_name;
    public $institution_place;
    public $address;
    public $mobile;
    public $email, $fax, $alt_mobile;
    public $responsible_person_id;
    public $officer_id;
    public $title;
    public $position;
    public $date_of_commencing;


    protected $rules = [
        'tin' => 'required|numeric|digits:9',
        'institution_name' => 'required|strip_tag',
        'institution_place' => 'required|strip_tag',
        'email' => 'required|email',
        'mobile' => 'required|digits_between:10,10',
        'address' => 'required|strip_tag',
        'region_id' => 'required|numeric',
        'district_id' => 'required|numeric',
        'ward_id' => 'required|numeric',
        'street_id' => 'required|numeric',
        'date_of_commencing' => 'required|strip_tag',
        'alt_mobile' => 'nullable|digits_between:10,10',
        'fax' => 'nullable|strip_tag'
    ];

    public function mount($id)
    {
        $this->regions = Region::select('id', 'name')->get();
        $this->districts = District::select('id', 'name')->get();
        $this->wards = Ward::select('id', 'name')->get();
        $this->responsible_persons = Taxpayer::select('id', 'first_name', 'middle_name', 'last_name')->get();
        $this->withholding_agent = WithholdingAgent::findOrFail(decrypt($id));
        $this->tin = $this->withholding_agent->tin;
        $this->institution_name = $this->withholding_agent->institution_name;
        $this->institution_place = $this->withholding_agent->institution_place;
        $this->email = $this->withholding_agent->email;
        $this->mobile = $this->withholding_agent->mobile;
        $this->address = $this->withholding_agent->address;
        $this->region_id = $this->withholding_agent->region_id;
        $this->district_id = $this->withholding_agent->district_id;
        $this->ward_id = $this->withholding_agent->ward_id;
        $this->street_id = $this->withholding_agent->street_id;
        $this->alt_mobile = $this->withholding_agent->alt_mobile;
        $this->fax = $this->withholding_agent->fax;
        $this->date_of_commencing = Carbon::create($this->withholding_agent->date_of_commencing)->format('Y-m-d');

        if ($this->street_id){
            $this->streets = Street::where('ward_id', $this->ward_id)->select('id', 'name')->get();
        }
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

        if ($propertyName === 'ward_id'){
            $this->streets = [];
            $this->streets = Street::where('ward_id', $this->ward_id)->select('id', 'name')->get();
        }
    }

    public function submit()
    {
        if (!Gate::allows('withholding-agents-registration')) {
            abort(403);
        }
        $this->validate();

        try {

            $this->withholding_agent->update([
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
                'street_id' => $this->street_id,
                'fax' => $this->fax,
                'alt_mobile' => $this->alt_mobile,
            ]);
            
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());

        } catch (Exception $e) {
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }


    public function render()
    {
        return view('livewire.withholding-agents.edit-modal');
    }
}
