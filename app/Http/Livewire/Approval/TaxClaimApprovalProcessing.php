<?php

namespace App\Http\Livewire\Approval;

use App\Enum\TaxClaimStatus;
use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\Claims\TaxClaim;
use App\Models\Claims\TaxClaimAssessment;
use App\Models\Claims\TaxClaimOfficer;
use App\Models\Claims\TaxCredit;
use App\Models\Taxpayer;
use App\Notifications\DatabaseNotification;
use Exception;
use App\Models\Role;
use App\Models\User;
use App\Models\TaxType;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\NotIn;
use App\Traits\WorkflowProcesssingTrait;
use Illuminate\Support\Facades\Auth;
use App\Traits\CustomAlert;

class TaxClaimApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, CustomAlert, WithFileUploads;

    public $modelId;
    public $modelName;
    public $comments;

    public $teamLeader;
    public $teamMember;

    public $paymentType;
    public $installmentCount;
    public $assessmentReport;
    public $taxTypes;

    public $staffs = [];
    public $subRoles = [];

    public $task;
    public $installmentAmount;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId   = decrypt($modelId);
        $this->taxTypes = TaxType::all();

        $this->registerWorkflow($modelName, $this->modelId);

        $this->task = $this->subject->pinstancesActive;

        if ($this->task != null) {
            $operators = json_decode($this->task->operators);
            if (gettype($operators) != "array") {
                $operators = [];
            }
            $roles = Role::whereIn('id', $operators)->get()->pluck('id')->toArray();

            $this->subRoles = Role::whereIn('report_to', $roles)->get();

            $this->staffs = User::whereIn('role_id', $this->subRoles->pluck('id')->toArray())->get();
        }
    }

    public function updated($propertyName){
        if ($propertyName == 'paymentType') {
            $this->installmentCount = 1;
        }
    }

    public function calcMoney()
    {
       if ($this->installmentCount > 0) {
            try {
                return $this->subject->amount / (int)$this->installmentCount;
            } catch (Exception $exception) {
                Log::error($exception .', '. Auth::user());
                return $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            }
       }
        
    }

    public function approve($transition)
    {
        $transition = $transition['data']['transition'];
        $taxType = $this->subject->taxType;

        $operators = [];
        if ($this->checkTransition('assign_officers')) {
            $this->validate(
                [
                    'teamLeader' => ['required',  new NotIn([$this->teamMember])],
                    'teamMember' => ['required',  new NotIn([$this->teamLeader])],
                ],
                [
                    'teamLeader.not_in' => 'Duplicate  already exists as team member',
                    'teamMember.not_in' => 'Duplicate already exists as team leader'
                ]
            );

            $officers = $this->subject->officers()->exists();

            if ($officers) {
                $this->subject->officers()->delete();
            }

            TaxClaimOfficer::create([
                'claim_id' => $this->subject->id,
                'user_id' => $this->teamLeader,
                'team_leader' => true,
            ]);

            TaxClaimOfficer::create([
                'claim_id' => $this->subject->id,
                'user_id' => $this->teamMember,
            ]);

            $operators = [intval($this->teamLeader), intval($this->teamMember)];
        }

        if ($this->checkTransition('verification_results')) {
            $this->validate(
                [
                    'assessmentReport' => 'required|mimes:pdf|max:1024',
                ]
            );

            DB::beginTransaction();

            try {
                $reportPath = $this->assessmentReport->store('tax-claims', 'local');

                $assessment = TaxClaimAssessment::create([
                    'claim_id' => $this->subject->id,
                    'report_path' => $reportPath,
                ]);

                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                Log::error($e);
                $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            }
        }

        if ($this->checkTransition('method_of_payment')) {
            $this->validate([
                'paymentType' => 'required',
                'installmentCount' => 'required_if:paymentType,installment|exclude_if:paymentType,full|exclude_if:paymentType,cash|numeric|max:12'
            ]);

            TaxCredit::create([
                'business_id' => $this->subject->business_id,
                'location_id' => $this->subject->location_id,
                'tax_type_id' => $this->subject->tax_type_id,
                'claim_id' => $this->subject->id,
                'payment_method' => $this->paymentType,
                'amount' => $this->subject->amount,
                'currency' => $this->subject->currency,
                'installments_count' => $this->paymentType == 'installment' ? $this->installmentCount : null,
                'status' => 'draft'
            ]);

            $this->subject->status = TaxClaimStatus::APPROVED;
            $this->subject->approved_on = now();
            $this->subject->save();
        }

        if ($this->checkTransition('accepted')) {
            $this->subject->status = TaxClaimStatus::APPROVED;
            $this->subject->save();
            $credit = TaxCredit::where('claim_id', $this->subject->id)->firstOrFail();
            $credit->status = TaxClaimStatus::APPROVED;
            $credit->save();

            $claim = TaxClaim::query()->findOrFail($this->subject->id);
            if(is_null($claim)){
                abort(404);
            }
            $taxpayer = $claim->taxpayer;
            
            $taxpayer->notify(new DatabaseNotification(
                $subject = 'TAX CLAIM APPROVAL',
                $message = 'Your tax claim for the return month of '.$claim->financialMonth->name.' '.$claim->financialMonth->year->code.' has been successfully approved',
                $href = 'claims.show',
                $hrefText = 'View',
                $hrefParameters = $this->subject->id,
            ));

            $emailPayload = [
                'email' => $taxpayer->email,
                'taxpayerName' => $taxpayer->first_name,
                'message' => 'Your tax claim for the return month of '.$claim->financialMonth->name.' '.$claim->financialMonth->year->code.' has been successfully approved'
            ];

            event(new SendMail('tax-claim-feedback', $emailPayload));

            $smsPayload = [
                'phone' => $taxpayer->phone,
                'message' => 'Hello '.$taxpayer->first_name.', Your tax claim for the return month of '.$claim->financialMonth->name.' '.$claim->financialMonth->year->code.' has been successfully approved'
            ];

            event(new SendSms('tax-claim-feedback', $smsPayload));
        }

        try {
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments, 'operators' => $operators]);
        } catch (Exception $e) {
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact support for assistance.');
            return;
        }

        $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];
        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);

        if ($this->checkTransition('rejected')) {
            $this->subject->status = TaxClaimStatus::REJECTED;
            $this->subject->save();
            $credit = TaxCredit::where('claim_id', $this->subject->id)->first();
            $credit->status = TaxClaimStatus::REJECTED;
            $credit->save();
        }

        try {
            $this->doTransition($transition, ['status' => 'reject', 'comment' => $this->comments]);
        } catch (Exception $e) {
            Log::error($e);
            return;
        }

        $claim = TaxClaim::query()->findOrFail($this->subject->id);
        if(is_null($claim)){
            abort(404);
        }
        $taxpayer = $claim->taxpayer;

        $emailPayload = [
            'email' => $taxpayer->email,
            'taxpayerName' => $taxpayer->first_name,
            'message' => 'Your tax claim for the return month of '.$claim->financialMonth->name.' '.$claim->financialMonth->year->code.' has been rejected.'
        ];

        event(new SendMail('tax-claim-feedback', $emailPayload));

        $smsPayload = [
            'phone' => $taxpayer->phone,
            'message' => 'Hello '.$taxpayer->first_name.', Your tax claim for the return month of '.$claim->financialMonth->name.' '.$claim->financialMonth->year->code.' has been rejected.'
        ];

        event(new SendSms('tax-claim-feedback', $smsPayload));

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
        return view('livewire.approval.tax_claim');
    }
}
