<?php

namespace App\Services\LivewireWizard\Components;

use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\Livewire;
use App\Services\LivewireWizard\Components\Concerns\MountsWizard;
use App\Services\LivewireWizard\Exceptions\InvalidStepComponent;
use App\Services\LivewireWizard\Exceptions\NoNextStep;
use App\Services\LivewireWizard\Exceptions\NoPreviousStep;
use App\Services\LivewireWizard\Exceptions\NoStepsReturned;
use App\Services\LivewireWizard\Exceptions\StepDoesNotExist;
use App\Services\LivewireWizard\Support\State;

abstract class WizardComponent extends Component
{
    use MountsWizard;

    public array $allStepState = [];
    public ?string $currentStepName = null;

    protected $listeners = [
        'previousStep',
        'nextStep',
        'showStep',
    ];

    /** @return <int, class-string<StepComponent> */
    abstract public function steps(): array;

    public function initialState(): ?array
    {
        return null;
    }

    public function stepNames(): Collection
    {
        $steps = collect($this->steps())
            ->each(function (string $stepClassName) {
                if (! is_a($stepClassName, StepComponent::class, true)) {
                    throw InvalidStepComponent::doesNotExtendStepComponent(static::class, $stepClassName);
                }
            })
            ->map(function (string $stepClassName) {
                $alias = Livewire::getAlias($stepClassName);

                if (is_null($alias)) {
                    throw InvalidStepComponent::notRegisteredWithLivewire(static::class, $stepClassName);
                }

                return $alias;
            });

        if ($steps->isEmpty()) {
            throw NoStepsReturned::make(static::class);
        }

        return $steps;
    }

    public function previousStep(array $currentStepState)
    {
        $currentStep = collect($this->stepNames())->filter(function ($value, $key) {
            return $value  === $this->currentStepName;
        })->all();
        $previousStepIndex = (int) key($currentStep) - 1;

        $previousStep = $this->stepNames()->get($previousStepIndex);

//        $previousStep = collect($this->stepNames())
//            ->before(fn (string $step) => $step === $this->currentStepName);

        if (! $previousStep) {
            throw NoPreviousStep::make(self::class, $this->currentStepName);
        }

        $this->showStep($previousStep, $currentStepState);
    }

    public function nextStep(array $currentStepState)
    {
        $currentStep = collect($this->stepNames())->filter(function ($value, $key) {
            return $value  === $this->currentStepName;
        })->all();

        $nextStepIndex = (int) key($currentStep) + 1;

        $nextStep = $this->stepNames()->get($nextStepIndex);

        if (! $nextStep) {
            throw NoNextStep::make(self::class, $this->currentStepName);
        }

        $this->showStep($nextStep, $currentStepState);
    }

    public function showStep($toStepName, array $currentStepState = [])
    {
        if ($this->currentStepName) {
            $this->setStepState($this->currentStepName, $currentStepState);
        }

        $this->currentStepName = $toStepName;
    }

    public function setStepState(string $step, array $state = []): void
    {
        if (! $this->stepNames()->contains($step)) {
            throw StepDoesNotExist::doesNotHaveState($step);
        }

        $this->allStepState[$step] = $state;
    }

    public function render()
    {
        $currentStepState = array_merge(
            $this->allStepState[$this->currentStepName] ?? [],
            [
                'allStepNames' => $this->stepNames()->toArray(),
                'allStepsState' => $this->allStepState,
                'stateClassName' => $this->stateClass(),
            ],
        );

        return view('livewire.livewire-wizard.wizard', compact('currentStepState'));
    }

    /** @return class-string<State> */
    public function stateClass(): string
    {
        return State::class;
    }
}
