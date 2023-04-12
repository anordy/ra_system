<?php

namespace App\Http\Livewire\Workflow;

use App\Models\Workflow;
use Exception;
use Livewire\Component;
use App\Traits\CustomAlert;

class WorkflowConfig extends Component
{

    use CustomAlert;

    public $places;
    public $transitions;
    public $workflow;

    public function mount($id)
    {
        $workflow = Workflow::find(decrypt($id));
        if (is_null($workflow)){
            abort(404);
        }
        $this->workflow = $workflow;
        $this->transitions = json_decode($workflow->transitions, true);
        $this->places = json_decode($workflow->places, true);
    }


    public function render()
    {
        return view('livewire.workflow.config');
    }
}
