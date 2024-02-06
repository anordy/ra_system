<?php

namespace App\Http\Livewire\Business\Updates;

use Exception;
use Carbon\Carbon;
use App\Models\Bank;
use App\Models\Ward;
use App\Models\ISIC1;
use App\Models\ISIC2;
use App\Models\ISIC3;
use App\Models\ISIC4;
use App\Models\Region;
use App\Models\Street;
use App\Events\SendSms;
use Livewire\Component;
use App\Events\SendMail;
use App\Models\Business;
use App\Models\Currency;
use App\Models\District;
use App\Models\TaxAgent;
use App\Models\Taxpayer;
use App\Models\TaxRegion;
use App\Models\AccountType;
use App\Traits\CustomAlert;
use App\Models\BusinessBank;
use App\Models\BusinessFile;
use App\Enum\AssistantStatus;
use App\Models\BusinessHotel;
use App\Models\BusinessStatus;
use App\Models\BusinessActivity;
use App\Models\BusinessLocation;
use App\Models\BusinessAssistant;
use App\Models\BusinessConsultant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\WorkflowProcesssingTrait;

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
    public $selectedTaxRegion;
    public $taxRegions;

    public $isiic_i;
    public $isiic_ii;
    public $isiic_iii;
    public $isiic_iv;

    public $isiiciList   = [];
    public $isiiciiList  = [];
    public $isiiciiiList = [];
    public $isiicivList  = [];

    public $old_values, $new_values;
    public $isBusinessActivityChanged = false;
    public $isLocationChanged = false;

    public function mount($modelName, $modelId, $businessUpdate)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->business_update_data = $businessUpdate;
        $this->business_id = $businessUpdate->business_id;
        $this->registerWorkflow($modelName, $this->modelId);

        $this->old_values = json_decode($this->business_update_data->old_values, TRUE);
        $this->new_values = json_decode($this->business_update_data->new_values, TRUE);


        if (array_key_exists('business_information', $this->old_values) && $this->old_values['business_information']['business_activities_type_id']['id'] != $this->new_values['business_information']['business_activities_type_id']) {
            $this->isBusinessActivityChanged = true;
            $this->isiiciList = ISIC1::all();

            $this->isiic_i = $this->business_update_data->business->isiic_i ?? null;

            if ($this->isiic_i) {
                $this->isiiciChange($this->isiic_i);
            }
            $this->isiic_ii = $this->business_update_data->business->isiic_ii ?? null;
            if ($this->isiic_ii) {
                $this->isiiciiChange($this->isiic_ii);
            }
            $this->isiic_iii = $this->business_update_data->business->isiic_iii ?? null;

            if ($this->isiic_iii) {
                $this->isiiciiiChange($this->isiic_iii);
            }

            $this->isiic_iv = $this->business_update_data->business->isiic_iv ?? null;
        }

        if (array_key_exists('business_location', $this->old_values) && $this->old_values['business_location']['region_id']['id'] != $this->new_values['business_location']['region_id']) {
            $this->isLocationChanged = true;
            $this->taxRegions = TaxRegion::all();
            $this->selectedTaxRegion = $this->business_update_data->business->headquarter->tax_region_id;
        }
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

                    DB::beginTransaction();
                    try {
                        if ($this->isLocationChanged) {
                            $business->headquarter->tax_region_id = $this->selectedTaxRegion;
                            $business->headquarter->save();
                        }

                        if ($this->isBusinessActivityChanged) {
                            $business->isiic_i = $this->isiic_i ?? null;
                            $business->isiic_ii = $this->isiic_ii ?? null;
                            $business->isiic_iii = $this->isiic_iii ?? null;
                            $business->isiic_iv = $this->isiic_iv ?? null;
                            $business->save();
                        }

                        $business->update($business_information_data);

                        /** Update business location */
                        $business_location = BusinessLocation::where('business_id', $this->business_id)->where('is_headquarter', true)->firstOrFail();
                        $business_location->update($business_location_data);

                        $this->subject->status = BusinessStatus::APPROVED;
                        DB::commit();
                    } catch (Exception $e) {
                        Log::error($e);
                        DB::rollBack();
                        $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
                    }

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

                    DB::beginTransaction();
                    try {
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
                        ]);

                        // Mark any active assistant as inactive.
                        $business->assistants()->active()->update(['status' => AssistantStatus::INACTIVE]);

                        if ($new_values['hasAssistants']) {
                            foreach ($new_values['assistants'] as $assistant) {
                                BusinessAssistant::create([
                                    'business_id' => $assistant['business_id'],
                                    'taxpayer_id' => $assistant['taxpayer_id'],
                                    'added_by_type' => $assistant['added_by_type'],
                                    'added_by_id' => $assistant['added_by_id'],
                                    'status' => $assistant['status'],
                                    'assigned_at' => $assistant['assigned_at']
                                ]);
                            }
                        }
                        DB::commit();
                    } catch (Exception $e) {
                        Log::error($e);
                        DB::rollBack();
                        $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
                    }

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
                } else if ($this->business_update_data->type == 'bank_information') {
                    $new_values = json_decode($this->business_update_data->new_values);

                    /** Update business information */
                    $business = Business::findOrFail($this->business_id);

                    DB::beginTransaction();
                    try {
                        $business->banks()->forceDelete();

                        foreach ($new_values as $bank) {
                            BusinessBank::create([
                                'business_id' => $business->id,
                                'bank_id' => $bank->bank_id,
                                'acc_no' => $bank->acc_no,
                                'account_type_id' => $bank->account_type_id,
                                'branch' => $bank->branch,
                                'currency_id' => $bank->currency_id,
                            ]);
                        }

                        $this->subject->status = BusinessStatus::APPROVED;
                        DB::commit();
                    } catch (Exception $e) {
                        Log::error($e);
                        DB::rollBack();
                        $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
                    }

                    $notification_payload = [
                        'business' => $business,
                        'time' => Carbon::now()->format('d-m-Y')
                    ];

                    event(new SendMail('change-business-information-approval', $notification_payload));
                    event(new SendSms('change-business-information-approval', $notification_payload));
                } else if ($this->business_update_data->type == 'business_attachments') {
                    $new_values = json_decode($this->business_update_data->new_values);

                    $business = Business::findOrFail($this->business_id);

                    DB::beginTransaction();
                    try {
                        $supporting_attachments = $new_values->supporting_attachments;

                        if (count($supporting_attachments) > 0) {
                            foreach ($supporting_attachments as $file) {
                                BusinessFile::updateOrCreate(
                                    [
                                        'business_file_type_id' => $file->business_file_type_id,
                                        'business_id' => $business->id,
                                    ],
                                    [
                                        'business_id' => $business->id,
                                        'taxpayer_id' => $file->taxpayer_id,
                                        'business_file_type_id' => $file->business_file_type_id,
                                        'location' => $file->location
                                    ]
                                );
                            }
                        }

                        $partners_tins = $new_values->partners_tins;

                        if (count($partners_tins) > 0) {
                            foreach ($partners_tins as $tin) {
                                $taxpayer = Taxpayer::findOrFail($tin->taxpayer_id);
                                $taxpayer->tin_location = $tin->tin_location;
                                $taxpayer->save();
                            }
                        }

                        $this->subject->status = BusinessStatus::APPROVED;
                        DB::commit();
                    } catch (Exception $e) {
                        Log::error($e);
                        DB::rollBack();
                        $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
                    }

                    $notification_payload = [
                        'business' => $business,
                        'time' => Carbon::now()->format('d-m-Y')
                    ];

                    event(new SendMail('change-business-information-approval', $notification_payload));
                    event(new SendSms('change-business-information-approval', $notification_payload));
                } else if ($this->business_update_data->type == 'hotel_information') {
                    $new_values = json_decode($this->business_update_data->new_values, true);

                    DB::beginTransaction();
                    try {
                        // Get the first Hotel of the business
                        $hotel = BusinessHotel::where('business_id', $this->business_update_data->business_id)->first();

                        $hotel->update($new_values);

                        $this->subject->status = BusinessStatus::APPROVED;
                        DB::commit();
                    } catch (Exception $e) {
                        Log::error($e);
                        DB::rollBack();
                        $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
                    }

                    $notification_payload = [
                        'business' => $hotel->business,
                        'time' => Carbon::now()->format('d-m-Y')
                    ];

                    event(new SendMail('change-business-information-approval', $notification_payload));
                    event(new SendSms('change-business-information-approval', $notification_payload));
                } else if ($this->business_update_data->type == 'transfer_ownership') {
                    $new_values = json_decode($this->business_update_data->new_values, true);

                    DB::beginTransaction();
                    try {
                        $business = Business::where('id', $this->business_update_data->business_id)->first();

                        if (!$business) {
                            $this->customAlert('warning', 'Business not found');
                            return;
                        }


                        $business->update(
                            [
                                'taxpayer_id' => $new_values['taxpayer_id'],
                                'responsible_person_id' => $new_values['responsible_person_id']
                            ]
                        );


                        $this->subject->status = BusinessStatus::APPROVED;
                        DB::commit();

                        $notification_payload = [
                            'business' => $business,
                            'time' => Carbon::now()->format('d-m-Y')
                        ];

                        event(new SendMail('change-business-information-approval', $notification_payload));
                        event(new SendSms('change-business-information-approval', $notification_payload));
                    } catch (Exception $e) {
                        Log::error($e);
                        DB::rollBack();
                        $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
                    }

                }
            }
        } catch (Exception $e) {
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            return;
        }

        try {
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

    public function isiiciChange($value)
    {
        $this->isiiciiList  = ISIC2::where('isic1_id', $value)->get();
        $this->isiic_ii     = null;
        $this->isiic_iii    = null;
        $this->isiic_iv     = null;
        $this->isiiciiiList = [];
        $this->isiicivList  = [];
    }

    public function isiiciiChange($value)
    {
        $this->isiiciiiList = ISIC3::where('isic2_id', $value)->get();
        $this->isiic_iii    = null;
        $this->isiic_iv     = null;
        $this->isiicivList  = [];
    }

    public function isiiciiiChange($value)
    {
        $this->isiicivList = ISIC4::where('isic3_id', $value)->get();
        $this->isiic_iv    = null;
    }

    public function getNameById($type, $id)
    {
        if ($type == 'business_activities_type_id') {
            return BusinessActivity::find($id)->name ?? 'N/A';
        } else if ($type == 'currency_id') {
            return Currency::find($id)->name ?? 'N/A';
        } else if ($type == 'region_id') {
            return Region::find($id)->name ?? 'N/A';
        } else if ($type == 'district_id') {
            return District::find($id)->name ?? 'N/A';
        } else if ($type == 'ward_id') {
            return Ward::find($id)->name ?? 'N/A';
        } else if ($type == 'bank_id') {
            return Bank::find($id)->name ?? 'N/A';
        } else if ($type == 'account_type_id') {
            return AccountType::find($id)->name ?? 'N/A';
        } else if ($type == 'street_id') {
            return Street::find($id)->name ?? 'N/A';
        }
    }

    public function render()
    {
        return view('livewire.approval.changes');
    }
}
