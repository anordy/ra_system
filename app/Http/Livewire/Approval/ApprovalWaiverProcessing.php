<?php

namespace App\Http\Livewire\Approval;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\Waiver;
use App\Models\WaiverReport;
use App\Models\WaiverStatus;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class ApprovalWaiverProcessing extends Component
{
    use WorkflowProcesssingTrait, WithFileUploads, LivewireAlert;
    public $modelId;
    public $modelName;
    public $comments;
    public $weaverReport;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = $modelId;
        $this->registerWorkflow($modelName, $modelId);

    }

    public function approve($transtion)
    {
        $this->validate([
            'comments' => 'required',
        ]);

        if ($this->checkTransition('waiver_manager_review')) {

            $this->validate(
                [
                    'weaverReport' => 'required|mimes:pdf',
                ]
            );

            $weaverReport = "";
            if ($this->weaverReport) {
                $weaverReport = $this->weaverReport->store('waiver_report', 'local-admin');
            }

            $waiver  = Waiver::find($this->modelId);

            DB::beginTransaction();
            try {

                $waiver->update([
                    'attachments' => $weaverReport ?? '',
                ]);
                DB::commit();
            } catch (\Exception $e) {
                throw $e;
                Log::error($e);
                DB::rollBack();
            }

        }

        if ($this->checkTransition('chief_assurance_reject')) {
            // dd('chief assuarance review');
        }

        if ($this->checkTransition('commisioner_review')) {
            // dd('chief assuarance review');
            $this->subject->verified_at = Carbon::now()->toDateTimeString();
            $this->subject->status = WaiverStatus::APPROVED;
            $this->subject->save();
            // event(new SendSms('business-registration-approved', $this->subject->id));
            // event(new SendMail('business-registration-approved', $this->subject->id));
        }

        try {
            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments]);
        } catch (Exception $e) {
            Log::error($e);

            return;
        }
        $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());

    }

    public function reject($transtion)
    {
        $this->validate([
            'comments' => 'required|string',
        ]);

        try {
            if ($this->checkTransition('application_filled_incorrect')) {
                $this->subject->status = WaiverStatus::CORRECTION;
                // event(new SendSms('business-registration-correction', $this->subject->id));
                // event(new SendMail('business-registration-correction', $this->subject->id));
            }
            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments]);
        } catch (Exception $e) {
            Log::error($e);

            return;
        }
        $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
    }

    public function render()
    {
        return view('livewire.approval.approval-waiver-processing');
    }
}
