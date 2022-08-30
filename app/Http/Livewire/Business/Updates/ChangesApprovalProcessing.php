<?php

namespace App\Http\Livewire\Business\Updates;

use Exception;
use Carbon\Carbon;
use App\Events\SendSms;
use Livewire\Component;
use App\Events\SendMail;
use App\Models\Business;
use App\Models\TaxAgent;
use App\Models\BusinessBank;
use App\Models\BusinessStatus;
use App\Models\BusinessLocation;
use App\Models\BusinessConsultant;
use Illuminate\Support\Facades\DB;
use App\Traits\WorkflowProcesssingTrait;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ChangesApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, LivewireAlert;
    public $modelId;
    public $modelName;
    public $comments;
    public $business_update_data;
    public $business_id;


    public function mount($modelName, $modelId, $businessUpdate)
    {
        $this->modelName = $modelName;
        $this->modelId = $modelId;
        $this->business_update_data = $businessUpdate;
        $this->business_id = $businessUpdate->business_id;
        $this->registerWorkflow($modelName, $modelId);
    }


    public function approve($transtion)
    {
        $this->validate(['comments' => 'required']);
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

                    event(new SendMail('change-business-information', $notification_payload));
                    event(new SendSms('change-business-information', $notification_payload));
                } else if ($this->business_update_data->type == 'responsible_person') {
                    /** Update business information */
                    $new_values = json_decode($this->business_update_data->new_values, true);
                    $business = Business::findOrFail($this->business_id);
                    $current_business_consultant = BusinessConsultant::where('business_id', $this->business_id)->latest()->get()->first();

                    if ($new_values['is_own_consultant'] == 0) {
                        if ($current_business_consultant) {
                            $current_business_consultant->update(['status' => 'removed', 'removed_at' => Carbon::now()]);

                            $consultant = BusinessConsultant::create([
                                'business_id' => $business->id,
                                'contract' => $this->business_update_data->agent_contract ?? null,
                                'taxpayer_id' => TaxAgent::where('reference_no', $new_values['tax_consultant_reference_no'])->first()->taxpayer_id
                            ]);
                        } else {
                            $consultant = BusinessConsultant::create([
                                'business_id' => $business->id,
                                'contract' => $this->business_update_data->agent_contract ?? null,
                                'taxpayer_id' => TaxAgent::where('reference_no', $new_values['tax_consultant_reference_no'])->first()->taxpayer_id
                            ]);
                        }
                    } else {
                        if ($current_business_consultant) {
                            $current_business_consultant->update(['status' => 'removed', 'removed_at' => Carbon::now()]);
                        }
                    }

                    $business->update([
                        'is_own_consultant' => $new_values['is_own_consultant'],
                    ]);

                    $this->subject->status = BusinessStatus::APPROVED;

                    $notification_payload = [
                        'business' => $business,
                        'time' => Carbon::now()->format('d-m-Y')
                    ];

                    event(new SendMail('change-business-information', $notification_payload));
                    event(new SendSms('change-business-information', $notification_payload));
                }
            }
            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments]);
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function reject($transtion)
    {
        $this->validate(['comments' => 'required']);
        try {
            if ($this->checkTransition('registration_manager_reject')) {
                $this->subject->status = BusinessStatus::REJECTED;
            } else if ($this->checkTransition('application_filled_incorrect')) {
                $this->subject->status = BusinessStatus::CORRECTION;
            }

            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments]);
            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }


    public function render()
    {
        return view('livewire.approval.changes');
    }
}
