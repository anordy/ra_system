<?php

namespace App\Http\Livewire\WithholdingAgents;

use Livewire\Component;
use App\Models\WithholdingAgent;
use Jantinnerezo\LivewireAlert\LivewireAlert;


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
