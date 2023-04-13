<?php

namespace App\Http\Livewire\Approval;

use App\Enum\VettingStatus;
use App\Models\Returns\Vat\VatReturn;
use App\Models\Role;
use App\Models\Taxpayer;
use App\Models\User;
use App\Notifications\DatabaseNotification;
use App\Traits\PaymentsTrait;
use App\Traits\TaxClaimsTrait;
use App\Traits\WorkflowProcesssingTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use App\Traits\TaxReturnHistory;
use App\Traits\TaxVerificationTrait;
use Livewire\Component;

class TaxReturnsVettingApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, CustomAlert, PaymentsTrait, TaxVerificationTrait, TaxReturnHistory, TaxClaimsTrait;

    public $modelId;
    public $modelName;
    public $comments;

    public $return;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->return = $modelName::findOrFail($this->modelId);

        $this->registerWorkflow($modelName, $this->modelId);
    }


    public function approve($transition)
    {
        $transition = $transition['data']['transition'];

        if ($this->checkTransition('return_vetting_officer_review')) {
            $this->validate(
                [
                    'comments' => ['nullable'],
                ],
            );

            Db::beginTransaction();
            try {
                $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
                $this->return->vetting_status = VettingStatus::VETTED;
                $this->return->return->vetting_status = VettingStatus::VETTED;
                $this->return->save();
                $this->return->return->save();


                // Trigger verification
                $this->triggerTaxVerifications($this->return->return, auth()->user());

                DB::commit();

                // Generate control number
                $this->generateReturnControlNumber($this->return);

                // TODO: Trigger claim for VAT
                //triggering claim
                if ($this->return->return_type == VatReturn::class) {
                    if ($this->return->total_amount_due < 0) {
                        $claim = $this->triggerClaim(abs($this->return->total_amount_due), $this->return->currency, $this->return);

                        $this->return->claim_status = 'claim';
                        $this->return->save();

                        $taxpayer = Taxpayer::query()->where('id', $this->return->filed_by_id)->first();
                        $taxpayer = implode(" ", array($taxpayer->first_name, $taxpayer->last_name));
                        $role = Role::query()->where('name', 'Administrator')->first();
                        $admins = User::query()->where('role_id', $role->id)->get();
                        foreach ($admins as $admin) {
                            $admin->notify(new DatabaseNotification(
                                $subject = 'TAX CLAIMING',
                                $message = 'You have a new request for tax claim from ' . $taxpayer . '',
                                $href = 'claims.show',
                                $hrefText = 'View',
                                $hrefParameters = $claim->id,
                            ));
                        }
                    }
                }

                $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
            } catch (Exception $e) {
                DB::rollBack();
                Log::error($e);
                $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            }
        }
    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];
        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);

        if ($this->checkTransition('application_filled_incorrect')) {

            DB::beginTransaction();
            try {
                $this->subject->vetting_status = VettingStatus::CORRECTION;
                $this->subject->return->vetting_status = VettingStatus::CORRECTION;
                $this->subject->return->save();


                $this->saveHistory($this->subject);
                $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

                DB::commit();

                $this->flash('success', 'Application sent for correction', [], redirect()->back()->getTargetUrl());
            } catch (Exception $e) {
                DB::rollBack();
                Log::error($e);
                $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            }
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

    public function render()
    {
        return view('livewire.approval.tax_returns_vetting');
    }
}
