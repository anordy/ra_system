<?php

namespace App\Traits;

use App\Enum\ReportRegister\RgAssignmentStatus;
use App\Enum\ReportRegister\RgAuditEvent;
use App\Enum\ReportRegister\RgRequestorType;
use App\Enum\ReportRegister\RgStatus;
use App\Enum\ReportRegister\RgTaskStatus;
use App\Jobs\General\SendCustomSMS;
use App\Models\ReportRegister\RgAssignment;
use App\Models\ReportRegister\RgAudit;
use App\Models\ReportRegister\RgComment;
use App\Models\ReportRegister\RgRegister;
use App\Models\Taxpayer;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait ReportRegisterTrait
{
    use CustomAlert;

    public function assignStaffId($register, $staffId, $status = RgStatus::IN_PROGRESS)
    {
        try {
            DB::beginTransaction();

            $updated = $register->update(['status' => $status, 'assigned_to_id' => $staffId]);

            if (!$updated) throw new Exception('Failed to update incident status');

            if (count($register->assignees) > 0) {
                $lastAssignee = $register->assignee;

                if ($lastAssignee) {
                    $lastAssignee->end_date = now();
                    $lastAssignee->status = RgAssignmentStatus::RE_ASSIGNED;

                    if (!$lastAssignee->save()) throw new Exception('Failed to update assignment');

                }
            }

            $assigned = RgAssignment::create([
                'assignee_id' => $staffId,
                'assigner_id' => Auth::id(),
                'rg_register_id' => $register->id,
                'start_date' => now(),
                'assigned_date' => now(),
                'status' => RgAssignmentStatus::ASSIGNED
            ]);

            if (!$assigned) throw new Exception('Failed to assign incident');

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function updateStatus($register, $status)
    {
        try {
            DB::beginTransaction();

            if ($status === RgStatus::RESOLVED || $status === RgTaskStatus::CLOSED) {
                $updated = $register->update(['resolved_date' => now()]);
                if (!$updated) throw new Exception('Failed to update incident status');
            }

            $updated = $register->update(['status' => $status]);

            if (!$updated) throw new Exception('Failed to update incident status');

            $this->auditReportRegister($register, RgAuditEvent::UPDATED, "Set Status to {$status}");

            DB::commit();

            // TODO: Send Notification to assigner on status
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function updatePriority($register, $priority)
    {
        try {
            DB::beginTransaction();

            $updated = $register->update(['priority' => $priority]);

            if (!$updated) throw new Exception('Failed to update incident priority');

            $this->auditReportRegister($register, RgAuditEvent::UPDATED, "Set Priority to {$priority}");

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function addComment($register, $comment)
    {
        try {
            if (get_class(Auth::user()) === User::class) {
                $actorType = RgRequestorType::STAFF;
            } else if (get_class(Auth::user()) === Taxpayer::class) {
                $actorType = RgRequestorType::TAXPAYER;
            } else {
                throw new \Exception('Invalid actor Type');
            }

            DB::beginTransaction();

            $comment = RgComment::create([
                'commenter_type' => $actorType,
                'commenter_id' => Auth::id(),
                'comment' => $comment,
                'rg_register_id' => $register->id,
                'is_read' => false
            ]);

            if (!$comment) throw new Exception('Failed to save comment');

            $this->auditReportRegister($register, RgAuditEvent::UPDATED, "Added Comment {$comment->comment}");

            DB::commit();

            // TODO: Send Notification to requester

        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function getRegister($incidentId)
    {
        return RgRegister::query()
            ->with(['assigned', 'comments', 'audits', 'assignees', 'attachments:rg_register_id,name,path', 'category', 'subcategory'])
            ->findOrFail($incidentId, ['id', 'requester_type', 'requester_id', 'register_type', 'title', 'description', 'start_date', 'resolved_date', 'breach_date', 'status', 'priority', 'is_breached', 'created_at', 'rg_category_id', 'rg_sub_category_id', 'code', 'is_scheduled', 'assigned_to_id']);
    }

    /**
     * Record report register audit event
     * @param $register
     * @param $event
     * @param $description
     * @return void
     * @throws \Exception
     */
    public function auditReportRegister($register, $event, $description): void
    {
        try {
            if (get_class(Auth::user()) === User::class) {
                $actorType = RgRequestorType::STAFF;
            } else if (get_class(Auth::user()) === Taxpayer::class) {
                $actorType = RgRequestorType::TAXPAYER;
            } else {
                throw new \Exception('Invalid actor Type');
            }

            $audit = RgAudit::create([
                'event' => $event,
                'actor_type' => $actorType,
                'actor_id' => Auth::id(),
                'rg_register_id' => $register->id,
                'description' => $description,
            ]);

            if (!$audit) throw new \Exception('Failed to save report register audit');

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}
