<?php

namespace App\Http\Livewire\DriversLicense\Wizard;

use App\Services\LivewireWizard\Components\WizardComponent;

class Wizard extends WizardComponent
{

    public function initialState(): array
    {
        return ['drivers-license.wizard.application-initial-step'=>['']];
    }

    public function steps(): array
    {
        return [
            ApplicationInitialStep::class,
            ApplicationDetailsStep::class,
            LicenseDetailsStep::class,
        ];
    }
}
