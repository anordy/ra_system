<?php

namespace App\Http\Livewire\Business\Updates;

use Exception;
use Carbon\Carbon;
use App\Events\SendSms;
use Livewire\Component;
use App\Events\SendMail;
use App\Models\Business;
use App\Models\TaxAgent;
use App\Models\BusinessStatus;
use App\Models\BusinessLocation;
use App\Models\BusinessConsultant;
use App\Traits\WorkflowProcesssingTrait;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;

class ChangesApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, CustomAlert;
    public $modelId;
    public $modelName;
    public $comments;
    public $business_update_data;
    public $business_id;
    public $business;
    public $consultant;


    public function mount($modelName, $modelId, $businessUpdate)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->business_update_data = $businessUpdate;
        $this->business_id = $businessUpdate->business_id;
        $this->registerWorkflow($modelName, $this->modelId);
    }


    public function approve($transition)
    {
        $transition = $transition['data']['transition'];
        try {
            if ($this->checkTransition('registration_manager_review')) {

                if ($this->business_update_data->type == 'business_information') {
                    $new_values = json_decode($this->business_update_data->new_values, true);

                    $business_information_data = $new_values['business_information'];
                    $business_location_data = $new_values['business_location'];

                    /** Update business information */
                    $business = Business::findOrFail($this->business_id);
                    $business->update($business_information_data);

                    /** Update business location */
                    $business_location = BusinessLocation::where('business_id', $this->business_id)->where('is_headquarter', true)->firstOrFail();
                    $business_location->update($business_location_data);

                    $this->subject->status = BusinessStatus::APPROVED;

                    $notification_payload = [
                        'business' => $business,
                        'time' => Carbon::now()->format('d-m-Y')
                    ];

                    event(new SendMail('change-business-information-approval', $notification_payload));
                    event(new SendSms('change-business-information-approval', $notification_payload));
                } else if ($this->business_update_data->type == 'responsible_person') {
                    /** Update business information */
                    $new_values = json_decode($this->business_update_data->new_values, true);
                    $business = Business::findOrFail($this->business_id);

                    // Get current business consultant
                    $current_business_consultant = BusinessConsultant::where('business_id', $this->business_id)->latest()->get()->first() ?? null;

                    // If I am not consultant of my business add new consultant
                    if ($new_values['is_own_consultant'] == 0) {
                        // If consultant exists mark current consultant as removed and add new consultant
                        if ($current_business_consultant) {
                            $current_business_consultant->update(['status' => 'removed', 'removed_at' => Carbon::now()]);

                            $this->consultant = BusinessConsultant::create([
                                'business_id' => $business->id,
                                'contract' => $this->business_update_data->agent_contract ?? null,
                                'taxpayer_id' => TaxAgent::where('reference_no', $new_values['tax_consultant_reference_no'])->firstOrFail()->taxpayer_id
                            ]);
                        // If consultant does not exist add new consultant
                        } else {
                            $this->consultant = BusinessConsultant::create([
                                'business_id' => $business->id,
                                'contract' => $this->business_update_data->agent_contract ?? null,
                                'taxpayer_id' => TaxAgent::where('reference_no', $new_values['tax_consultant_reference_no'])->firstOrFail()->taxpayer_id
                            ]);
                        }
                    // If I am removing a consultant from my business ie. remove consultant from business
                    } else {
                        if ($current_business_consultant) {
                            $current_business_consultant->update(['status' => 'removed', 'removed_at' => Carbon::now()]);
                        }
                    }

                    $business->update([
                        'is_own_consultant' => $new_values['is_own_consultant'],
                        'responsible_person_id' => $new_values['responsible_person_id']
                    ]);

                    $this->subject->status = BusinessStatus::APPROVED;

                    $notification_payload = [
                        'business' => $business,
                        'time' => Carbon::now()->format('d-m-Y')
                    ];

                    event(new SendMail('change-business-information-approval', $notification_payload));
                    event(new SendSms('change-business-information-approval', $notification_payload));

                    if ($this->consultant) {
                        $consultant_info = [
                            'business' => $business,
                            'consultant' => $this->consultant,
                            'time' => Carbon::now()->format('d-m-Y')
                        ];
                        event(new SendMail('change-business-consultant-information-approval', $consultant_info));
                        event(new SendSms('change-business-consultant-information-approval', $consultant_info));
                    }
                }
            }
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];
        $this->validate(['comments' => 'required|strip_tag']);
        $business = Business::findOrFail($this->business_id);

        try {
            if ($this->checkTransition('registration_manager_reject')) {
                $this->subject->status = BusinessStatus::REJECTED;
                $notification_payload = [
                    'business' => $business,
                    'time' => Carbon::now()->format('d-m-Y')
                ];
                // event(new SendMail('change-business-information-rejected', $notification_payload));
                // event(new SendSms('change-business-information-rejected', $notification_payload));
            } else if ($this->checkTransition('application_filled_incorrect')) {
                $this->subject->status = BusinessStatus::CORRECTION;
                $notification_payload = [
                    'business' => $business,
                    'time' => Carbon::now()->format('d-m-Y')
                ];
                event(new SendMail('change-business-information-correction', $notification_payload));
                event(new SendSms('change-business-information-correction', $notification_payload));
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
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
        return view('livewire.approval.changes');
    }
}
