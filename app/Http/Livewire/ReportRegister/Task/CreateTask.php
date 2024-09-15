<?php

namespace App\Http\Livewire\ReportRegister\Task;

use App\Enum\CustomMessage;
use App\Enum\GeneralConstant;
use App\Enum\ReportRegister\RgAuditEvent;
use App\Enum\ReportRegister\RgPriority;
use App\Enum\ReportRegister\RgRegisterType;
use App\Enum\ReportRegister\RgRequestorType;
use App\Enum\ReportRegister\RgScheduleStatus;
use App\Enum\ReportRegister\RgTaskStatus;
use App\Jobs\SendCustomSMS;
use App\Models\ReportRegister\RgAttachment;
use App\Models\ReportRegister\RgAudit;
use App\Models\ReportRegister\RgRegister;
use App\Models\ReportRegister\RgSchedule;
use App\Models\User;
use App\Traits\CustomAlert;
use App\Traits\ReportRegisterTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateTask extends Component
{
    use CustomAlert, WithFileUploads, ReportRegisterTrait;

    public $title, $description, $files = [];
    public $priorities = [], $users = [], $scheduledTime, $startDate;
    public $incident, $status, $priority, $staffId, $isScheduled = GeneralConstant::ZERO;

    protected function rules()
    {
        return [
            'title' => 'required|max:100|alpha_gen',
            'description' => 'required|max:255|alpha_gen',
            'staffId' => 'required|integer|exists:users,id',
            'isScheduled' => ['required', Rule::in([GeneralConstant::ZERO, GeneralConstant::ONE])],
//            'scheduledTime' => ['required_if:isScheduled,' . GeneralConstant::ONE, 'date'],
            'files.*.file' => 'nullable|mimes:pdf,xlsx,xls|max:3072|max_file_name_length:100',
            'files.*.name' => 'nullable|alpha_gen|max:255',
            'priority' => ['required', Rule::in($this->priorities)],
            'startDate' => 'required|date'
        ];
    }

    public function mount()
    {
        $this->files = [['file' => null, 'name' => null]];
        $this->priorities = RgPriority::getConstants();
        $this->users = User::query()->select('id', 'fname', 'lname')->orderBy('fname', 'ASC')->get();
    }


    public function submit()
    {
        $this->validate();

        try {
            $attachments = [];

            foreach ($this->files ?? [] as $file) {
                if (isset($file['file']) && !$file['file']) {
                    $filePath = $file['file']->store('tasks', 'local');
                    if ($filePath) {
                        $attachments[] = [
                            'name' => $file['name'],
                            'path' => $filePath
                        ];
                    }
                }
            }

            DB::beginTransaction();

            $rgRegister = RgRegister::create([
                'requester_type' => RgRequestorType::STAFF,
                'requester_id' => Auth::id(),
                'title' => $this->title,
                'description' => $this->description,
                'priority' => $this->priority,
                'status' => RgTaskStatus::CREATED,
                'register_type' => RgRegisterType::TASK,
                'start_date' => $this->startDate,
                'is_scheduled' => $this->isScheduled
            ]);

            if (!$rgRegister) throw new Exception('Failed to save task');

            // Add attachments
            foreach ($attachments as $fileData) {
                $attachment = RgAttachment::create([
                    'rg_register_id' => $rgRegister->id,
                    'name' => $fileData['name'],
                    'path' => $fileData['path'],
                ]);

                if (!$attachment) throw new Exception('Failed to save attachment');
            }

            $this->assignStaffId($rgRegister, $this->staffId, RgTaskStatus::CREATED);

            // Audit incident
            $this->auditReportRegister($rgRegister, RgAuditEvent::CREATED, "Created task");

            $audit = RgAudit::create([
                'event' => RgAuditEvent::CREATED,
                'actor_type' => RgRequestorType::STAFF,
                'actor_id' => Auth::id(),
                'rg_register_id' => $rgRegister->id
            ]);

            if (!$audit) throw new Exception('Failed to save audit');

            DB::commit();

            $assignee = User::findOrFail($this->staffId, ['fname', 'phone', 'email']);
            $assigner = User::findOrFail(Auth::id(), ['fname', 'lname', 'phone', 'email']);

            if ($this->isScheduled === GeneralConstant::ZERO) {
                SendCustomSMS::dispatch($assignee->phone, "Hello {$assignee->fname}, {$assigner->fname} {$assigner->lname} has assign you a with task: {$this->title}");
            } else if ($this->isScheduled === GeneralConstant::ONE) {
                $a = SendCustomSMS::dispatch($assignee->phone, "Hello {$assignee->fname}, {$assigner->fname} {$assigner->lname} has assign you a with task: {$this->title}")->delay(Carbon::create($this->scheduledTime));

                Log::info('Scheduled');
                Log::info('sasa', [$a]);

                // Track saved schedules for cancellation
                $schedule = RgSchedule::create([
                    'rg_register_id' => $rgRegister->id,
                    'job_reference' => 'sa',
                    'status' => RgScheduleStatus::PENDING,
                    'time' => Carbon::create($this->scheduledTime)
                ]);

                if (!$schedule) throw new Exception('Failed to save schedule information');
            } else {
                $this->customAlert('warning', 'Invalid schedule option');
            }

            $this->flash('success', 'Task successfully created', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            foreach ($attachments ?? [] as $fileData) {
                $savedFilePath = $fileData['path'];
                if (Storage::disk('local')->exists($savedFilePath)) {
                    Storage::disk('local')->delete($savedFilePath);
                }
            }
            Log::error('REPORT-REGISTER-TASK-CREATE-TASK', [$e]);
            $this->customAlert('error', CustomMessage::error());
        }
    }


    public function render()
    {
        return view('livewire.report-register.task.create');
    }

    public function addFileInput()
    {
        array_push($this->files, ['file' => '', 'name' => '']); // Add empty placeholders
    }

    public function removeFileInput($index)
    {
        unset($this->files[$index]);
    }
}
