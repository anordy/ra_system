<?php

namespace App\Http\Livewire\DriversLicense\Wizard;

use App\Models\BusinessLocation;
use App\Models\DlDriversLicense;
use App\Models\Taxpayer;
use App\Services\LivewireWizard\Components\StepComponent;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ApplicationInitialStep extends StepComponent
{
    use LivewireAlert;
    // Form Fields
    public $lookup_fired = false;
    public $number;
    public $search_type;
    public $type;
    public $taxpayer_id;
    public $summary;

    public $taxpayer;

    public $width = 33;

    protected $rules = [
        'type' => 'required',
        'number' => 'required|numeric|min:1',
        'taxpayer_id' => 'required|min:1',
    ];

    protected $messages = [
        'telephone_no.digits_between' => 'The mobile number should in the format 07XXXXXXXX',
    ];

    public function mount()
    {

        $this->summary = $this->state()->currentStep();

    }

    public function stepinfo(): array
    {
        return [
            'label' => 'Initiate',
            'icon' => 'bi bi-box',
        ];
    }

    public function nextStep()
    {
        if (empty($this->type) || empty($this->taxpayer_id)){
            $this->alert('error', 'Ensure your have provided all the details in this step!');
            return;
        }
        parent::nextStep();
    }

    public function render()
    {
        return view('livewire.drivers-license.wizard.application-initial-step');
    }

    public function applicantLookup(){
        $this->lookup_fired = true;
        if ($this->search_type == 'zin'){
            $taxpayer = Taxpayer::query()->where(['reference_no'=>$this->number])->first();
            if (!empty($taxpayer)){
                $this->taxpayer = $taxpayer;
            }else{
                $this->taxpayer = null;
            }
        }else if ($this->search_type == 'tin'){
            $taxpayer = Taxpayer::query()->where(['tin'=>$this->number])->first();
            if (!empty($taxpayer)){
                $this->taxpayer = $taxpayer;
            }else{
                $this->taxpayer = null;
            }
        }else if ($this->search_type == 'license'){
            $license = DlDriversLicense::query()->where(['license_number'=>$this->number])->first();
            if (!empty($license->drivers_license_owner)){
                $this->taxpayer = $license->drivers_license_owner->taxpayer;
            }else{
                $this->taxpayer = null;
            }
        }

        $this->taxpayer_id = $this->taxpayer->id ?? null;
    }
}
