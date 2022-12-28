<?php

namespace App\Http\Livewire\Approval;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\BusinessStatus;
use App\Models\LumpSumPayment;
use App\Models\TaxRegion;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class BranchesApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, LivewireAlert;
    public $modelId;
    public $modelName;
    public $comments;
    public $taxRegions;
    public $selectedTaxRegion;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId); // todo: encrypt id
        $this->registerWorkflow($modelName, $this->modelId);
        $this->taxRegions = TaxRegion::all();
    }

    public function approve($transition)
    {
        $transition = $transition['data']['transition'];

        if ($this->checkTransition('registration_officer_review')) {
            $this->validate(['selectedTaxRegion' => 'required']);
            $this->subject->tax_region_id = $this->selectedTaxRegion;
            $this->subject->vrn = $this->subject->business->vrn;
            $this->subject->save();
        }

        if ($this->checkTransition('director_of_trai_review')) {
            if (!$this->subject->generateZ()) {
                $this->alert('error', 'Something went wrong, please contact the administrator for help.');
                return;
            }
            
            $lump_sum_payemnt = LumpSumPayment::where('business_id', $this->subject->business_id)->first() ?? null;

            if ($lump_sum_payemnt != null) {
                LumpSumPayment::create([
                    'filed_by_id'         => auth()->user()->id,
                    'business_id'         => $this->subject->business_id,
                    'business_location_id' => $this->subject->id,
                    'annual_estimate'     => $lump_sum_payemnt->annual_estimate,
                    'payment_quarters'    => $lump_sum_payemnt->payment_quarters,
                    'currency'            => $lump_sum_payemnt->currency,
                ]);
            }

            $this->subject->verified_at = Carbon::now()->toDateTimeString();
            $this->subject->status = BusinessStatus::APPROVED;

            $notification_payload = [
                'branch' => $this->subject,
                'time' => Carbon::now()->format('d-m-Y')
            ];

            event(new SendSms('branch-approval', $notification_payload));
            event(new SendMail('branch-approval', $notification_payload));
        }

        try {
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
            
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];

        $this->validate(['comments' => 'required']);

        $notification_payload = [
            'branch' => $this->subject,
            'time' => Carbon::now()->format('d-m-Y')
        ];

        try {
            if ($this->checkTransition('application_filled_incorrect')) {
                $this->subject->status = BusinessStatus::CORRECTION;

                $notification_payload = [
                    'branch' => $this->subject,
                    'time' => Carbon::now()->format('d-m-Y')
                ];

                event(new SendSms('branch-correction', $notification_payload));
                event(new SendMail('branch-correction', $notification_payload));
            }
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    protected $listeners = [
        'approve', 'reject'
    ];

    public function confirmPopUpModal($action, $transition)
    {
        $this->alert('warning', 'Are you sure you want to complete this action?', [
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
        return view('livewire.approval.branches');
    }
}
