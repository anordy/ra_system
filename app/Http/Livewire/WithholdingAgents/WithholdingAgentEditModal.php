<?php

namespace App\Http\Livewire\WithholdingAgents;


use Exception;
use App\Models\User;
use App\Models\Ward;
use App\Models\Region;
use Livewire\Component;
use App\Models\District;
use App\Models\Taxpayer;
use App\Models\WithholdingAgent;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;


class WithholdingAgentEditModal extends Component
{
    use LivewireAlert;

    public $regions = [];
    public $districts = [];
    public $responsible_persons = [];
    public $wards = [];
    public $withholding_agent;
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
        'email' => 'required|email',
        'mobile' => 'required|size:10',
        'address' => 'required',
        'responsible_person_id' => 'required',
        'region_id' => 'required',
        'district_id' => 'required',
        'ward_id' => 'required',
        'title' => 'required',
        'position' => 'required',
        'date_of_commencing' => 'required'
    ];

    public function mount($id)
    {
        $this->regions = Region::select('id', 'name')->get();
        $this->districts = District::select('id', 'name')->get();
        $this->wards = Ward::select('id', 'name')->get();
        $this->responsible_persons = Taxpayer::select('id', 'first_name', 'middle_name', 'last_name')->get();
        $this->withholding_agent = WithholdingAgent::find($id);
        $this->tin = $this->withholding_agent->tin;
        $this->institution_name = $this->withholding_agent->institution_name;
        $this->institution_place = $this->withholding_agent->institution_place;
        $this->email = $this->withholding_agent->email;
        $this->mobile = $this->withholding_agent->mobile;
        $this->address = $this->withholding_agent->address;
        $this->responsible_person_id = $this->withholding_agent->responsible_person_id;
        $this->region_id = $this->withholding_agent->region_id;
        $this->district_id = $this->withholding_agent->district_id;
        $this->ward_id = $this->withholding_agent->ward_id;
        $this->title = $this->withholding_agent->title;
        $this->position = $this->withholding_agent->position;
        $this->date_of_commencing = $this->withholding_agent->date_of_commencing;
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

        try {

            $this->withholding_agent->update([
                'tin' => $this->tin,
                'institution_name' => $this->institution_name,
                'institution_place' => $this->institution_place,
                'email' => $this->email,
                'mobile' => $this->mobile,
                'address' => $this->address,
                'responsible_person_id' => $this->responsible_person_id,
                'officer_id' => auth()->user()->id,
                'title' => $this->title,
                'position' => $this->position,
                'date_of_commencing' => $this->date_of_commencing,
                'region_id' => $this->region_id,
                'district_id' => $this->district_id,
                'ward_id' => $this->ward_id,
            ]);
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());

        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }


    public function render()
    {
        return view('livewire.withholding-agents.edit-modal');
    }
}
