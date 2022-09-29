<?php

namespace App\Http\Livewire\Approval;

use App\Models\Verification\TaxVerification;
use App\Models\WorkflowTask;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ApprovalCountCard extends Component
{
    use LivewireAlert;

    public $pending = 0;
    public $rejected = 0;
    public $approved = 0;

    public function mount($modelName)
    {
        $user_id = auth()->id();
        switch ($modelName) {
            case 'TaxVerification':
                $model = TaxVerification::class;
                $workflow = WorkflowTask::where('pinstance_type', $model)
                    ->where('user_type', get_class(auth()->user()))
                    ->where('user_id', $user_id)
                    ->get();
        
                $this->pending = $workflow->where('status', 'running')->count();
                $this->rejected = $workflow->where('status', 'rejected')->count();
                $this->approved = $workflow->where('status', 'completed')->count();
                break;
        }
    }

    public function render()
    {
        return view('livewire.approval.approval-count-card');
    }
}
