<?php

namespace App\Http\Livewire\Workflow;

use App\Models\Workflow;
use Exception;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class WorkflowConfig extends Component
{

    use LivewireAlert;

    public $places;
    public $transitions;
    public $workflow;

    public function mount($id)
    {
        $workflow = Workflow::find(decrypt($id));
        $this->workflow = $workflow;
        $this->transitions = json_decode($workflow->transitions, true);
        $this->places = json_decode($workflow->places, true);
    }


    public function render()
    {
        return view('livewire.workflow.config');
    }
}
