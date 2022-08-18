<?php

namespace App\Services\LivewireWizard\Exceptions;

use Exception;
use App\Services\LivewireWizard\Support\State;

class InvalidStateClassName extends Exception
{
    public static function doesNotExtendState(string $wizardClass, string $invalidStateClassName): self
    {
        $correctStateClassName = State::class;

        return new self("The `stateClass` method of the wizard class `{$wizardClass}` returned an invalid state class `{$invalidStateClassName}`. Make sure that the class name you return extends `{$correctStateClassName}`.");
    }
}
