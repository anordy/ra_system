<?php

namespace App\Services\LivewireWizard\Components\Concerns;

use Livewire\Livewire;
use App\Services\LivewireWizard\Enums\StepStatus;
use App\Services\LivewireWizard\Support\Step;

trait StepAware
{
    public function bootedStepAware()
    {
        $currentFound = false;

        $currentStepName = Livewire::getAlias(static::class);

        $this->steps = collect($this->allStepNames)
            ->map(function (string $stepName) use (&$currentFound, $currentStepName) {
                $className = Livewire::getClass($stepName);

                $info = (new $className())->stepInfo();

                $status = $currentFound ? StepStatus::Next : StepStatus::Previous;


                if ($stepName === $currentStepName) {
                    $currentFound = true;
                    $status = StepStatus::Current;
                }

                return new Step($stepName, $info, $status);
            })
            ->toArray();
    }
}
