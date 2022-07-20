<?php

namespace App\Http\Livewire\WithholdingAgents;

use Livewire\Component;
use App\Models\WithholdingAgent;
use Illuminate\Support\Facades\Route;
use Jantinnerezo\LivewireAlert\LivewireAlert;


class WithholdingAgentView extends Component
{
    use LivewireAlert;

    public $withholding_agent;

    public function mount()
    {
        $withholding_agent_id = decrypt(Route::current()->parameter('id'));
        $this->withholding_agent = WithholdingAgent::with(['district', 'region', 'ward'])->find($withholding_agent_id);
    }


    public function render()
    {
        return view('livewire.withholding-agents.view');
    }
}
