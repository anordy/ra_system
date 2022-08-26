<?php

namespace App\Http\Livewire\DriversLicense;


use App\Models\Country;
use App\Models\DlApplicationLicenseClass;
use App\Models\DlApplicationStatus;
use App\Models\DlDriversLicense;
use App\Models\DlDriversLicenseClass;
use App\Models\DlDriversLicenseOwner;
use App\Models\DlLicenseApplication;
use App\Models\MvrBodyType;
use App\Models\MvrClass;
use App\Models\MvrColor;
use App\Models\MvrFuelType;
use App\Models\MvrMake;
use App\Models\MvrModel;
use App\Models\MvrMotorVehicle;
use App\Models\MvrMotorVehicleOwner;
use App\Models\MvrOwnershipStatus;
use App\Models\MvrOwnershipTransfer;
use App\Models\MvrOwnershipTransferReason;
use App\Models\MvrRegistrationStatus;
use App\Models\MvrRequestStatus;
use App\Models\MvrTransmissionType;
use App\Models\MvrVehicleStatus;
use App\Models\Taxpayer;
use App\Services\TRA\ServiceRequest;
use App\Traits\WorkflowProcesssingTrait;
use App\Traits\WorkflowTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class CapturePassportModal extends Component
{

    use LivewireAlert,WithFileUploads,WorkflowProcesssingTrait;


    public string $application_id;
    /**
     * @var  TemporaryUploadedFile
     */
    public $photo;
    private ?string $photo_path = null;


    public function mount($application_id)
    {
        $this->application_id = $application_id;
    }

    protected function rules()
    {
        return [
            'photo'=>'required|mimes:png,jpg'
        ];
    }


    public function submit()
    {
        $this->validate();
        try{
            DB::beginTransaction();
            $this->photo_path = $this->photo->storePubliclyAs('DL', "DL-Passport-{$this->application_id}-".date('YmdHis').'-'.random_int(10000,99999).'.'.$this->photo->extension());
            $dla =  DlLicenseApplication::query()->find($this->application_id);
            $dla->update([
                'photo_path'=>$this->photo_path,
                'dl_application_status_id'=>DlApplicationStatus::query()->firstOrCreate(['name'=>DlApplicationStatus::STATUS_LICENSE_PRINTING])->id,
            ]);
            $this->generateLicense($dla);
            DB::commit();
            $this->flash('success', 'Photo Uploaded', [], route('drivers-license.applications.show',encrypt($this->application_id)));
        }catch(Exception $e){
            Log::error($e);
            if (Storage::exists($this->photo_path)) Storage::delete($this->photo_path);
            DB::rollBack();
            $this->alert('error', 'Something went wrong: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.drivers-license.capture-passport-modal');
    }

    private function generateLicense(DlLicenseApplication $dla)
    {
        if (!empty($dla->dl_drivers_license_owner_id)){
            $latest_license = DlDriversLicense::query()
                ->where(['dl_drivers_license_owner_id'=>$dla->dl_drivers_license_owner_id])
                ->latest()
                ->first();
            if (!empty($latest_license)){
                $latest_license->update(['status'=>DlDriversLicense::STATUS_EXPIRED]);
                $latest_license->save();
            }
            $owner = $dla->drivers_license_owner;
            $dla->license_restrictions = $latest_license->license_restrictions;
        }else{
            $owner = DlDriversLicenseOwner::query()->create([
                'taxpayer_id'=>$dla->taxpayer_id,
                'dl_blood_group_id'=>$dla->dl_blood_group_id,
                'dob'=>$dla->dob,
                'competence_number'=>$dla->competence_number,
                'certificate_number'=>$dla->certificate_number,
                'confirmation_number'=>$dla->confirmation_number,
                'photo_path'=>$dla->photo_path
            ]);

        }

        /** @var DlDriversLicense $license */
        $license = DlDriversLicense::query()->create([
            'dl_drivers_license_owner_id'=>$owner->id,
            'license_number'=>DlDriversLicense::getNextLicenseNumber(),
            'dl_license_duration_id'=>$dla->dl_license_duration_id,
            'issued_date'=>date('Y-m-d'),
            'expiry_date'=>date('Y-m-d',strtotime("+{$dla->license_duration->number_of_years} years")),
            'license_restrictions'=>$dla->license_restrictions,
            'dl_license_application_id'=>$dla->id
        ]);

        foreach ($dla->application_license_classes()->get() as $class){
            DlDriversLicenseClass::query()->create(
                [
                    'dl_drivers_license_id'=>$license->id,
                    'dl_license_class_id'=>$class->dl_license_class_id
                ]
            );
        }
    }
}
