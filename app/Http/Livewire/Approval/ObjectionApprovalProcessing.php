<?php

namespace App\Http\Livewire\Approval;

use App\Models\Disputes\Dispute;
use App\Models\Returns\ReturnStatus;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxType;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use App\Traits\PaymentsTrait;
use App\Traits\TaxAssessmentDisputeTrait;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class ObjectionApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, WithFileUploads, TaxAssessmentDisputeTrait, PaymentsTrait, CustomAlert;
    public $modelId;
    public $modelName;
    public $comments;
    public $disputeReport;
    public $taxTypes;
    public $complete = '0';
    public $penaltyPercent, $penaltyAmount, $penaltyAmountDue, $interestAmountDue;
    public $interestPercent, $interestAmount, $dispute, $assesment, $total, $principal_amount_due;
    public $natureOfAttachment, $noticeReport, $settingReport;
    public $principal, $penalty, $interest;
    public $propertyName;
    public $assessment;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->dispute = Dispute::find($this->modelId);
        if (is_null($this->dispute)) {
            abort(404);
        }
        $this->principal = $this->dispute->tax_in_dispute;
        $this->assessment = TaxAssessment::find($this->dispute->assesment_id);
        if(is_null($this->assessment)){
            abort(404);
        }
        $this->penalty = $this->assessment->penalty_amount;
        $this->interest = $this->assessment->interest_amount;
        $this->taxTypes = TaxType::where('code', 'disputes')->first();
        if(!$this->taxTypes){
            abort(404);
        }
        $this->principal_amount_due = $this->assessment->principal_amount - $this->dispute->tax_deposit;
        $this->total = ($this->penalty + $this->interest + $this->principal) - ($this->dispute->tax_deposit);

        $this->registerWorkflow($modelName, $this->modelId);
    }

    public function updated($propertyName)
    {
        $this->propertyName = $propertyName;
        if ($propertyName == "penaltyPercent") {
            if (!is_numeric($this->interestPercent)) {
                $this->penaltyPercent = null;
            }
            if ($this->penaltyPercent > 100) {
                $this->penaltyPercent = 100;
            } elseif ($this->penaltyPercent < 0) {
                $this->penaltyPercent = null;
            }
            $this->penaltyAmount = ($this->assessment->penalty_amount * $this->penaltyPercent) / 100;
        }

        if ($propertyName == "interestPercent") {
            if (!is_numeric($this->interestPercent)) {
                $this->interestPercent = null;
            }
            if ($this->interestPercent > 50) {
                $this->interestPercent = 50;
            } elseif ($this->interestPercent < 0) {
                $this->interestPercent = null;
            }
            $this->interestAmount = ($this->assessment->interest_amount * $this->interestPercent) / 100;
        }

        if ($propertyName == "interestPercent" || $propertyName == "penaltyPercent") {
            $this->penalty = $this->assessment->penalty_amount - $this->penaltyAmount;
            $this->interest = $this->assessment->interest_amount - $this->interestAmount;
            $this->total = ($this->penalty + $this->interest + $this->assessment->principal_amount) - ($this->dispute->tax_deposit);
        }

        if ($propertyName == "penalty" || $propertyName == "interest") {
            $this->total = (str_replace(',', '', $this->penalty) + str_replace(',', '', $this->interest) + str_replace(',', '', $this->principal)) - ($this->dispute->tax_deposit);
        }
    }

    public function approve($transition)
    {
        $transition = $transition['data']['transition'];

        $this->penalty = str_replace(',', '', $this->penalty);
        $this->interest = str_replace(',', '', $this->interest);
        $this->principal = str_replace(',', '', $this->principal);

        if ($this->checkTransition('objection_manager_review')) {

            $this->validate(
                [
                    'disputeReport' => 'required|mimes:pdf',
                ]
            );

            $disputeReport = "";
            if ($this->disputeReport) {
                $disputeReport = $this->disputeReport->store('waiver_report', 'local');
            }

            $noticeReport = "";
            if ($this->noticeReport) {
                $noticeReport = $this->noticeReport->store('notice_report', 'local');
            }

            $settingReport = "";
            if ($this->settingReport) {
                $settingReport = $this->settingReport->store('setting_report', 'local');
            }

            $dispute = Dispute::find($this->modelId);
            if(is_null($dispute)){
                abort(404);
            }
            DB::beginTransaction();
            try {

                $dispute->update([
                    'dispute_report' => $disputeReport ?? null,
                    'notice_report' => $noticeReport ?? null,
                    'setting_report' => $settingReport ?? null,
                ]);

                $this->addDisputeToAssessment($this->assessment, $this->dispute->category, $this->principal, $this->penalty, $this->interest, $this->dispute->tax_deposit);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error($e);
                $this->customAlert('error', 'Something went wrong, please contact the administrator for help.');
            }
        }

        if ($this->checkTransition('chief_assurance_review')) {
        }

        if ($this->checkTransition('commisioner_review')) {
            $this->complete = "1";

            if ($this->propertyName == "interestPercent" || $this->propertyName == "penaltyPercent") {
                $this->validate([
                    'interestPercent' => ['required', 'numeric'],
                    'penaltyPercent' => ['required', 'numeric'],
                ]);
            }

            DB::beginTransaction();

            try {
                $this->addDisputeToAssessment($this->assessment, $this->dispute->category, $this->principal, $this->penalty, $this->interest, $this->dispute->tax_deposit);

                if ($this->total <= 0) {
                    session()->flash('message', 'Bill amount can not be zero, null or not a number');
                    return;
                }

                // Generate control number for waived application
                $billitems = [
                    [
                        'billable_id' => $this->dispute->id,
                        'billable_type' => get_class($this->dispute),
                        'use_item_ref_on_pay' => 'N',
                        'amount' => $this->total,
                        'currency' => 'TZS',
                        'gfs_code' => $this->taxTypes->gfs_code,
                        'tax_type_id' => $this->taxTypes->id,
                    ],
                ];


                $taxpayer = $this->subject->business->taxpayer;

                $payer_type = get_class($taxpayer);
                $payer_name = implode(" ", array($taxpayer->first_name, $taxpayer->last_name));
                $payer_email = $taxpayer->email;
                $payer_phone = $taxpayer->mobile;
                $description = "dispute for assessment";
                $payment_option = ZmCore::PAYMENT_OPTION_FULL;
                $currency = 'TZS';
                $createdby_type = get_class(Auth::user());
                $createdby_id = Auth::id();
                $exchange_rate = 1;
                $payer_id = $taxpayer->id;
                $expire_date = Carbon::now()->addMonth()->toDateTimeString();
                $billableId = $this->assessment->id;
                $billableType = get_class($this->assessment);

                $zmBill = ZmCore::createBill(
                    $billableId,
                    $billableType,
                    $this->taxTypes->where('code', 'verification')->firstOrFail()->id,
                    $payer_id,
                    $payer_type,
                    $payer_name,
                    $payer_email,
                    $payer_phone,
                    $expire_date,
                    $description,
                    $payment_option,
                    $currency,
                    $exchange_rate,
                    $createdby_id,
                    $createdby_type,
                    $billitems
                );

                if (config('app.env') != 'local') {
                    $response = ZmCore::sendBill($zmBill->id);
                    if ($response->status === ZmResponse::SUCCESS) {
                        $this->dispute->payment_status = ReturnStatus::CN_GENERATING;
                        $this->dispute->save();

                        $this->flash('success', 'A control number has been generated successful.');
                    } else {

                        session()->flash('error', 'Control number generation failed, try again later');
                        $this->dispute->payment_status = ReturnStatus::CN_GENERATION_FAILED;
                    }

                    $this->dispute->save();
                } else {

                    // We are local
                    $this->dispute->payment_status = ReturnStatus::CN_GENERATED;
                    $this->dispute->save();

                    // Simulate successful control no generation
                    $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
                    $zmBill->zan_status = 'pending';
                    $zmBill->control_number = rand(2000070001000, 2000070009999);
                    $zmBill->save();
                    $this->flash('success', 'A control number for this dispute has been generated successfull and approved');
                }

                DB::commit();
            } catch (Exception $e) {
                Log::error($e);
                $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            }
        }

        try {
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help.');
            return;
        }
    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];

        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);

        DB::beginTransaction();

        try {

            if ($this->checkTransition('commisioner_reject')) {

                $this->addDisputeToAssessment($this->assessment, $this->dispute->category, $this->principal_amount_due, $this->assessment->penalty_amount, $this->assessment->interest_amount, $this->dispute->tax_deposit);
                $total_deposit = $this->principal_amount_due + $this->assessment->interest_amount + $this->assessment->penalty_amount;

                // Generate control number for waived application
                $billitems = [
                    [
                        'billable_id' => $this->assessment->id,
                        'billable_type' => get_class($this->assessment),
                        'use_item_ref_on_pay' => 'N',
                        'amount' => $total_deposit,
                        'currency' => 'TZS',
                        'gfs_code' => $this->taxTypes->gfs_code,
                        'tax_type_id' => $this->taxTypes->id,
                    ],
                ];

                $taxpayer = $this->subject->business->taxpayer;

                $payer_type = get_class($taxpayer);
                $payer_name = implode(" ", array($taxpayer->first_name, $taxpayer->last_name));
                $payer_email = $taxpayer->email;
                $payer_phone = $taxpayer->mobile;
                $description = "dispute for assessment";
                $payment_option = ZmCore::PAYMENT_OPTION_FULL;
                $currency = 'TZS';
                $createdby_type = get_class(Auth::user());
                $createdby_id = Auth::id();
                $exchange_rate = 1;
                $payer_id = $taxpayer->id;
                $expire_date = Carbon::now()->addMonth()->toDateTimeString();
                $billableId = $this->assessment->id;
                $billableType = get_class($this->assessment);

                $zmBill = ZmCore::createBill(
                    $billableId,
                    $billableType,
                    $this->taxTypes->where('code', 'verification')->firstOrFail()->id,
                    $payer_id,
                    $payer_type,
                    $payer_name,
                    $payer_email,
                    $payer_phone,
                    $expire_date,
                    $description,
                    $payment_option,
                    $currency,
                    $exchange_rate,
                    $createdby_id,
                    $createdby_type,
                    $billitems
                );

                if (config('app.env') != 'local') {
                    $response = ZmCore::sendBill($zmBill->id);
                    if ($response->status === ZmResponse::SUCCESS) {
                        $this->dispute->payment_status = ReturnStatus::CN_GENERATING;
                        $this->dispute->save();

                        $this->flash('success', 'A control number has been generated successful.');
                    } else {

                        session()->flash('error', 'Control number generation failed, try again later');
                        $this->dispute->payment_status = ReturnStatus::CN_GENERATION_FAILED;
                    }

                    $this->dispute->save();
                } else {

                    // We are local
                    $this->dispute->payment_status = ReturnStatus::CN_GENERATED;
                    $this->dispute->save();

                    // Simulate successful control no generation
                    $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
                    $zmBill->zan_status = 'pending';
                    $zmBill->control_number = rand(2000070001000, 2000070009999);
                    $zmBill->save();
                    $this->flash('success', 'A control number for this dispute has been generated successfull and approved');
                }
            }

            DB::commit();

            $this->doTransition($transition, ['status' => 'reject', 'comment' => $this->comments]);
            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            return;
        }
    }

    protected $listeners = [
        'approve', 'reject',
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
                'transition' => $transition,
            ],

        ]);
    }

    public function render()
    {
        return view('livewire.approval.objection-approval-processing');
    }
}
