<?php

namespace App\Http\Livewire\ReportRegister\Task;

use App\Enum\CustomMessage;
use App\Enum\ReportRegister\RgAuditEvent;
use App\Enum\ReportRegister\RgPriority;
use App\Enum\ReportRegister\RgScheduleStatus;
use App\Enum\ReportRegister\RgTaskStatus;
use App\Models\ReportRegister\RgSchedule;
use App\Models\User;
use App\Traits\CustomAlert;
use App\Traits\ReportRegisterTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $this->schedule = RgSchedule::select('id', 'time', 'status', 'job_reference', 'cancelled_by_id')->where('rg_register_id', $this->taskId)->latest()->first();
        $this->statuses = RgTaskStatus::getConstants();
        $this->priorities = RgPriority::getConstants();
        $this->status = $this->task->status;
        $this->priority = $this->task->priority;
        $this->staffId = $this->task->assigned_to_id ?? null;
        $this->users = User::query()
            ->select('id', 'fname', 'lname')
            ->orderBy('fname', 'ASC')
            ->get();
    }

    public function updatedStatus()
    {
        $this->validate([
            'status' => Rule::in($this->statuses),
            'comment' => 'required|min:10|max:255|string'
        ],[
            'comment.required' => "Please enter comment for marking this task as {$this->status}"
        ]);
        try {
            $this->updateStatus($this->task, $this->status);
            $this->customAlert('success', 'Report status has been updated');
            $this->task = $this->getRegister($this->taskId);
        } catch (Exception $exception) {
            Log::error('REPORT-REGISTER-TASK-UPDATE-STATUS', [$exception]);
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
            Log::error('REPORT-REGISTER-TASK-VIEW-UPDATE-STAFF-ID', [$exception]);
            $this->customAlert('error', CustomMessage::error());
        }
    }

    public function saveComment()
    {
        $this->validate([
            'comment' => 'required|min:10|max:255|string'
        ]);

        try {
            if ($this->task->status != $this->status) {
                $this->updateStatus($this->task, $this->status);
            }
            $this->addComment($this->task, $this->comment, $this->status);
            $this->customAlert('success', 'Comment has been added');
            $this->task = $this->getRegister($this->taskId);
            $this->comment = null;
        } catch (Exception $exception) {
            Log::error('REPORT-REGISTER-TASK-VIEW-SAVE-COMMENT', [$exception]);
            $this->customAlert('error', CustomMessage::error());
        }
    }

    public function cancelTask()
    {
        try {
            DB::beginTransaction();
            $this->task->status = RgTaskStatus::CANCELLED;
            if (!$this->task->save()) throw new Exception('Failed to cancel task');

            $this->schedule->cancelled_by_id = Auth::id();
            $this->schedule->status = RgScheduleStatus::CANCELLED;
            if (!$this->schedule->save()) throw new Exception('Failed to update schedule');

            $job = DB::table('jobs')->delete($this->schedule->job_reference);
            if (!$job) throw new Exception('Failed to delete job');

            $this->auditReportRegister($this->task, RgAuditEvent::UPDATED, "Set status to cancelled");

            DB::commit();
            $this->customAlert('success', 'Task has been cancelled successfully');
            $this->task = $this->getRegister($this->taskId);
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error('REPORT-REGISTER-TASK-CANCEL-SCHEDULE', [$exception]);
            $this->customAlert('error', CustomMessage::error());
        }
    }

    protected $listeners = [
        'cancelTask'
    ];

    public function confirmPopUpModal()
    {
        $this->customAlert('warning', 'Are you sure you want to complete this action?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Confirm',
            'onConfirmed' => 'cancelTask',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
        ]);
    }

    public function render()
    {
        return view('livewire.report-register.task.view');
    }
}
