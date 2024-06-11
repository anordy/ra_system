<?php

namespace App\Http\Livewire\Approval\Mvr;

use App\Enum\BillStatus;
use App\Enum\GeneralConstant;
use App\Events\SendSms;
use App\Jobs\SendCustomSMS;
use App\Models\DlApplicationCertificate;
use App\Models\DlApplicationLicenseClass;
use App\Models\DlApplicationStatus;
use App\Models\DlDriversLicense;
use App\Models\DlFee;
use App\Models\DlLicenseRestriction;
use App\Models\DlRestriction;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use App\Traits\WorkflowProcesssingTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class DriverLicenseApprovalProcessing extends Component
{
    use CustomAlert, WorkflowProcesssingTrait, PaymentsTrait, WithFileUploads;

    public $modelId;
    public $modelName;
    public $comments;
    public $approvalReport;
    public $selectedRestrictions, $restrictions;
    public $attachments = [];

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->registerWorkflow($modelName, $this->modelId);
        $this->selectedRestrictions = [];
        $this->restrictions = DlRestriction::select('id', 'symbol', 'description')->get();
        $this->attachments = [
            [
                'file' => '',
            ],
        ];
    }


    public function addAttachment()
    {
        $this->attachments[] = [
            'file' => '',
        ];
    }

    public function removeAttachment($i)
    {
        unset($this->attachments[$i]);
    }

    public function updatedSelectedRestrictions($value)
    {
        $this->selectedRestrictions[] = (int) $value;
        // Remove 0 and false values from the array
        $this->selectedRestrictions = array_filter($this->selectedRestrictions, function ($item) {
            return $item !== 0 && $item !== false;
        });
        $this->selectedRestrictions = array_unique($this->selectedRestrictions);
    }

    public function approve($transition)
    {
        $transition = $transition['data']['transition'];

        try {
            DB::beginTransaction();

            if ($this->checkTransition('transport_officer_review') && $transition === 'transport_officer_review') {
                $this->validate([
                    'comments' => 'required|strip_tag',
                    'attachments.*.file' => 'required|mimes:pdf|max:1024|max_file_name_length:100|valid_pdf',
                ]);

                DlLicenseRestriction::where('dl_license_application_id', $this->subject->id)->delete();
                foreach ($this->selectedRestrictions as $_id => $_value) {
                    if (!empty($_value)) {
                        $class = DlLicenseRestriction::query()->create([
                            'dl_license_application_id' => $this->subject->id,
                            'dl_restriction_id' => $_id
                        ]);

                        if (!$class) {
                            throw new \Exception('Could not persist driving license restriction information');
                        }
                    }
                }

                $this->subject->status = DlApplicationStatus::STATUS_PENDING_PAYMENT;

                foreach ($this->attachments as $attachment) {
                    DlApplicationCertificate::create([
                        'location' => $attachment['file']->store('dl_files'),
                        'dl_license_application_id' => $this->subject->id
                    ]);
                }

                $this->subject->payment_status = BillStatus::CN_GENERATING;
                $this->subject->save();
            } else {
                $this->validate([
                    'comments' => 'required|strip_tag',
                ]);

                $dlAppLicenseClass = DlApplicationLicenseClass::where('dl_license_application_id', $this->subject->id)->count();
                $lastDlLicense = DlDriversLicense::where('dl_drivers_license_owner_id', $this->subject->dl_drivers_license_owner_id)->latest()->first();
                $lastDlLicenseClass = $lastDlLicense->drivers_license_classes->count();

                if ($lastDlLicenseClass < $dlAppLicenseClass) {
                    $lastDlLicense->status = GeneralConstant::ADD_CLASS;
                    $lastDlLicense->save();
                }
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

            DB::commit();

            if ($this->subject->status = DlApplicationStatus::STATUS_PENDING_PAYMENT && $transition === 'transport_officer_review') {
                event(new SendSms(SendCustomSMS::SERVICE, NULL, [
                    'phone' => $this->subject->drivers_license_owner->mobile,
                    'message' => "
                Hello {$this->subject->drivers_license_owner->fullname()}, your driver license application has been approved, you will receive your payment control number shortly."
                ]));
            }

            // Generate Control Number after MVR SC Approval
            if ($this->subject->status == DlApplicationStatus::STATUS_PENDING_PAYMENT && $transition === 'transport_officer_review') {
                $this->generateControlNumber();
            }

            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $exception) {
            DB::rollBack();

            Log::error('Error approving application: ' . $exception->getMessage(), [
                'subject_id' => $this->subject->id,
                'applicant name' => $this->subject->drivers_license_owner->fullname(),
                'exception' => $exception,
            ]);
            $this->customAlert('error', 'Something went wrong');
            return;
        }
    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];

        $this->validate([
            'comments' => 'required|strip_tag',
        ]);

        try {
            DB::beginTransaction();

            if ($this->checkTransition('application_filled_incorrect')) {
                $this->subject->status = DlApplicationStatus::CORRECTION;
                $this->subject->save();
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

            DB::commit();

            if ($this->subject->status = DlApplicationStatus::CORRECTION) {
                // Send correction email/sms
                event(new SendSms(SendCustomSMS::SERVICE, NULL, [
                    'phone' => $this->subject->drivers_license_owner->mobile,
                    'message' => "
                Hello {$this->subject->drivers_license_owner->fullname()}, your driver license application requires correction, please vist your driving school/login to the system to perform data update."
                ]));
            }

            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error rejecting application: ' . $exception->getMessage(), [
                'subject_id' => $this->subject->id,
                'applicant name' => $this->subject->drivers_license_owner->fullname(),
                'exception' => $exception,
            ]);
            $this->customAlert('error', 'Something went wrong');
        }

    }

    protected $listeners = [
        'approve',
        'reject'
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

    public function generateControlNumber()
    {
        try {
            // Fetch the fee
            $fee = DlFee::query()
                ->where('dl_license_duration_id', $this->subject->license_duration_id)
                ->where('type', $this->subject->type)
                ->first();

            if (empty ($fee)) {
                $errorMessage = "Driver License fee for this application is not configured";
                $this->customAlert('error', $errorMessage);
                return;
            } else {
                // Generate control number
                $this->generateDLicenseControlNumber($this->subject, $fee);

            }
        } catch (Exception $e) {

            Log::error('Error generating control number: ' . $e->getMessage(), [
                'subject_id' => $this->subject->id,
                'license_duration_id' => $this->subject->license_duration_id,
                'exception' => $e,
            ]);

            $this->customAlert('error', 'Failed to generate control number, please try again');
        }
    }

    public function render()
    {
        return view('livewire.approval.mvr.driver-license');
    }
}
