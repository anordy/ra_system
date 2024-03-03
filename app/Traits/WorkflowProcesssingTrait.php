<?php

namespace App\Traits;

use App\Models\Workflow;
use App\Services\Workflow\Subscriber\WorkflowSubscriber;
use App\Services\Workflow\WorkflowRegistry;
use Illuminate\Support\Facades\Log;

trait WorkflowProcesssingTrait
{
    public $flow;
    public $subject;

    public function registerWorkflow($modelName, $modelId)
    {
        try {
            $this->subject = $modelName::findOrFail($modelId);
            $workflow = Workflow::where('supports', $modelName)->latest()->first();

            if ($workflow == null) {
                Log::error('Workflow object is not configured' . $modelName);
                $this->workflow = [];
            } else {

                $this->flow = [
                    $workflow->code => [
                        'type' => 'workflow',
                        'marking_store' => json_decode($workflow->marking_store, true),
                        'initial_marking' => $workflow->initial_marking,
                        'supports' => [$workflow->supports],
                        'places' => json_decode($workflow->places, true),
                        'transitions' => json_decode($workflow->transitions, true),
                    ],
                ];
            }
        } catch (\Exception $exception){
            Log::error($exception);
            abort(500, 'Something went wrong, please contact system administrator.');
        }
    }

    /**
     * Each call should be wrapped within a try and catch block.
     * @throws \Exception
     */
    public function doTransition($transition, $context)
    {
        try {
            $registry = new WorkflowRegistry($this->flow, new WorkflowSubscriber());
            $workflow = $registry->get($this->subject);
            $workflow->apply($this->subject, $transition, $context);
            $this->subject->save();
        } catch (\Exception $exception){
            Log::error($exception);
            throw $exception;
        }
    }

    public function getEnabledTransitions()
    {
        if ($this->flow == []) {
            Log::error('Workflow object is null for model ' . get_class($this->subject));
            return [];
        }
        $registry = new WorkflowRegistry($this->flow, new WorkflowSubscriber());
        $workflow = $registry->get($this->subject);

        return $workflow->getEnabledTransitions($this->subject);
    }

    public function checkTransition($name)
    {
        $registry = new WorkflowRegistry($this->flow, new WorkflowSubscriber());
        $workflow = $registry->get($this->subject);

        return $workflow->can($this->subject, $name);
    }
}
