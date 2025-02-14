<?php

namespace App\Http\Livewire\Approval;

use App\Enum\DisputeStatus;
use App\Enum\TaxClearanceStatus;
use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\Sequence;
use App\Models\TaxClearanceRequest;
use App\Models\TaxType;
use App\Traits\PaymentsTrait;
use App\Traits\TaxAssessmentDisputeTrait;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class TaxClearenceApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, WithFileUploads, TaxAssessmentDisputeTrait, PaymentsTrait, CustomAlert;
    public $modelId;
    public $modelName;
    public $comments;
    public $disputeReport;
    public $taxTypes;
    public $penaltyPercent, $penaltyAmount, $penaltyAmountDue, $interestAmountDue;
    public $interestPercent, $interestAmount, $tax_clearence, $assesment, $total;
    public $natureOfAttachment, $noticeReport, $settingReport;

    public function mount($modelName, $modelId)
    {

        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->tax_clearence = TaxClearanceRequest::find($this->modelId);
        if (is_null($this->tax_clearence)) {
            abort(404);
        }
        $this->taxTypes = TaxType::all();
        $this->registerWorkflow($modelName, $this->modelId);
    }

    public function approve($transition)
    {
        $transition = $transition['data']['transition'];

        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);

        if ($this->checkTransition('crdm_review')) {

//                fetch latest tax clearance certificate
            $last_sequence = Sequence::query()->select('next_id', 'id')->where('name', Sequence::TAX_CLEARANCE)->first();
            $last_year = Sequence::query()->select('next_id', 'id')->where('name', Sequence::TAX_CLEARANCE_YEAR)->first();
            DB::beginTransaction();
            try {
                $cert_no = date("Y").'00001';
                if ($last_sequence && $last_year){
                    $incrementedDigits = $last_sequence->next_id;
                    $year = $last_year->next_id;

                    $formattedDigits = str_pad($incrementedDigits, 5, '0', STR_PAD_LEFT);
//                    increment only if it's the same year
                    if (date('Y') == $year){
                        $cert_no = $year . $formattedDigits;
                    }else{
//                        update year
                        $last_year->next_id = date('Y');
                        if (!$last_year->save()){
                            DB::rollBack();
                            $this->customAlert('error', 'Could not generate certificate.');
                            return;
                        }
                    }

                    $affectedRows = DB::update('UPDATE sequences SET next_id = next_id + 1 WHERE name = ?', [Sequence::TAX_CLEARANCE]);
                    if ($affectedRows === 0){
                        DB::rollBack();
                        $this->customAlert('error', 'Could not generate certificate.');
                        return;
                    }

                }
                $this->subject->status = TaxClearanceStatus::APPROVED;
                $this->subject->certificate_number = $cert_no;
                $this->subject->save();

                $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
                DB::commit();

                $emailPayload = [
                    $this->tax_clearence->businessLocation,
                    $this->subject,
                ];
                event(new SendMail('tax-clearance-approved', $emailPayload));

                $smsPayload = [
                    $this->tax_clearence->businessLocation->taxpayer->mobile,
                    'Your approval for tax clearance certificate of your business ' . $this->tax_clearence->businessLocation->name . ' has been granted, please check your email or log in to ZIDRAS to obtain your online certificate copy.'
                ];
                event(new SendSms('tax-clearance-feedback-to-taxpayer', $smsPayload));

                $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
            } catch (Exception $e) {
                DB::rollBack();
                Log::error('Error: ' . $e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]);
                $this->customAlert('error', 'Something went wrong, please contact the administrator for help.');
                return;
            }
        }else if ($this->checkTransition('officer_review')) {
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        }else{
            $this->flash('success', 'You do not have permission to this action!', [], redirect()->back()->getTargetUrl());
        }
 	
    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];
        $this->validate([
            'comments' => 'required|strip_tag',
        ]);

        try {

            $this->doTransition($transition, ['status' => 'reject', 'comment' => $this->comments]);

            $mailPayload = [
                $this->tax_clearence->businessLocation->taxpayer->email,
                'Your approval for tax clearance certificate of your business ' . $this->tax_clearence->businessLocation->name . ' has been rejected, please pay off all debts to be clear for approval.'
            ];

            event(new SendMail('tax-clearance-rejected', $mailPayload));

            $smsPayload = [
                $this->tax_clearence->businessLocation->taxpayer->mobile,
                'Your approval for tax clearance certificate of your business ' . $this->tax_clearence->businessLocation->name . ' has been rejected, please pay off all debts to be clear for approval.'
            ];

            event(new SendSms('tax-clearance-feedback-to-taxpayer', $smsPayload));
        } catch (Exception $e) {
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help.');
            return;
        }
        $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
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

    public function render()
    {
        return view('livewire.approval.tax-clearence-approval-processing');
    }
}
