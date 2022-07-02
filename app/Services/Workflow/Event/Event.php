<?php


namespace App\Services\Workflow\Event;

use App\Services\Workflow\Marking;
use App\Services\Workflow\Transition;
use App\Services\Workflow\WorkflowInterface;
use Symfony\Contracts\EventDispatcher\Event as BaseEvent;

class Event extends BaseEvent
{
    protected $context;
    private $subject;
    private $marking;
    private $transition;
    private $workflow;

    public function __construct(object $subject, Marking $marking, Transition $transition = null, WorkflowInterface $workflow = null, array $context = [])
    {
        $this->subject = $subject;
        $this->marking = $marking;
        $this->transition = $transition;
        $this->workflow = $workflow;
        $this->context = $context;
    }

    public function getMarking()
    {
        return $this->marking;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getTransition()
    {
        return $this->transition;
    }

    public function getWorkflow(): WorkflowInterface
    {
        return $this->workflow;
    }

    public function getWorkflowName()
    {
        return $this->workflow->getName();
    }


    public function getContext(): array
    {
        return $this->context;
    }
}
