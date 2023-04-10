<?php

namespace App\Http\Livewire\DriversLicense;


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

    use CustomAlert,WithFileUploads,WorkflowProcesssingTrait;


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
            'photo'=>'required|mimes:png,jpg,jpeg|max:1024'
        ];
    }


    public function submit()
    {
        $this->validate();
        try{
            DB::beginTransaction();
            $this->photo_path = $this->photo->storeAs('dl_passport', "dl-passport-{$this->application_id}-".date('YmdHis').'-'.random_int(10000,99999).'.'.$this->photo->extension());
            $dla =  DlLicenseApplication::query()->find($this->application_id);
            if(is_null($dla)){
                abort(404);
            }
            $dla->update([
                'photo_path'=>$this->photo_path,
                'dl_application_status_id'=>DlApplicationStatus::query()->firstOrCreate(['name'=>DlApplicationStatus::STATUS_LICENSE_PRINTING])->id,
            ]);
            $this->generateLicense($dla);
            DB::commit();
            $this->flash('success', 'Photo Uploaded', [], route('drivers-license.applications.show',encrypt($this->application_id)));
        }catch(Exception $e){
            DB::rollBack();
            Log::error($e);
            if (Storage::disk('local')->exists($this->photo_path)) Storage::disk('local')->delete($this->photo_path);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help.');
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
                'certificate_path'=>$dla->certificate_path,
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
