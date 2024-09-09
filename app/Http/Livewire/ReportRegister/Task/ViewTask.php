<?php

namespace App\Http\Livewire\ReportRegister\Task;

use App\Enum\CustomMessage;
use App\Enum\ReportRegister\RgPriority;
use App\Enum\ReportRegister\RgStatus;
use App\Enum\ReportRegister\RgTaskStatus;
use App\Models\ReportRegister\RgSchedule;
use App\Models\User;
use App\Traits\CustomAlert;
use App\Traits\ReportRegisterTrait;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ViewTask extends Component
{
    use CustomAlert, ReportRegisterTrait;

    public $statuses = [], $priorities = [], $users = [], $taskId;
    public $task, $status, $priority, $staffId, $comment, $schedule;

    public function mount($taskId)
    {
        $this->taskId = decrypt($taskId);
        $this->task = $this->getRegister($this->taskId);
        $this->schedule = RgSchedule::select('time', 'status')->where('rg_register_id', $this->taskId)->latest()->first();
        $this->statuses = RgTaskStatus::getConstants();
        $this->priorities = RgPriority::getConstants();
        $this->status = $this->task->status;
        $this->priority = $this->task->priority;
        $this->staffId = $this->task->assigned_to_id ?? null;
        $this->users = User::query()->select('id', 'fname', 'lname')->orderBy('fname', 'ASC')->get();
    }

    public function updatedStatus()
    {
        $this->validate([
            'status' => Rule::in($this->statuses)
        ]);
        try {
            $this->updateStatus($this->task, $this->status);
            $this->customAlert('success', 'Report status has been updated');
            $this->task = $this->getRegister($this->taskId);
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
            $this->updatePriority($this->task, $this->priority);
            $this->customAlert('success', 'Report priority has been updated');
            $this->task = $this->getRegister($this->taskId);
        } catch (Exception $exception) {
            Log::error('REPORT-REGISTER-INCIDENT-VIEW-UPDATE-PRIORITY', [$exception]);
            $this->customAlert('error', CustomMessage::error());
        }
    }

    public function updatedStaffId()
    {
        try {
            $this->assignStaffId($this->task, $this->staffId);
            $this->customAlert('success', 'Staff has been assigned');
            $this->task = $this->getRegister($this->taskId);
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
            $this->addComment($this->task, $this->comment);
            $this->customAlert('success', 'Comment has been added');
            $this->task = $this->getRegister($this->taskId);
            $this->comment = null;

            // TODO: Dispatch Job To Notify Requester

        } catch (Exception $exception) {
            Log::error('REPORT-REGISTER-INCIDENT-VIEW-UPDATE-PRIORITY', [$exception]);
            $this->customAlert('error', CustomMessage::error());
        }
    }



    public function render()
    {
        return view('livewire.report-register.task.view');
    }
}
