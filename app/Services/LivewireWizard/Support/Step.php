<?php

namespace App\Services\LivewireWizard\Support;

use Illuminate\Support\Arr;
use App\Services\LivewireWizard\Enums\StepStatus;

class Step
{
    public $stepName;
    public $info;
    public $status;
    public function __construct($stepName, $info, $status)
    {
        $this->stepName = $stepName;
        $this->info = $info;
        $this->status = $status;
    }

    public function isPrevious(): bool
    {
        return $this->status === StepStatus::Previous;
    }

    public function isCurrent(): bool
    {
        return $this->status === StepStatus::Current;
    }

    public function isNext(): bool
    {
        return $this->status === StepStatus::Next;
    }

    public function show(): string
    {
        return "showStep('{$this->stepName}')";
    }

    public function __get(string $key)
    {
        return Arr::get($this->info, $key);
    }
}
