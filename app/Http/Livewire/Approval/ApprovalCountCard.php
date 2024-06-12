<?php

namespace App\Http\Livewire\Approval;

use App\Enum\BillStatus;
use App\Models\Disputes\Dispute;
use App\Models\Investigation\TaxInvestigation;
use App\Models\TaxAudit\TaxAudit;
use App\Models\Verification\TaxVerification;
use App\Models\WorkflowTask;
use App\Traits\CustomAlert;
use Livewire\Component;

class ApprovalCountCard extends Component
{
    use CustomAlert;

    public $pending = 0;
    public $rejected = 0;
    public $approved = 0;
    public $category;

    public function mount($modelName, $category = null)
    {
        $this->category = $category;
        $user_id = auth()->id();
        switch ($modelName) {
            case 'TaxVerification':
                $model = TaxVerification::class;
                $workflowPending = WorkflowTask::where('pinstance_type', $model)
                    ->where('owner', WorkflowTask::STAFF)
                    ->where('status', 'running')
                    ->whereHas('actors', function ($query) use ($user_id) {
                        $query->where('user_id', $user_id);
                    })
                    ->get();

                $workflow = WorkflowTask::where('pinstance_type', $model)
                    ->where('user_type', get_class(auth()->user()))
                    ->where('user_id', $user_id)
                    ->get();

                $this->pending = $workflowPending->count();
                $this->rejected = $workflow->where('status', 'rejected')->count();
                $this->approved = $workflow->where('status', 'completed')->count();
                break;

            case 'TaxAudit':
                $model = TaxAudit::class;
                $workflowPending = WorkflowTask::where('pinstance_type', $model)
                    ->where('owner', WorkflowTask::STAFF)
                    ->where('status', 'running')
                    ->whereHas('actors', function ($query) use ($user_id) {
                        $query->where('user_id', $user_id);
                    })
                    ->get();

                $workflow = WorkflowTask::where('pinstance_type', $model)
                    ->where('user_type', get_class(auth()->user()))
                    ->where('user_id', $user_id)
                    ->get();

                $this->pending = $workflowPending->count();
                $this->rejected = $workflow->where('status', 'rejected')->count();
                $this->approved = $workflow->where('status', 'completed')->count();
                break;
            case 'TaxInvestigation':
                $model = TaxInvestigation::class;
                $workflowPending = WorkflowTask::where('pinstance_type', $model)
                    ->where('owner', WorkflowTask::STAFF)
                    ->where('status', 'running')
                    ->whereHas('actors', function ($query) use ($user_id) {
                        $query->where('user_id', $user_id);
                    })
                    ->get();

                $workflow = WorkflowTask::where('pinstance_type', $model)
                    ->where('user_type', get_class(auth()->user()))
                    ->where('user_id', $user_id)
                    ->get();

                $this->pending = $workflowPending->count();
                $this->rejected = $workflow->where('status', 'rejected')->count();
                $this->approved = $workflow->where('status', 'completed')->count();
                break;
            case 'Dispute':
                $model = Dispute::class;
                $workflowPending = WorkflowTask::where('pinstance_type', $model)
                    ->where('owner', WorkflowTask::STAFF)
                    ->where('status', 'running')
                    ->whereHasMorph('pinstance', Dispute::class, function ($query) {
                        $query->where('category', $this->category);
                    })
                    ->whereHas('actors', function ($query) use ($user_id) {
                        $query->where('user_id', $user_id);
                    })
                    ->get();
                $workflow = WorkflowTask::where('pinstance_type', $model)
                    ->where('user_type', get_class(auth()->user()))
                    ->where('user_id', $user_id)
                    ->whereHasMorph('pinstance', Dispute::class, function ($query) {
                        $query->where('category', $this->category);
                    })
                    ->get();

                $this->pending = $workflowPending->count();
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
