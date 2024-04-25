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
        $this->withholding_agent = WithholdingAgent::with(['district', 'region', 'ward'])->findOrFail(decrypt($id));
    }


    public function render()
    {
        return view('livewire.withholding-agents.show');
    }
}
