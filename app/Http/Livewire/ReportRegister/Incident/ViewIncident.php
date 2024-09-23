<?php

namespace App\Http\Livewire\ReportRegister\Incident;

use App\Enum\CustomMessage;
use App\Enum\ReportRegister\RgPriority;
use App\Enum\ReportRegister\RgStatus;
use App\Models\User;
use App\Traits\CustomAlert;
use App\Traits\ReportRegisterTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ViewIncident extends Component
{
    use CustomAlert, ReportRegisterTrait;

    public $statuses = [], $priorities = [], $users = [], $incidentId;
    public $incident, $status, $priority, $staffId, $comment, $roles = [];
    public $query, $selectedUser, $highlightIndex;


    public function mount($incidentId)
    {
        $this->incidentId = decrypt($incidentId);
        $this->incident = $this->getRegister($this->incidentId);
        $this->statuses = RgStatus::getConstants();
        $this->priorities = RgPriority::getConstants();
        $this->status = $this->incident->status;
        $this->priority = $this->incident->priority;
        $this->staffId = $this->incident->assigned_to_id ?? null;
        $currentUser = User::find($this->staffId, ['fname', 'lname']);
        if ($currentUser) {
            $this->query = $currentUser->fname . ' ' . $currentUser->lname;
        }
        if (isset($this->incident->subcategory->notifiables)) {
            $this->roles = $this->incident->subcategory->notifiables->pluck('role_id')->toArray();
        }
    }

    public function updatedStatus()
    {
        $this->validate([
            'status' => Rule::in($this->statuses),
            'comment' => 'required|min:10|max:255|string'
        ],[
            'comment.required' => "Please enter comment for marking this incident as {$this->status}"
        ]);

        try {
            $this->updateStatus($this->incident, $this->status);
            $this->customAlert('success', 'Report status has been updated');
            $this->incident = $this->getRegister($this->incidentId);
        } catch (Exception $exception) {
            Log::error('REPORT-REGISTER-INCIDENT-VIEW-UPDATE-STATUS', [$exception]);
            $this->customAlert('error', CustomMessage::error());
        }
    }

    public function updatedPriority()
    {
        $this->validate([
            'priority' => Rule::in($this->priorities)
        ]);
        try {
            $this->updatePriority($this->incident, $this->priority);
            $this->customAlert('success', 'Report priority has been updated');
            $this->incident = $this->getRegister($this->incidentId);
        } catch (Exception $exception) {
            Log::error('REPORT-REGISTER-INCIDENT-VIEW-UPDATE-PRIORITY', [$exception]);
            $this->customAlert('error', CustomMessage::error());
        }
    }

    public function updatedStaffId()
    {
        try {
            $this->assignStaffId($this->incident, $this->staffId);
            $this->customAlert('success', 'Staff has been assigned');
            $this->incident = $this->getRegister($this->incidentId);
        } catch (Exception $exception) {
            Log::error('REPORT-REGISTER-INCIDENT-VIEW-UPDATE-PRIORITY', [$exception]);
            $this->customAlert('error', CustomMessage::error());
        }
    }

    public function saveComment()
    {
        $this->validate([
            'comment' => 'required|min:10|max:255|string'
        ]);
        try {
            if ($this->incident->status != $this->status) {
                $this->updateStatus($this->incident, $this->status);
            }
            $this->addComment($this->incident, $this->comment, $this->status);
            $this->customAlert('success', 'Comment has been added');
            $this->incident = $this->getRegister($this->incidentId);
            $this->comment = null;
        } catch (Exception $exception) {
            Log::error('REPORT-REGISTER-INCIDENT-VIEW-UPDATE-PRIORITY', [$exception]);
            $this->customAlert('error', CustomMessage::error());
        }
    }

    public function resetFields()
    {
        $this->query = '';
        $this->users = [];
        $this->highlightIndex = 0;
    }

    public function incrementHighlight()
    {
        if ($this->highlightIndex === count($this->users) - 1) {
            $this->highlightIndex = 0;
            return;
        }
        $this->highlightIndex++;
    }

    public function decrementHighlight()
    {
        if ($this->highlightIndex === 0) {
            $this->highlightIndex = count($this->users) - 1;
            return;
        }
        $this->highlightIndex--;
    }

    public function selectUser($index)
    {
        $this->selectedUser = $this->users[$index] ?? null;
        if ($this->selectedUser) {
            $this->staffId = $this->selectedUser['id'];
            $this->query = ucfirst($this->selectedUser['fname'].' '.$this->selectedUser['lname']);
            $this->updatedStaffId();
        }
        $this->users = [];
        $this->highlightIndex = 0;
    }

    public function updatedQuery()
    {
        $this->users = User::query()
            ->select('id', 'fname', 'lname')
            ->whereIn('role_id', $this->roles)
            ->whereRaw(DB::raw("LOWER(fname) like '%' || LOWER('$this->query') || '%'"))
            ->orWhereRaw(DB::raw("LOWER(lname) like '%' || LOWER('$this->query') || '%'"))
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.report-register.incident.view');
    }
}
