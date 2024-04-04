<?php

namespace App\Http\Livewire\DriversLicense;

use App\Models\DlApplicationStatus;
use App\Models\DlFee;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use App\Traits\WorkflowProcesssingTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, CustomAlert, PaymentsTrait;

    public $modelId;
    public $modelName;
    public $comments;
    public $duration_id;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->registerWorkflow($modelName, $this->modelId);
    }


    public function approve($transition)
    {
        $this->validate(['comments' => 'required|strip_tag', 'duration_id' => 'required|numeric'], ['duration_id.required' => 'You must select License Duration to approve']);

        try {
            $fee = DlFee::where(['type' => $this->subject->type, 'dl_license_duration_id' => $this->subject->dl_license_duration_id])
                ->first();

            if (empty($fee)) {
                $this->customAlert('error', "Fee for Drivers license application ({$this->subject->type}) is not configured");
                return;
            }

            DB::beginTransaction();

            $this->subject->dl_application_status_id = DlApplicationStatus::query()->firstOrCreate(['name' => DlApplicationStatus::STATUS_PENDING_PAYMENT])->id;
            $this->subject->dl_license_duration_id = $this->duration_id;
            $this->subject->save();

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

            DB::commit();
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('DRIVERS-LICENSE-APPROVAL-PROCESSING-APPROVE', [$e]);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            return;
        }

        try {
            $this->generateDLicenseControlNumber($this->subject, $fee);
        } catch (Exception $exception) {
            Log::error('DRIVERS-LICENSE-APPROVAL-PROCESSING-APPROVE', [$exception]);
            $this->flash('error', 'Failed to generate bill', [], redirect()->back()->getTargetUrl());
        }
    }

    public function reject($transition)
    {
        $this->validate(['comments' => 'required|strip_tag']);
        try {
            $this->subject->dl_application_status_id = DlApplicationStatus::query()->firstOrCreate(['name' => DlApplicationStatus::STATUS_DETAILS_CORRECTION])->id;
            $this->subject->save();
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error('DRIVERS-LICENSE-APPROVAL-PROCESSING-REJECT', [$e]);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }


    public function render()
    {
        return view('livewire.drivers-license.approval-processing');
    }
}
