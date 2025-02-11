<?php

namespace App\Http\Livewire\Approval\Mvr;

use App\Enum\AlertType;
use App\Enum\BillStatus;
use App\Enum\CustomMessage;
use App\Enum\GeneralConstant;
use App\Events\SendSms;
use App\Jobs\SendCustomSMS;
use App\Models\DlApplicationStatus;
use App\Models\DlFee;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use App\Traits\TaxpayerLedgerTrait;
use App\Traits\WorkflowProcesssingTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class DriverLicenseApprovalProcessing extends Component
{
    use CustomAlert, WorkflowProcesssingTrait, PaymentsTrait, WithFileUploads, TaxpayerLedgerTrait;

    public $modelId;
    public $modelName;
    public $comments;
    public $approvalReport, $lostReport;
    public $selectedRestrictions, $restrictions;
    public $attachments = [];

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->registerWorkflow($modelName, $this->modelId);
        $this->lostReport = $this->subject->lost_report_path;
    }


    public function approve($transition)
    {
        $transition = $transition['data']['transition'];

        $this->validate([
            'comments' => 'required|strip_tag',
        ]);

        if ($this->lostReport instanceof TemporaryUploadedFile) {
            $this->validate([
                'lostReport' => 'required|mimes:pdf|valid_pdf|max:3072|max_file_name_length:100',
            ]);
        }

        try {
            if ($this->checkTransition('zra_officer_review') && $transition === 'zra_officer_review') {
                $this->subject->status = DlApplicationStatus::STATUS_PENDING_PAYMENT;
                $this->subject->payment_status = BillStatus::CN_GENERATING;
            }

            if ($this->lostReport instanceof TemporaryUploadedFile) {
                $lostReport = $this->lostReport->store('dlLostReport', 'local');
                $this->subject->lost_report_path = $lostReport;
            }

            DB::beginTransaction();

            if (!$this->subject->save()) throw new Exception('Failed to save application payment status');

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

            DB::commit();

            if ($this->subject->status = DlApplicationStatus::STATUS_PENDING_PAYMENT && $transition === 'zra_officer_review') {
                event(new SendSms(SendCustomSMS::SERVICE, NULL, [
                    'phone' => $this->subject->taxpayer->mobile,
                    'message' => "Hello {$this->subject->taxpayer->fullname()}, your driver license application has been approved, you will receive your payment control number shortly."
                ]));
            }

            // Generate Control Number after MVR SC Approval
            if ($this->subject->status == DlApplicationStatus::STATUS_PENDING_PAYMENT && $transition === 'zra_officer_review') {
                $this->generateControlNumber();
            }

            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('ERROR-APPROVING-DL-APPLICATION', [$exception]);
            $this->customAlert(AlertType::ERROR, CustomMessage::ERROR);
            return;
        }
    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];

        $this->validate([
            'comments' => 'required|strip_tag',
        ]);

        try {
            DB::beginTransaction();

            if ($this->checkTransition('police_officer_reject')) {
                $this->subject->status = DlApplicationStatus::REJECTED;
                if (!$this->subject->save()) throw new Exception('Failed to save application status');
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

            DB::commit();

            if ($this->subject->status === DlApplicationStatus::REJECTED) {
                // Send correction email/sms
                event(new SendSms(SendCustomSMS::SERVICE, null, [
                    'phone' => $this->subject->taxpayer->mobile,
                    'message' => "Hello {$this->subject->taxpayer->full_name}, your driver license application has been rejected, Please login to the system to view the reasons."
                ]));
            }

            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('ERROR-REJECTING-DL-APPLICATION', [$exception]);
            $this->customAlert(AlertType::ERROR, CustomMessage::ERROR);
        }

    }

    protected $listeners = [
        'approve',
        'reject'
    ];

    public function confirmPopUpModal($action, $transition)
    {
        $this->customAlert('warning', CustomMessage::ERROR, [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Confirm',
            'onConfirmed' => $action,
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'transition' => $transition
            ],

        ]);
    }

    public function generateControlNumber()
    {
        try {
            // Fetch the fee
            $fee = DlFee::query()
                ->select('id', 'name', 'amount', 'type', 'gfs_code')
                ->where('dl_license_duration_id', $this->subject->license_duration_id)
                ->where('type', $this->subject->type)
                ->first();

            if (empty($fee)) {
                $errorMessage = "Driver License fee for this application is not configured";
                $this->customAlert(AlertType::ERROR, $errorMessage);
                return;
            } else {
                $classFactor = 1;
                if ($this->subject->type == GeneralConstant::ADD_CLASS) {
                    $classFactor = $this->subject->application_license_classes->count() -
                        $this->subject->previousApplication->application_license_classes->count();
                }
                $this->generateDLicenseControlNumber($this->subject, $fee, $classFactor);
            }
        } catch (Exception $e) {
            Log::error('DL-GENERATE-CONTROL-NUMBER', [$e]);
            $this->customAlert(AlertType::ERROR, CustomMessage::FAILED_TO_GENERATE_CONTROL_NUMBER);
        }
    }

    public function render()
    {
        return view('livewire.approval.mvr.driver-license');
    }
}
