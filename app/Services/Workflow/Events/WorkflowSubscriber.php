<?php

namespace App\Services\Workflow\Events;

use App\Models\Role;
use App\Models\User;
use App\Models\Workflow;
use App\Models\WorkflowTask;
use App\Notifications\DatabaseNotification;
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
        $subject = $event->getSubject();
        $task = $subject->pinstancesActive;
        $user = auth()->user();
        $marking = $event->getMarking()->getPlaces();
        $place = $marking[key($marking)];
        $owner = $place['owner'];

        if ($task) {
            $operator_type = $task->operator_type;
            $operators = json_decode($task->operators, true);
        } else {
            $operator_type = $place["operator_type"];
            $operators = $place['operators'];
        }
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


        $task = $subject->pinstancesActive;
        if ($task) {
            $task->status = 'completed';
            $task->save();
        }

        $workflow = Workflow::where('code', $event->getWorkflowName())->first();

        try {
            foreach ($places as $key => $place) {
                $task =  new WorkflowTask([
                    'workflow_id' => $workflow->id,
                    'name' => $transition->getName(),
                    'from_place' => $transition->getFroms()[0],
                    'to_place' => $key,
                    'owner' => $place['owner'],
                    'operator_type' => $place['operator_type'],
                    'operators' => json_encode($place['operators']),
                    'approved_on' => Carbon::now()->toDateTimeString(),
                    'user_id' => $user->id,
                    'user_type' => get_class($user),
                    'status' => $key == 'completed' ? 'completed' : 'running',
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
        try {
            $user = auth()->user();
            $subject = $event->getSubject();
            $marking = $event->getMarking();
            $placesCurrent = $marking->getPlaces();
            $transition = $event->getTransition();

            $places = $placesCurrent[key($placesCurrent)];

            $notificationName = strtoupper(str_replace('_', ' ', $event->getWorkflowName())) . ' APPROVAL';

            $placeName = $event->getWorkflowName();

            if ($placeName == 'BUSINESS_UPDATE') {
                $hrefClient = 'business.index';
                $hrefAdmin = 'business.updatesRequests';
            } elseif ($placeName == 'BUSINESS_REGISTRATION') {
                $hrefClient = 'business.index';
                $hrefAdmin = 'business.registrations.index';
            } elseif ($placeName == 'BUSINESS_TAX_TYPE_CHANGE') {
                $hrefClient = 'business.taxTypes';
                $hrefAdmin = 'business.registrations.index';
            } elseif ($placeName == 'BUSINESS_DEREGISTER') {
                $hrefClient = 'business.deregistrations';
                $hrefAdmin = 'business.deregistrations';
            } elseif ($placeName == 'BUSSINESS_CLOSURE') {
                $hrefClient = 'business.closures';
                $hrefAdmin = 'business.closure';
            } elseif ($placeName == 'BUSSINESS_BRANCH_REGISTRATION') {
                $hrefClient = 'business.branches.index';
                $hrefAdmin = 'business.branches.index';
            }

            if (key($placesCurrent) == 'completed') {
                $event->getSubject()->taxpayer->notify(new DatabaseNotification(
                    $subject = $notificationName,
                    $message = 'Your request has been approved successfully.',
                    $href = $hrefClient ?? null,
                    $hrefText = 'View',
                    $owner = 'taxpayer'
                ));
            } elseif (key($placesCurrent) == 'rejected') {
                $event->getSubject()->taxpayer->notify(new DatabaseNotification(
                    $subject = $notificationName,
                    $message = 'Your request has been rejected .',
                    $href = $hrefClient ?? null,
                    $hrefText = 'View',
                    $owner = 'taxpayer',
                ));
            }

            if ($places['owner'] == 'staff') {
                $operators = $places['operators'];
                if ($places['operator_type'] == 'role') {
                    $users = User::whereIn('role_id', $operators)->get();
                    foreach ($users as $u) {
                        $u->notify(new DatabaseNotification(
                            $subject = $notificationName,
                            $message = 'You have a business to review',
                            $href = $hrefAdmin ?? null,
                            $hrefText = 'view'
                        ));
                    }
                }
            }
        } catch (Exception $e) {
            report($e);
        }
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
