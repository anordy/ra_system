<?php

namespace App\Http\Livewire\DriversLicense;


use App\Enum\AlertType;
use App\Enum\CustomMessage;
use App\Enum\DlFeeType;
use App\Enum\GeneralConstant;
use App\Models\DlApplicationStatus;
use App\Models\DlDriversLicense;
use App\Models\DlLicenseApplication;
use App\Traits\CustomAlert;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class CapturePassportModal extends Component
{
    use CustomAlert, WithFileUploads, WorkflowProcesssingTrait;

    public $applicationId;
    public $photo;
    public $licenseId;
    private $photoPath = null;


    public function mount($applicationId)
    {
        $this->applicationId = $applicationId;
    }

    protected function rules()
    {
        return [
            'photo' => 'required|mimes:png,jpg,jpeg|max:3072'
        ];
    }


    public function submit()
    {
        $this->validate();

        try {
            $dlApplication = DlLicenseApplication::findOrFail($this->applicationId);

            if ($dlApplication->photo_path) {
                $this->updateDriverPhoto($dlApplication);
                $this->flash(AlertType::SUCCESS, 'Photo re-uploaded successfully', [], redirect()->back()->getTargetUrl());
            } else {
                DB::transaction(function () use ($dlApplication) {
                    $this->updateDriverPhoto($dlApplication);

                    if ($dlApplication->type != DlFeeType::RENEW) {
                        $this->generateLicense($dlApplication);
                    } else {
                        $this->generateLicense($dlApplication, false);
                    }
                    $dlApplication->status = DlApplicationStatus::STATUS_LICENSE_PRINTING;
                    if (!$dlApplication->save()) throw new Exception('Failed to save drivers license application');
                });
                $this->flash(AlertType::SUCCESS, 'Photo Uploaded and License has been created successfully', [], redirect()->back()->getTargetUrl());
            }

        } catch (Exception $exception) {
            DB::rollBack();
            Log::error('DRIVER-LICENSE-CAPTURE-PASSPORT', [$exception]);
            if (Storage::disk('local')->exists($this->photoPath)) {
                Storage::disk('local')->delete($this->photoPath);
            }
            $this->customAlert(AlertType::ERROR, CustomMessage::ERROR);
        }
    }


    public function render()
    {
        return view('livewire.drivers-license.capture-passport-modal');
    }

    private function updateDriverPhoto(DlLicenseApplication $dla)
    {
        $photoPath = $this->photo->store('dl_passport');

        if (!$photoPath) {
            $this->customAlert(GeneralConstant::WARNING, 'Failed to upload photo');
            return;
        }
        $dla->photo_path = $photoPath;

        if (!$dla->save()) throw new Exception('Failed to save drivers license application');

    }

    private function generateLicense(DlLicenseApplication $dla, $generateNumber = true)
    {
        $dlLicense = $dla->drivers_license;

        if ($generateNumber) {
            $dlLicense->license_number = DlDriversLicense::getNextLicenseNumber();
        }

        $dlLicense->issued_date = date('Y-m-d');
        $dlLicense->expiry_date = Carbon::now()->addYears($dla->license_duration->number_of_years)->format('Y-m-d');
        $dlLicense->status = DlApplicationStatus::ACTIVE;

        if (!$dlLicense->save()) {
            throw new Exception('Error saving drivers license');
        }

    }

}
