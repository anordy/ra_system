<?php

namespace App\Http\Livewire\WithholdingAgents;

use App\Models\WithholdingAgent;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;


class WithholdingAgentView extends Component
{
    use LivewireAlert;

    public $withholding_agent;

    public function mount($id)
    {
        $this->withholding_agent = WithholdingAgent::with(['district', 'region', 'ward'])->findOrFail(decrypt($id));
    }


    public function render()
    {
        return view('livewire.withholding-agents.view');
    }
}
