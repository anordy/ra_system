<?php

namespace App\Http\Livewire\DriversLicense;


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
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class CapturePassportModal extends Component
{

    use CustomAlert, WithFileUploads, WorkflowProcesssingTrait;

    public $application_id;
    public $photo;
    public $licenseId;
    private $photo_path = null;


    public function mount($application_id)
    {
        $this->application_id = $application_id;
    }

    protected function rules()
    {
        return [
            'photo' => 'required|mimes:png,jpg,jpeg|max:3072'
        ];
    }


    public function submit()
    {
        $dla = DlLicenseApplication::findOrFail($this->application_id);

        $this->validate();

        try {
            DB::transaction(function () use ($dla) {
                $this->updateDriverPhoto($dla);
                $this->generateLicense($dla);
                $dla->status = DlApplicationStatus::STATUS_LICENSE_PRINTING;
                $dla->save();
            });

            $this->flash('success', 'Photo Uploaded and License is created successful', [], route('drivers-license.licenses.show', encrypt($this->licenseId)));
        } catch (Exception $exception) {
            DB::rollBack();

            Log::error('Error creating driver license: ' . $exception->getMessage(), [
                'subject_id' => $dla->id,
                'exception' => $exception,
            ]);

            if (Storage::disk('local')->exists($this->photo_path)) {
                Storage::disk('local')->delete($this->photo_path);
            }
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help.');
        }
    }


    public function render()
    {
        return view('livewire.drivers-license.capture-passport-modal');
    }

    private function updateDriverPhoto(DlLicenseApplication $dla)
    {
        try {
            $photoPath = $this->photo->store('dl_passport');
            $dla->photo_path = $photoPath;
            $dla->save();
        } catch (Exception $exception) {
            Log::error('DRIVERS-LICENSE-CAPTURE-PASSPORT-MODAL-UPDATE-DRIVER-PHOTO', [$exception]);
            throw $exception;
        }
    }

    private function generateLicense(DlLicenseApplication $dla)
    {
        $dlLicense = $dla->drivers_license;

        $dlLicense->license_number = 'Z'. DlDriversLicense::getNextLicenseNumber();
        $dlLicense->issued_date = date('Y-m-d');
        $dlLicense->expiry_date = Carbon::now()->addYears($dla->license_duration->number_of_years)->format('Y-m-d');
        $dlLicense->status = DlApplicationStatus::ACTIVE;

        if (!$dlLicense->save()) {
            throw new Exception('Error saving drivers license');
        }

    }

}
