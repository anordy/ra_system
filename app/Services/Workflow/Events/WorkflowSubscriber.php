<?php

namespace App\Services\Workflow\Events;

use App\Models\Role;
use App\Models\User;
use App\Models\WorkflowTask;
use App\Services\Workflow\Event\Event;
use App\Services\Workflow\Event\GuardEvent;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class WorkflowSubscriber implements EventSubscriberInterface
{
    protected $expressionLanguage;

    public function __construct()
    {
        $this->expressionLanguage = new ExpressionLanguage();
    }
    public function guardEvent(GuardEvent $event)
    {
        $user = auth()->user();
        $marking = $event->getMarking()->getPlaces();
        $place = $marking[key($marking)];
        $owner = $place['owner'];
        $operator_type = $place['operator_type'];
        $operators = $place['operators'];
        $status = $place['status'];

        if ($status != 1) {
            $event->setBlocked(true);
        }

        if ($owner == 'taxpayer') {
            $event->setBlocked(true);
        }

        if ($operator_type == "role") {
            $role = Role::find($user->role->id);
            if ($role == null) {
                $event->setBlocked(true);
            }
            if (!in_array($user->role->id, $operators)) {
                $event->setBlocked(true);
            }
        } elseif ($operator_type == 'user') {
            if (!in_array($user->id, $operators)) {
                $event->setBlocked(true);
            }
        } else {
            $event->setBlocked(true);
        }
    }

    public function leaveEvent(Event $event)
    {

        $places       = $event->getTransition()->getFroms();
        $workflowName = $event->getWorkflowName();
    }

    public function transitionEvent(Event $event)
    {
        $workflowName   = $event->getWorkflowName();
        $transitionName = $event->getTransition()->getName();
    }

    public function enterEvent(Event $event)
    {
        $places       = $event->getTransition()->getTos();
        $workflowName = $event->getWorkflowName();
    }

    public function enteredEvent(Event $event)
    {
        $workflowName = $event->getWorkflowName();
        $transition   = $event->getTransition();
    }

    public function completedEvent(Event $event)
    {
        $user = auth()->user();
        $subject = $event->getSubject();
        $marking = $event->getMarking();
        $places = $marking->getPlaces();
        $transition = $event->getTransition();
        $context = $event->getContext();

        try {
            foreach ($places as $key => $place) {
                $task =  new WorkflowTask([
                    'workflow_id' => 1,
                    'name' => $transition->getName(),
                    'from_place' => $transition->getFroms()[0],
                    'to_place' => $key,
                    'owner' => $place['owner'],
                    'operator_type' => $place['operator_type'],
                    'operators' => json_encode($place['operators']),
                    'approved_on' => Carbon::now()->toDateTimeString(),
                    'user_id' => $user->id,
                    'user_type' => get_class($user),
                    'status' => 'running',
                    'remarks' => $context['comment']
                ]);

                DB::transaction(function () use ($task, $subject) {
                    $subject->pinstances()->save($task);
                });
            }
        } catch (Exception $e) {
            report($e);
        }
    }

    public function announceEvent(Event $event)
    {
        $user = auth()->user();
        $subject = $event->getSubject();
        $marking = $event->getMarking();
        $places = $marking->getPlaces();
        $transition = $event->getTransition();
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.guard'      => ['guardEvent'],
            'workflow.leave'      => ['leaveEvent'],
            'workflow.transition' => ['transitionEvent'],
            'workflow.enter'      => ['enterEvent'],
            'workflow.entered'    => ['enteredEvent'],
            'workflow.completed'  => ['completedEvent'],
            'workflow.announce'  => ['announceEvent'],
        ];
    }
}
