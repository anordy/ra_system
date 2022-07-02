<?php

namespace App\Services\Workflow\Event;

use App\Services\Workflow\Marking;
use App\Services\Workflow\Transition;
use App\Services\Workflow\TransitionBlocker;
use App\Services\Workflow\TransitionBlockerList;
use App\Services\Workflow\WorkflowInterface;

final class GuardEvent extends Event
{
    private $transitionBlockerList;

    /**
     * {@inheritdoc}
     */
    public function __construct(object $subject, Marking $marking, Transition $transition, WorkflowInterface $workflow = null)
    {
        parent::__construct($subject, $marking, $transition, $workflow);

        $this->transitionBlockerList = new TransitionBlockerList();
    }

    public function getTransition(): Transition
    {
        return parent::getTransition();
    }

    public function isBlocked(): bool
    {
        return !$this->transitionBlockerList->isEmpty();
    }

    public function setBlocked(bool $blocked, string $message = null): void
    {
        if (!$blocked) {
            $this->transitionBlockerList->clear();

            return;
        }

        $this->transitionBlockerList->add(TransitionBlocker::createUnknown($message));
    }

    public function getTransitionBlockerList(): TransitionBlockerList
    {
        return $this->transitionBlockerList;
    }

    public function addTransitionBlocker(TransitionBlocker $transitionBlocker): void
    {
        $this->transitionBlockerList->add($transitionBlocker);
    }
}
