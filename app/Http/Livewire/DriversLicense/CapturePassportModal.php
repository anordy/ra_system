<?php

namespace App\Http\Livewire\DriversLicense;


use App\Enum\MvrRegistrationStatus;
use App\Models\DlApplicationStatus;
use App\Models\DlDriversLicense;
use App\Models\DlDriversLicenseClass;
use App\Models\DlDriversLicenseOwner;
use App\Models\DlLicenseApplication;
use App\Traits\WorkflowProcesssingTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Traits\CustomAlert;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class CapturePassportModal extends Component
{

    use CustomAlert, WithFileUploads, WorkflowProcesssingTrait;


    public string $application_id;
    /**
     * @var  TemporaryUploadedFile
     */
    public $photo;
    public $licenseId;
    private ?string $photo_path = null;


    public function mount($application_id)
    {
        $this->application_id = $application_id;
    }

    protected function rules()
    {
        return [
            'photo' => 'required|mimes:png,jpg,jpeg|max:1024'
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
        $photoPath = $this->photo->storeAs('dl_passport', "dl-passport-{$this->application_id}-" . date('YmdHis') . '-' . random_int(10000, 99999) . '.' . $this->photo->extension());
        $dla->drivers_license_owner->photo_path = $photoPath;
        $dla->drivers_license_owner->save();

    }

    private function generateLicense(DlLicenseApplication $dla)
    {
        $owner = $dla->drivers_license_owner;

        $originalLicense = DlDriversLicense::query()
            ->where('dl_drivers_license_owner_id', $dla->dl_drivers_license_owner_id)
            ->latest()
            ->first();

        if ($originalLicense) {

            $newLicense = clone $originalLicense;
            $newLicense->status = DlApplicationStatus::ACTIVE;

            if ($dla->type === DlApplicationStatus::RENEW) {
                $newLicense->license_duration = $dla->license_duration;
                $newLicense->issued_date = date('Y-m-d');
                $newLicense->expiry_date = date('Y-m-d', strtotime("+{$dla->license_duration} years"));
            }

            // Delete existing license class associations
            $newLicense->drivers_license_classes()->delete();

            $newLicense->save();

        } else {
            $newLicense = DlDriversLicense::create([
                'dl_drivers_license_owner_id' => $owner->id,
                'taxpayer_id' => $owner->taxpayer_id,
                'license_number' => DlDriversLicense::getNextLicenseNumber(),
                // 'dl_license_duration_id' =>(int) $dla->license_duration_id, 
                'license_duration' => $dla->license_duration,
                'issued_date' => date('Y-m-d'),
                'expiry_date' => date('Y-m-d', strtotime("+{$dla->license_duration} years")),
                'license_restrictions' => $dla->license_restrictions ?? 'none',
                'dl_license_application_id' => $dla->id
            ]);
        }


        // Associate License Classes with the new License
        foreach ($dla->application_license_classes()->get() as $class) {
            DlDriversLicenseClass::query()->create([
                'dl_drivers_license_id' => $newLicense->id,
                'dl_license_class_id' => $class->dl_license_class_id
            ]);
        }

        // dd($newLicense->drivers_license_classes);

        $this->licenseId = $newLicense->id;

        return $newLicense;
    }

}
