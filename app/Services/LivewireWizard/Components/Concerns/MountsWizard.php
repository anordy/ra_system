<?php

namespace App\Services\LivewireWizard\Components\Concerns;

use App\Services\LivewireWizard\Exceptions\InvalidStateClassName;
use App\Services\LivewireWizard\Support\State;

trait MountsWizard
{
    public function mountMountsWizard(?string $showStep = null, array $initialState = null)
    {
        $stepName = $showStep ?? $this->currentStepName ?? $this->stepNames()->first();

        $initialState = $initialState ?? $this->initialState() ?? [];

        $this->showStep($stepName, $initialState[$stepName] ?? []);

        foreach ($initialState as $stepName => $state) {
            $this->setStepState($stepName, $state);
        }

        if (! is_a($this->stateClass(), State::class, true)) {
            throw InvalidStateClassName::doesNotExtendState(static::class, $this->stateClass());
        };
    }
}
