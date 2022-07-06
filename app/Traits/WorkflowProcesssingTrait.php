<?php

namespace App\Traits;

use App\Models\Workflow;
use App\Services\Workflow\Events\WorkflowSubscriber;
use App\Services\Workflow\WorkflowRegistry;

use function PHPUnit\Framework\returnSelf;

trait WorkflowProcesssingTrait
{
    public $flow;
    public $subject;

    public function registerWorkflow($modelName, $modelId)
    {
        $this->subject = $modelName::find($modelId);
        $workflow = Workflow::where('supports', $modelName)->first();

        $this->flow = [
            $workflow->code   => [
                'type'          => 'workflow',
                'marking_store' => json_decode($workflow->marking_store, true),
                'initial_marking' => $workflow->initial_marking,
                'supports'      => [$workflow->supports],
                'places'        => json_decode($workflow->places, true),
                'transitions'   => json_decode($workflow->transitions, true),
            ]
        ];
    }

    public function doTransition($transition, $context)
    {
        $registry = new WorkflowRegistry($this->flow, new WorkflowSubscriber());
        $workflow = $registry->get($this->subject);
        $workflow->apply($this->subject, $transition, $context);
        $this->subject->save();
    }

    public function getEnabledTranstions()
    {
        $registry = new WorkflowRegistry($this->flow, new WorkflowSubscriber());
        $workflow = $registry->get($this->subject);

        $enabledTransitions = $workflow->getEnabledTransitions($this->subject);
        return $enabledTransitions;
    }

    public function checkTransition($name)
    {
        $registry = new WorkflowRegistry($this->flow, new WorkflowSubscriber());
        $workflow = $registry->get($this->subject);

        return $workflow->can($this->subject, $name);
    }
}
