<?php

namespace App\Http\Livewire\DriversLicense\Wizard;

use App\Models\DlDriversLicense;
use App\Services\LivewireWizard\Components\StepComponent;
use App\Traits\CustomAlert;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class ApplicationDetailsStep extends StepComponent
{
    use CustomAlert, WithFileUploads;

    public $blood_group_id;
    public $dob;
    public $cert_number;
    public $type;
    public $conf_number;
    public $certificate;
    public $certificate_path;
    public $editable = true;

    /**
     * @var  TemporaryUploadedFile
     */
    public $loss_report;
    public $loss_report_path = null;

    protected $rules = [
        'cert_number' => 'required|min:1|strip_tag',
        'conf_number' => 'required|numeric|min:1',
        'dob' => 'required|date',
        'blood_group_id' => 'required|numeric',
    ];

    public $width = 67;


    private array $summary;
    public $dl_number;

    public function mount()
    {
        $this->summary = $this->state()->currentStep();
        $init_step = $this->state()->all()['drivers-license.wizard.application-initial-step'];
        $this->type = strtolower($init_step['type']);
        /** @var DlDriversLicense $dl */

        $dl = DlDriversLicense::query()->where(['license_number'=>$init_step['number']])->first();
        if ($this->type!='fresh'){
            $this->blood_group_id = $dl->drivers_license_owner->dl_blood_group_id;
            $this->dob =  $dl->drivers_license_owner->dob;
            $this->cert_number = $dl->drivers_license_owner->certificate_number;
            $this->comp_number = $dl->drivers_license_owner->competence_number;
            $this->conf_number = $dl->drivers_license_owner->confirmation_number;
            $this->dl_number = $init_step['number'];
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
        if ($this->type=='duplicate' && empty($this->loss_report_path)){
            $this->rules = array_merge(['loss_report'=>'required|mimes:pdf'],$this->rules);
        }elseif($this->type=='fresh' && empty($this->certificate)){
            $this->customAlert('error', 'Please upload certificate of competence!');
            return;
        }
        $this->validate();
        if (!empty($this->loss_report) && !is_array($this->loss_report)){
            $this->loss_report_path = $this->loss_report->store("Driver-License-LOSS-REPORT-DRIVERS-LICENSE-{$this->dl_number}-".date('YmdHis').'-'.random_int(10000,99999).'.'.$this->loss_report->extension(),'local');
        }

        if (!empty($this->certificate)){
            $this->certificate_path = $this->certificate->store("Driver-License-CERTIFICATE-OF-COMPETENCE-{$this->cert_number}-".date('YmdHis').'-'.random_int(10000,99999).'.'.$this->certificate->extension(),'local');
        }
        parent::nextStep();
    }

    public function render()
    {
        return view('livewire.drivers-license.wizard.application-details-step');
    }
}
