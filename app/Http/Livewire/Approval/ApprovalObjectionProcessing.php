<?php

namespace App\Http\Livewire\Approval;

use App\Models\Objection;
use App\Models\ObjectionStatus;
use App\Models\Returns\ReturnStatus;
use App\Models\TaxType;
use App\Models\Waiver;
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

class ApprovalObjectionProcessing extends Component
{
    use WorkflowProcesssingTrait, WithFileUploads, LivewireAlert;
    public $modelId;
    public $modelName;
    public $comments, $objection;
    public $objectionReport;
    public $penaltyPercent, $penaltyAmount;
    public $interestPercent, $interestAmount, $waiver, $assesment, $total;
    public $natureOfAttachment, $noticeReport, $settingReport;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = $modelId;
        $this->objection = Objection::find($this->modelId);
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
            $this->penaltyAmount = ($this->objection->taxVerificationAssesment->penalty_amount * $this->penaltyPercent) / 100;

        }

        if ($propertyName == "interestPercent") {
            if ($this->interestPercent > 50) {
                $this->interestPercent = 50;
            } elseif ($this->interestPercent < 0 || !is_numeric($this->interestPercent)) {
                $this->interestPercent = null;
            }
            $this->interestAmount = ($this->objection->taxVerificationAssesment->interest_amount * $this->interestPercent) / 100;
        }

        $this->total = $this->interestAmount + $this->penaltyAmount + $this->objection->taxVerificationAssesment->principal_amount;

    }

    public function approve($transtion)
    {
        $this->validate([
            'comments' => 'required',
        ]);

        if ($this->checkTransition('objection_manager_review')) {

            $this->validate(
                [
                    'objectionReport' => 'required|mimes:pdf',
                ]
            );

            $objectionReport = "";
            if ($this->objectionReport) {
                $objectionReport = $this->objectionReport->store('objectionReport', 'local-admin');
            }

            $noticeReport = "";
            if ($this->noticeReport) {
                $noticeReport = $this->noticeReport->store('notice_report', 'local-admin');
            }

            $settingReport = "";
            if ($this->settingReport) {
                $settingReport = $this->settingReport->store('setting_report', 'local-admin');
            }

            $objection = Objection::find($this->modelId);
            
            DB::beginTransaction();
            try {

                $objection->update([
                    'objection_report' => $objectionReport ?? '',
                    'notice_report' => $noticeReport ?? '',
                    'setting_report' => $settingReport ?? '',
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

            DB::beginTransaction();

            try {

                // Generate control number for waived application
                $billitems = [
                    [
                        'billable_id' => $this->objection->id,
                        'billable_type' => get_class($this->objection),
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
                $description = "objection";
                $payment_option = ZmCore::PAYMENT_OPTION_FULL;
                $currency = 'TZS';
                $createdby_type = get_class(Auth::user());
                $createdby_id = Auth::id();
                $exchange_rate = 0;
                $payer_id = $taxpayer->id;
                $expire_date = Carbon::now()->addMonth()->toDateTimeString();
                $billableId = $this->objection->id;
                $billableType = get_class($this->objection);

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
                        $this->objection->status = ReturnStatus::CN_GENERATING;
                        $this->objection->save();

                        $this->flash('success', 'A control number has been generated successful.');
                    } else {

                        session()->flash('error', 'Control number generation failed, try again later');
                        $this->objection->status = ReturnStatus::CN_GENERATION_FAILED;
                    }

                    $this->objection->save();
                } else {

                    // We are local
                    // $this->waiver->status = ReturnStatus::CN_GENERATED;
                    $this->objection->save();

                    // Simulate successful control no generation
                    $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
                    $zmBill->zan_status = 'pending';
                    $zmBill->control_number = '90909919991909';
                    $zmBill->save();

                    $this->subject->verified_at = Carbon::now()->toDateTimeString();
                    $this->subject->status = ObjectionStatus::APPROVED;
                    $this->subject->save();
                    // event(new SendSms('business-registration-approved', $this->subject->id));
                    // event(new SendMail('business-registration-approved', $this->subject->id));

                    $this->flash('success', 'A control number for this Objection has been generated successfull and approved');
                }

                DB::commit();
            } catch (Exception $e) {
                Log::error($e);
                throw $e;
                $this->alert('error', 'Something went wrong');
            }

            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());

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
                $this->subject->status = ObjectionStatus::CORRECTION;
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
        return view('livewire.approval.approval-objection-processing');
    }
}
