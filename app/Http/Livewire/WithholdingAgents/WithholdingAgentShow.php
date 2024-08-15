<?php

namespace App\Http\Livewire\WithholdingAgents;

use App\Models\WithholdingAgent;
use App\Traits\CustomAlert;
use Livewire\Component;


class WithholdingAgentShow extends Component
{
    use CustomAlert;

    public $withholding_agent;

    public function mount($id)
    {
        $this->withholding_agent = WithholdingAgent::with(['district', 'region', 'ward'])->findOrFail(decrypt($id), ['id', 'tin', 'address', 'wa_number', 'institution_name', 'institution_place', 'email', 'mobile', 'date_of_commencing', 'status', 'ward_id', 'region_id', 'district_id', 'created_at', 'updated_at', 'deleted_at', 'business_id', 'fax', 'alt_mobile', 'street_id', 'approval_letter', 'app_status', 'marking', 'approved_on']);
    }


    public function render()
    {
        return view('livewire.withholding-agents.show');
    }
}
