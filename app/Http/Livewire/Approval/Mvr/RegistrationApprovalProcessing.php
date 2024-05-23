<?php

namespace App\Http\Livewire\Approval\Mvr;

use App\Enum\BillStatus;
use App\Enum\MvrRegistrationStatus;
use App\Events\SendSms;
use App\Jobs\SendCustomSMS;
use App\Models\MvrFee;
use App\Models\MvrFeeType;
use App\Models\MvrInspectionReport;
use App\Models\MvrPlateNumberStatus;
use App\Models\MvrRegistrationType;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class RegistrationApprovalProcessing extends Component
{
    use CustomAlert, WorkflowProcesssingTrait, PaymentsTrait, WithFileUploads;

    public $modelId;
    public $modelName;
    public $comments;
    public $mileage, $inspectionReport, $inspectionDate, $inspection;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->registerWorkflow($modelName, $this->modelId);
        $this->inspection = MvrInspectionReport::select('report_path', 'inspection_date', 'inspection_mileage')
            ->where('mvr_registration_id', $this->modelId)
            ->first();

        if ($this->inspection) {
            $this->inspectionDate = Carbon::create($this->inspection->inspection_date)->format('Y-m-d');
            $this->mileage = $this->inspection->inspection_mileage;
            $this->inspectionReport = $this->inspection->report_path;
        }
    }

    public function approve($transition)
    {
        if (!$this->subject->regtype->color){
            $this->customAlert('error', 'Please set Plate no. color for this registration type.');
            return;
        }

        $transition = $transition['data']['transition'];

        if ($this->checkTransition('zbs_officer_review')) {
            $this->validate([
                'inspectionDate' => 'required|date',
                'inspectionReport' => [$this->inspectionReport === ($this->inspection->report_path ?? null) ? 'required' : 'nullable', 'max:1024', 'valid_pdf'],
                'mileage' => 'required|numeric',
                'comments' => 'required|strip_tag',
            ]);
        } else {
            $this->validate([
                'comments' => 'required|strip_tag',
            ]);
        }

        try {
            DB::beginTransaction();
            if ($this->checkTransition('zbs_officer_review')) {

                if ($this->inspectionReport === ($this->inspection->report_path ?? null)) {
                    $inspectionReport = $this->inspectionReport;
                } else {
                    $inspectionReport = $this->inspectionReport->store('inspection_reports');
                }

                $report = MvrInspectionReport::updateOrCreate([
                    'mvr_registration_id' => $this->subject->id
                ],[
                    'inspection_date' => $this->inspectionDate,
                    'report_path' => $inspectionReport,
                    'inspection_mileage' => $this->mileage,
                    'mvr_registration_id' => $this->subject->id
                ]);

                if (!$report) {
                    throw new Exception("Could not persist MVR Inspection report into the database.");
                }
            }

            if ($this->checkTransition('mvr_registration_manager_review') && $transition === 'mvr_registration_manager_review') {
                $this->subject->status = MvrRegistrationStatus::STATUS_PENDING_PAYMENT;
                $this->subject->mvr_plate_number_status = MvrPlateNumberStatus::STATUS_NOT_ASSIGNED;
                $this->subject->save();

                $regType = $this->subject->regtype;

                if (in_array($regType->name, [MvrRegistrationType::TYPE_PRIVATE]))
                if (!$regType->initial_plate_number) {
                    $this->customAlert('warning', 'Please make sure initial plate number for this registration type has been created');
                    return;
                }
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
            Log::error('MVR-REGISTRATION-APPROVAL-APPROVE', [$exception]);
            $this->customAlert('error', 'Something went wrong');
            return;
        }

        // Generate Control Number after MVR RM Approval
        if ($this->subject->status == MvrRegistrationStatus::STATUS_PENDING_PAYMENT && $transition === 'mvr_registration_manager_review') {
            try {
                $this->generateControlNumber();
            } catch (Exception $exception) {
                $this->flash('error', 'Failed to generate control number, please try again', [], redirect()->back()->getTargetUrl());
            }
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

            if ($this->checkTransition('zbs_officer_review')) {
                $this->subject->status = MvrRegistrationStatus::CORRECTION;
                $this->subject->save();
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

            DB::commit();

            if ($this->subject->status == MvrRegistrationStatus::CORRECTION) {
                event(new SendSms(SendCustomSMS::SERVICE, NULL, ['phone' => $this->subject->taxpayer->mobile, 'message' => "
                Hello {$this->subject->taxpayer->fullname}, your motor vehicle registration request for chassis number {$this->subject->chassis->chassis_number} requires correction, please login to the system to perform data update."]));
            }

            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('MVR-REGISTRATION-APPROVAL-REJECT', [$exception]);
            $this->customAlert('error', 'Something went wrong');
        }

    }

    protected $listeners = [
        'approve', 'reject'
    ];

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

    public function generateControlNumber()
    {
        try {
            $feeType = MvrFeeType::query()->firstOrCreate(['type' => MvrFeeType::TYPE_REGISTRATION]);

            $fee = MvrFee::query()->where([
                'mvr_registration_type_id' => $this->subject->mvr_registration_type_id,
                'mvr_fee_type_id' => $feeType->id,
                'mvr_class_id' => $this->subject->mvr_class_id
            ])->first();

            if (empty($fee)) {
                $this->customAlert('error', "Registration fee for selected registration type is not configured");
                return;
            }

            DB::beginTransaction();

            $this->subject->status = MvrRegistrationStatus::STATUS_PENDING_PAYMENT;
            $this->subject->payment_status = BillStatus::CN_GENERATING;
            $this->subject->save();

            //Generate control number
            $this->generateMvrControlNumber($this->subject, $fee);

            DB::commit();

            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error('MVR-REGISTRATION-APPROVAL-CN-GENERATION', [$exception]);
            $this->customAlert('error', 'Failed to generate control number, please try again');
        }
    }

    public function render()
    {
        return view('livewire.approval.mvr.registration');
    }
}

