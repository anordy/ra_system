<?php

namespace App\Services\Workflow;

use App\Services\Workflow\MarkingStore\MarkingStoreInterface;
use App\Services\Workflow\MarkingStore\MethodMarkingStore;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;


class StateMachine extends Workflow
{
    public function __construct(Definition $definition, MarkingStoreInterface $markingStore = null, EventDispatcherInterface $dispatcher = null, string $name = 'unnamed', array $eventsToDispatch = null)
    {
        parent::__construct($definition, $markingStore ?? new MethodMarkingStore(true), $dispatcher, $name, $eventsToDispatch);
    }
}
