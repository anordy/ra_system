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


class WithholdingAgentViewModal extends Component
{
    use LivewireAlert;

    public $withholding_agent;
    public $region;
    public $wa_number;
    public $district;
    public $ward;
    public $tin;
    public $institution_name;
    public $institution_place;
    public $address;
    public $mobile;
    public $email;
    public $responsible_person_name;
    public $officer_id;
    public $title;
    public $position;
    public $date_of_commencing;

    public function mount($id)
    {
        $this->withholding_agent = WithholdingAgent::with(['district', 'region', 'ward', 'taxpayer'])->find($id);
        $this->tin = $this->withholding_agent->tin;
        $this->institution_name = $this->withholding_agent->institution_name;
        $this->institution_place = $this->withholding_agent->institution_place;
        $this->email = $this->withholding_agent->email;
        $this->wa_number = $this->withholding_agent->wa_number;
        $this->mobile = $this->withholding_agent->mobile;
        $this->address = $this->withholding_agent->address;
        $this->responsible_person_name = $this->withholding_agent->taxpayer->first_name . ' '. $this->withholding_agent->taxpayer->middle_name . ' ' . $this->withholding_agent->taxpayer->last_name;
        $this->region = $this->withholding_agent->region->name;
        $this->district = $this->withholding_agent->district->name;
        $this->ward = $this->withholding_agent->ward->name;
        $this->title = $this->withholding_agent->title;
        $this->position = $this->withholding_agent->position;
        $this->date_of_commencing = $this->withholding_agent->date_of_commencing;
    }


    public function render()
    {
        return view('livewire.withholding-agents.view-modal');
    }
}
