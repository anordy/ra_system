<?php

namespace App\Http\Livewire\DriversLicense\Wizard;

use App\Models\DlDriversLicense;
use App\Services\LivewireWizard\Components\StepComponent;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ApplicationDetailsStep extends StepComponent
{
    use LivewireAlert;

    public $blood_group_id;
    public $dob;
    public $cert_number;
    public $conf_number;
    public $comp_number;
    public $editable = true;

    protected $rules = [
        'cert_number' => 'required|min:1',
        'conf_number' => 'required|numeric|min:1',
        'comp_number' => 'required|numeric|min:1',
        'dob' => 'required|date',
        'blood_group_id' => 'required|numeric',
    ];

    public $width = 67;


    private array $summary;

    public function mount()
    {
        $this->summary = $this->state()->currentStep();
        $init = $this->state()->all()['drivers-license.wizard.application-initial-step'];
        $this->type = $init['type'];
        $init_step = $this->state()->all()['drivers-license.wizard.application-initial-step'];
        /** @var DlDriversLicense $dl */

        $dl = DlDriversLicense::query()->where(['license_number'=>$init_step['number']])->first();
        if (strtolower($init_step['type'])!='fresh'){
            $this->blood_group_id = $dl->drivers_license_owner->dl_blood_group_id;
            $this->dob =  $dl->drivers_license_owner->dob;
            $this->cert_number = $dl->drivers_license_owner->certificate_number;
            $this->comp_number = $dl->drivers_license_owner->competence_number;
            $this->conf_number = $dl->drivers_license_owner->confirmation_number;
            $this->editable = false;
        }
    }

    public function stepinfo(): array
    {
        return [
            'label' => 'Applicant Details',
            'icon' => 'bi bi-file-person',
        ];
    }
    public function nextStep()
    {
        $this->validate();
        parent::nextStep();
    }

    public function render()
    {
        return view('livewire.drivers-license.wizard.application-details-step');
    }
}
