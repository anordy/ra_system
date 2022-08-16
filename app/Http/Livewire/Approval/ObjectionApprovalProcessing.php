<?php

namespace App\Http\Livewire\Approval;

use App\Models\Disputes\Dispute;
use App\Models\Returns\ReturnStatus;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxType;
use App\Models\WaiverStatus;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class ObjectionApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, WithFileUploads, LivewireAlert;
    public $modelId;
    public $modelName;
    public $comments;
    public $disputeReport;
    public $taxTypes;
    public $penaltyPercent, $penaltyAmount, $penaltyAmountDue, $interestAmountDue;
    public $interestPercent, $interestAmount, $dispute, $assesment, $total;
    public $natureOfAttachment, $noticeReport, $settingReport;

    public function mount($modelName, $modelId)
    {

        $this->modelName = $modelName;
        $this->modelId = $modelId;
        $this->dispute = Dispute::find($this->modelId);
        $this->assessment = TaxAssessment::find($this->dispute->assesment_id);
        $this->taxTypes = TaxType::all();
        $this->registerWorkflow($modelName, $modelId);

    }

    public function updated($propertyName)
    {
        if ($propertyName == "penaltyPercent") {
            if ($this->penaltyPercent > 100) {
                $this->penaltyPercent = 100;
            } elseif ($this->penaltyPercent < 0 || !is_numeric($this->penaltyPercent)) {
                $this->penaltyPercent = null;
            }
            $this->penaltyAmount = ($this->assessment->penalty_amount * $this->penaltyPercent) / 100;
        }

        if ($propertyName == "interestPercent") {
            if ($this->interestPercent > 50) {
                $this->interestPercent = 50;
            } elseif ($this->interestPercent < 0 || !is_numeric($this->interestPercent)) {
                $this->interestPercent = null;
            }
            $this->interestAmount = ($this->assessment->interest_amount * $this->interestPercent) / 100;
        }

        $this->penaltyAmountDue = $this->assessment->penalty_amount - $this->penaltyAmount;
        $this->interestAmountDue = $this->assessment->interest_amount - $this->interestAmount;
        $this->total = ($this->penaltyAmountDue + $this->interestAmountDue + $this->assessment->principal_amount) - ($this->dispute->tax_deposit);
    }

    public function approve($transtion)
    {

        $taxType = $this->subject->taxType;

        if ($this->checkTransition('objection_manager_review')) {

            $this->validate(
                [
                    'disputeReport' => 'required|mimes:pdf',
                ]
            );

            $disputeReport = "";
            if ($this->disputeReport) {
                $disputeReport = $this->disputeReport->store('waiver_report', 'local-admin');
            }

            $noticeReport = "";
            if ($this->noticeReport) {
                $noticeReport = $this->noticeReport->store('notice_report', 'local-admin');
            }

            $settingReport = "";
            if ($this->settingReport) {
                $settingReport = $this->settingReport->store('setting_report', 'local-admin');
            }

            $dispute = Dispute::find($this->modelId);

            DB::beginTransaction();
            try {

                $dispute->update([
                    'dispute_report' => $disputeReport ?? '',
                    'notice_report' => $noticeReport ?? '',
                    'setting_report' => $settingReport ?? '',
                ]);

                DB::commit();
            } catch (\Exception $e) {
                Log::error($e);
                DB::rollBack();
                $this->alert('error', 'Something went wrong.');
            }

        }

        if ($this->checkTransition('chief_assurance_review')) {

        }

        if ($this->checkTransition('commisioner_review')) {
            $this->validate([
                'interestPercent' => 'required',
                'penaltyPercent' => 'required',
                
            ]);
            DB::beginTransaction();

            try {

                // Generate control number for waived application
                $billitems = [
                    [
                        'billable_id' => $this->dispute->id,
                        'billable_type' => get_class($this->dispute),
                        'use_item_ref_on_pay' => 'N',
                        'amount' => $this->total,
                        'currency' => 'TZS',
                        'gfs_code' => $this->taxTypes->where('code', 'verification')->first()->gfs_code,
                        'tax_type_id' => $this->taxTypes->where('code', 'verification')->first()->id,
                    ],
                ];

                $taxpayer = $this->subject->business->taxpayer;

                $payer_type = get_class($taxpayer);
                $payer_name = implode(" ", array($taxpayer->first_name, $taxpayer->last_name));
                $payer_email = $taxpayer->email;
                $payer_phone = $taxpayer->mobile;
                $description = "dispute";
                $payment_option = ZmCore::PAYMENT_OPTION_FULL;
                $currency = 'TZS';
                $createdby_type = get_class(Auth::user());
                $createdby_id = Auth::id();
                $exchange_rate = 0;
                $payer_id = $taxpayer->id;
                $expire_date = Carbon::now()->addMonth()->toDateTimeString();
                $billableId = $this->dispute->id;
                $billableType = get_class($this->dispute);

                $zmBill = ZmCore::createBill(
                    $billableId,
                    $billableType,
                    $this->taxTypes->where('code', 'verification')->first()->id,
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
                        $this->dispute->status = ReturnStatus::CN_GENERATING;
                        $this->dispute->save();

                        $this->flash('success', 'A control number has been generated successful.');
                    } else {

                        session()->flash('error', 'Control number generation failed, try again later');
                        $this->dispute->status = ReturnStatus::CN_GENERATION_FAILED;
                    }

                    $this->dispute->save();
                } else {

                    // We are local
                    // $this->dispute->status = ReturnStatus::CN_GENERATED;
                    $this->dispute->save();

                    // Simulate successful control no generation
                    $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
                    $zmBill->zan_status = 'pending';
                    $zmBill->control_number = '90909919991909';
                    $zmBill->save();

                    $this->subject->verified_at = Carbon::now()->toDateTimeString();
                    $this->subject->status = WaiverStatus::APPROVED;
                    $this->subject->save();
                    // event(new SendSms('business-registration-approved', $this->subject->id));
                    // event(new SendMail('business-registration-approved', $this->subject->id));

                    $this->flash('success', 'A control number for this dispute has been generated successfull and approved');
                }

                DB::commit();
            } catch (Exception $e) {
                Log::error($e);
                // throw $e;
                $this->alert('error', 'Something went wrong');
            }

        }

        try {
            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments]);
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong.');
            return;
        }
    }

    public function reject($transtion)
    {

        try {
            if ($this->checkTransition('application_filled_incorrect')) {
                $this->subject->status = WaiverStatus::CORRECTION;
                // event(new SendSms('business-registration-correction', $this->subject->id));
                // event(new SendMail('business-registration-correction', $this->subject->id));
            }

            if ($this->checkTransition('chief_assurance_reject')) {
                $this->validate([
                    'comments' => 'required',
                ]);

            }

            if ($this->checkTransition('commisioner_reject')) {
                $this->validate([
                    'comments' => 'required',
                ]);

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
        return view('livewire.approval.objection-approval-processing');
    }

}
