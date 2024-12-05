<?php

namespace App\Http\Livewire\ReportRegister\Incident;

use App\Enum\CustomMessage;
use App\Enum\ReportRegister\RgAuditEvent;
use App\Enum\ReportRegister\RgPriority;
use App\Enum\ReportRegister\RgStatus;
use App\Models\ReportRegister\RgCategory;
use App\Models\ReportRegister\RgSubCategory;
use App\Models\ReportRegister\RgSubCategoryNotifiable;
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

    public $statuses = [], $priorities = [], $users = [], $incidentId, $transferCategories = [], $transferCategoryId;
    public $incident, $status, $priority, $staffId, $comment, $roles = [], $isTransferredSelected = false;


    public function mount($incidentId)
    {
        $this->incidentId = decrypt($incidentId);
        $this->incident = $this->getRegister($this->incidentId);
        $this->statuses = RgStatus::getConstants();
        $this->priorities = RgPriority::getConstants();
        $this->status = $this->incident->status;
        $this->priority = $this->incident->priority;
        $this->staffId = $this->incident->assigned_to_id ?? null;
        $this->transferCategoryId = $this->incident->transferred_id;
        if ($this->transferCategoryId) {
            $transferId = $this->transferCategoryId;
        } else {
            $transferId = $this->incident->rg_category_id;
        }
        $this->transferCategories = RgCategory::query()
            ->select('id', 'name')
            ->where('id', '!=', $transferId)
            ->get();
        if (isset($this->incident->subcategory->notifiables)) {
            $additionalRoles = [];
            if ($this->transferCategoryId) {
                $additionalSubCategories = RgSubCategory::query()->select('id')->where('rg_category_id', $this->transferCategoryId)->pluck('id')->toArray();
                $additionalRoles = RgSubCategoryNotifiable::query()->select('role_id')->whereIn('rg_sub_category_id', $additionalSubCategories)->pluck('role_id')->toArray();

            }
            $this->roles = $this->incident->subcategory->notifiables->pluck('role_id')->toArray();
            $this->roles = [...$additionalRoles, ...$this->roles];
            $this->users = User::query()
                ->select('id', 'fname', 'lname')
                ->whereIn('role_id', $this->roles)
                ->get()
                ->map(function ($item) {
                    $item->fullname = ucwords($item->full_name);
                    return $item;
                });
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

    public function updatedtransferCategoryId()
    {
        $this->isTransferredSelected = true;
        $this->validate([
            'comment' => 'required|min:10|max:255|string'
        ],[
            'comment.required' => "Please enter reason for transferring this incident"
        ]);

    }

    public function saveTransferredState() {
        try {
            $transferredName = RgCategory::find($this->transferCategoryId, ['name']);
            DB::beginTransaction();
            $this->incident->transferred_id = $this->transferCategoryId;
            if (!$this->incident->save()) throw new Exception('Failed to update incident');
            $this->addComment($this->incident, $this->comment, $this->status);
            $this->auditReportRegister($this->incident, RgAuditEvent::UPDATED, "Incident transferred to {$transferredName->name}");
            DB::commit();
            $this->customAlert('success', 'Incident has been transferred successfully');
            return redirect(request()->header('Referer'));
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error('REPORT-REGISTER-INCIDENT-VIEW-SAVE-TRANSFERRED-STATE', [$exception]);
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


    public function render()
    {
        return view('livewire.report-register.incident.view');
    }
}
