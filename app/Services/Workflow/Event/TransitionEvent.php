<?php

namespace App\Services\Workflow\Event;

final class TransitionEvent extends Event
{
    public function setContext(array $context): void
    {
        $this->context = $context;
    }
}
