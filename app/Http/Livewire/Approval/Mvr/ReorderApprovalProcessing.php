<?php

namespace App\Http\Livewire\Approval\Mvr;

use App\Enum\BillStatus;
use App\Enum\Currencies;
use App\Enum\MvrRegistrationStatus;
use App\Enum\MvrReorderStatus;
use App\Enum\TransactionType;
use App\Events\SendSms;
use App\Jobs\SendCustomSMS;
use App\Models\MvrFee;
use App\Models\MvrFeeType;
use App\Models\MvrPlateNumberStatus;
use App\Models\MvrRegistrationStatusChangeFile;
use App\Models\MvrRegistrationStatusChange;
use App\Models\MvrReorderPlateNumberFee;
use App\Models\MvrReorderPlateNumberFile;
use App\Models\TaxType;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use App\Traits\TaxpayerLedgerTrait;
use App\Traits\WorkflowProcesssingTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class ReorderApprovalProcessing extends Component
{
    use CustomAlert, WorkflowProcesssingTrait, PaymentsTrait,WithFileUploads, TaxpayerLedgerTrait;

    public $modelId;
    public $modelName;
    public $comments;
    public $approvalReport;
    public $lossReport;
    public $attachments = [];

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId   = decrypt($modelId);
        $this->registerWorkflow($modelName, $this->modelId);
        $this->attachments = [
            [
                'name' => '',
                'file' => '',
            ],
        ];
    }

    public function approve($transition) {
        $transition = $transition['data']['transition'];

        $this->validate([
            'comments' => 'required|strip_tag',
        ]);

        if ($this->checkTransition('mvr_police_review')) {
            $this->validate(
                [
                    'lossReport' => $this->subject->loss_report ? 'nullable' : 'required|mimes:pdf|max:1024|max_file_name_length:100|valid_pdf',
                ],
                [
                    'lossReport.required' => 'Please upload Loss Report document'
                ]
            );
        }


        if ($this->checkTransition('mvr_zartsa_review')) {
            $this->validate(
                [
                    'approvalReport' => $this->subject->approval_report ? 'nullable' : 'required|mimes:pdf|max:1024|max_file_name_length:100|valid_pdf',
                    'attachments.*.name' => count($this->subject->attachments) > 0 ? 'nullable' : 'required|strip_tag',
                    'attachments.*.file' => count($this->subject->attachments) > 0 ? 'nullable' : 'required|mimes:pdf|max:1024|max_file_name_length:100|valid_pdf',
                ],
                [
                    'approvalReport.required' => 'Please upload Inspection report document'
                ]
            );
        }

        try {
            DB::beginTransaction();

            if ($this->checkTransition('mvr_police_review')) {

                $lossReport = $this->lossReport;
                if ($this->subject->loss_report != $this->lossReport && $this->lossReport) {
                        $lossReport = $this->lossReport->store('mvrPoliceReport', 'local');
                }

                $this->subject->loss_report = $lossReport;
                $this->subject->save();
            }

            if ($this->checkTransition('mvr_zartsa_review')) {

                foreach ($this->attachments as $attachment) {
                    if ($attachment['file'] && $attachment['name']) {
                        $documentPath = $attachment['file']->store("/mvr_status_change");

                        $file = MvrReorderPlateNumberFile::create([
                            'mvr_reorder_plate_number_id' => $this->subject->id,
                            'location' => $documentPath,
                            'name' => $attachment['name'],
                        ]);


                        if (!$file) throw new Exception('Failed to save mvr reorder plate number file');

                    }
                }

                $approvalReport = $this->approvalReport;
                if ($this->subject->approval_report != $this->approvalReport && $this->approvalReport) {
                        $approvalReport = $this->approvalReport->store('mvrZartsaReport', 'local');
                }

                $this->subject->approval_report = $approvalReport;
                $this->subject->save();
            }


            if ($this->checkTransition('mvr_registration_manager_review') && $transition === 'mvr_registration_manager_review') {
                $this->subject->status = MvrRegistrationStatus::STATUS_PENDING_PAYMENT;
                $this->subject->payment_status = BillStatus::CN_GENERATING;
                if($this->subject->replacement_reason == MvrReorderStatus::LOST) {
                $this->subject->mvr_plate_number_status = MvrPlateNumberStatus::STATUS_LOST;
                } else {
                    $this->subject->mvr_plate_number_status = MvrPlateNumberStatus::STATUS_DISTORTED;
                }
                $this->subject->save();
            }

           
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

            DB::commit();

            // Send correction email/sms
            if ($this->subject->status = MvrRegistrationStatus::STATUS_PENDING_PAYMENT && $transition === 'mvr_registration_manager_review') {
                event(new SendSms(SendCustomSMS::SERVICE, NULL, ['phone' => $this->subject->taxpayer->mobile, 'message' => "
                Hello {$this->subject->taxpayer->fullname}, your motor vehicle registration request for chassis number {$this->subject->chassis->chassis_number} has been approved, you will receive your payment control number shortly."]));
            }

            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('MVR-REORDER-PLATE-NUMBER-APPROVAL-APPROVE', [$exception]);
            $this->customAlert('error', 'Something went wrong');
            return;
        }

        // Generate Control Number after MVR SC Approval
        if ($this->subject->status == MvrReorderStatus::STATUS_PENDING_PAYMENT && $transition === 'mvr_registration_manager_review') {
            try {
                $payload = [
                    'quantity' => $this->subject->quantity,
                     'is_rfid' => $this->subject->is_rfid
                     ];
                $fee = MvrReorderPlateNumberFee::query()->where($payload)->first();;

                if (empty($fee)) {
                    $this->customAlert('error', "Registration fee for selected reorder plate number  is not configured");
                    return;
                }

                $this->generateControlNumber($fee);


            } catch (Exception $exception) {
                $this->customAlert('error', 'Failed to generate control number, please try again');
            }
        }

    }

    public function reject($transition) {
        $transition = $transition['data']['transition'];

        $this->validate([
            'comments' => 'required|strip_tag',
        ]);

        try {
            DB::beginTransaction();

            if ($this->checkTransition('application_rejected')) {
                $this->subject->status = MvrRegistrationStatus::REJECTED;
                $this->subject->save();
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

            DB::commit();

            if ($this->subject->status == MvrRegistrationStatus::REJECTED) {
                // Send correction email/sms
                event(new SendSms(SendCustomSMS::SERVICE, NULL, ['phone' => $this->subject->taxpayer->mobile, 'message' => "
                Hello {$this->subject->taxpayer->fullname}, your motor vehicle status change request for chassis number {$this->subject->chassis->chassis_number} has been rejected. Please ensure your details and re-submit your application again."]));
            }

            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('MVR-STATUS-APPROVAL-REJECT', [$exception]);
            $this->customAlert('error', 'Something went wrong');
        }

    }

    protected $listeners = [
        'approve', 'reject'
    ];

    public function addAttachment()
    {
        $this->attachments[] = [
            'name' => '',
            'file' => '',
        ];
    }

    public function removeAttachment($i)
    {
        unset($this->attachments[$i]);
    }

    public function confirmPopUpModal($action, $transition)
    {
        $this->customAlert('warning', 'Are you sure you want to complete this action?', [
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

    public function generateControlNumber($fee) {
        try {
            DB::beginTransaction();

            $this->generateMvrControlNumber($this->subject, $fee);
            DB::commit();
            $this->flash('success', 'Approved Successful', [], redirect()->back()->getTargetUrl());
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error('MVR-STATUS-APPROVAL-CN-GENERATION', [$exception]);
            $this->customAlert('error', 'Failed to generate control number, please try again');
        }
    }

    public function render()
    {
        return view('livewire.approval.mvr.reorder');
    }
}
