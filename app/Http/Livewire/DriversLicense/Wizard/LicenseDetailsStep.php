<?php

namespace App\Http\Livewire\DriversLicense\Wizard;

use App\Models\BusinessStatus;
use App\Models\DlApplicationLicenseClass;
use App\Models\DlApplicationStatus;
use App\Models\DlDriversLicense;
use App\Models\DlDriversLicenseOwner;
use App\Models\DlLicenseApplication;
use App\Services\LivewireWizard\Components\StepComponent;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class LicenseDetailsStep extends StepComponent
{
    use LivewireAlert;

    public $license_class_ids = [];
    public $duration_id;
    public $restrictions;

    protected $rules = [
        'restrictions' => 'required|min:1',
        'license_class_ids' => 'required',
        'duration_id' => 'required|numeric',
    ];

    public $width = 100;

    private array $summary;
    /**
     * @var false
     */
    public $editable = true;
    public $type;
    public $classes = [];

    public function mount()
    {
        $this->summary = $this->state()->currentStep();
        $init_step = $this->state()->all()['drivers-license.wizard.application-initial-step'];
        /** @var DlDriversLicense $dl */

        $dl = DlDriversLicense::query()->where(['license_number'=>$init_step['number']])->latest()->first();
        if (strtolower($init_step['type'])!='fresh'){
            $this->editable = false;
            $this->type = strtolower($init_step['type']);
            $this->classes = $dl->drivers_license_classes;
            $this->restrictions = $dl->license_restrictions;
        }
    }

    public function stepinfo(): array
    {
        return [
            'label' => 'License Details',
            'icon' => 'bi bi-credit-card-2-front',
        ];
    }
    public function nextStep()
    {
        $this->validate();
        $init = $this->state()->all()['drivers-license.wizard.application-initial-step'];
        $applicant = $this->state()->all()['drivers-license.wizard.application-details-step'];;

        try{
            DB::beginTransaction();
            if (strtolower($init['type'])=='fresh'){
                $dl_application = DlLicenseApplication::query()->create([
                    'taxpayer_id'=>$init['taxpayer']['id'],
                    'dl_blood_group_id' => $applicant['blood_group_id'],
                    'dl_license_duration_id' => $this->duration_id,
                    'dob'=>$applicant['dob'],
                    'competence_number'=>$applicant['comp_number'],
                    'certificate_number'=>$applicant['cert_number'],
                    'confirmation_number'=>$applicant['conf_number'],
                    'license_restrictions'=>$this->restrictions,
                    'type' => strtoupper($init['type']),
                    'dl_application_status_id' => DlApplicationStatus::query()->firstOrCreate(['name'=>DlApplicationStatus::STATUS_INITIATED])->id
                ]);
            }else{
                $dl_application = DlLicenseApplication::query()->create([
                    'taxpayer_id'=>$init['taxpayer']['id'],
                    'dl_drivers_license_owner_id'=>DlDriversLicenseOwner::query()->where(['taxpayer_id'=>$init['taxpayer']['id']])->first()->id,
                    'dl_license_duration_id' => $this->duration_id,
                    'dl_blood_group_id' => $applicant['blood_group_id'],
                    'type' => strtoupper($init['type']),
                    'dl_application_status_id' => DlApplicationStatus::query()->firstOrCreate(['name'=>DlApplicationStatus::STATUS_INITIATED])->id
                ]);
            }

            foreach ($this->license_class_ids as $id){
                if (!empty($id))
                DlApplicationLicenseClass::query()->create(
                    [
                        'dl_license_application_id'=>$dl_application->id,
                        'dl_license_class_id' => $id
                    ]
                );
            }
            DB::commit();
            $this->flash('success', 'Request Submitted successfully', [], route('drivers-license.applications.show',encrypt($dl_application->id)));
        }catch (\Exception $e){
            report($e);
            DB::rollBack();
            $this->alert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.drivers-license.wizard.license-details-step');
    }
}
