<?php

namespace App\Services\Workflow\Subscriber;

use App\Enum\DisputeStatus;
use App\Enum\ReturnApplicationStatus;
use App\Enum\TaxAuditStatus;
use App\Enum\TaxClearanceStatus;
use App\Enum\TaxInvestigationStatus;
use App\Enum\TaxVerificationStatus;
use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\Returns\BFO\BfoReturn;
use App\Models\Returns\EmTransactionReturn;
use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Models\Returns\MmTransferReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\Returns\Vat\VatReturn;
use App\Models\TaxAgentStatus;
use App\Models\Taxpayer;
use App\Models\User;
use App\Models\Workflow;
use App\Models\WorkflowTask;
use App\Models\WorkflowTaskOperator;
use App\Notifications\DatabaseNotification;
use App\Services\Workflow\Event\Event;
use App\Services\Workflow\Event\GuardEvent;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        $status = $place['status'];

        if ($status != 1) {
            $event->setBlocked(true);
        }

        if ($owner == 'taxpayer') {
            $event->setBlocked(true);
        }

        if ($task) {
            $can_approve = $task->actors()
                ->where('user_type', get_class($user))
                ->where('user_id', $user->id)->first();

            if ($can_approve == null) {
                $event->setBlocked(true);
            }
        }
    }

    public function leaveEvent(Event $event)
    {

        $places = $event->getTransition()->getFroms();
        $workflowName = $event->getWorkflowName();
    }

    public function transitionEvent(Event $event)
    {
        $workflowName = $event->getWorkflowName();
        $transitionName = $event->getTransition()->getName();
    }

    public function enterEvent(Event $event)
    {
        $places = $event->getTransition()->getTos();
        $workflowName = $event->getWorkflowName();
    }

    public function enteredEvent(Event $event)
    {
        $workflowName = $event->getWorkflowName();
        $transition = $event->getTransition();
    }

    public function completedEvent(Event $event)
    {
        $user = auth()->user();
        $subject = $event->getSubject();
        $marking = $event->getMarking();
        $places = $marking->getPlaces();
        $transition = $event->getTransition();
        $context = $event->getContext();
        $placeName = $event->getWorkflowName();

        $task = $subject->pinstancesActive;
        if ($task) {
            $task->status = 'completed';
            $task->save();
        }

        $workflow = Workflow::where('code', $event->getWorkflowName())->first();

        DB::beginTransaction();
        try {
            foreach ($places as $key => $place) {

                $operators = json_encode($place['operators']);
                $original_operators = json_encode($place['operators']);
                $operator_type = $place['operator_type'];
                if (array_key_exists('operators', $context) && $context['operators'] != []) {
                    $operators = json_encode($context['operators']);
                    $original_operators = $operators;
                } else {

                    $original_operators = json_encode($place['operators']);

                    if ($place['operator_type'] == 'role') {
                        $users = User::whereIn('role_id', $place['operators'])->get();
                        $operators = $users->count() > 0 ? json_encode($users->pluck('id')) : json_encode([]);
                    } else {
                        $operators = json_encode($place['operators']);
                    }
                }

                $comment = null;
                if (array_key_exists('comment', $context)) {
                    $comment = $context['comment'];
                }

                $new_task = new WorkflowTask([
                    'workflow_id' => $workflow->id,
                    'pinstance_type' => get_class($subject),
                    'pinstance_id' => $subject->id,
                    'name' => $transition->getName(),
                    'from_place' => $transition->getFroms()[0],
                    'to_place' => $key,
                    'owner' => $place['owner'],
                    'operator_type' => $operator_type,
                    'original_operators' => $original_operators,
                    'operators' => $operators,
                    'approved_on' => Carbon::now()->toDateTimeString(),
                    'user_id' => $user->id ?? null,
                    'user_type' => $user != null ? get_class($user) : null,
                    'status' => $key == 'completed' ? 'completed' : 'running',
                    'remarks' => $comment,
                ]);

                $new_task->save();
                $operators_collection = array();

                $user_type = '';
                if ($place['owner'] == 'taxpayer') {
                    $user_type = Taxpayer::class;
                } elseif ($place['owner'] == 'staff') {
                    $user_type = User::class;
                }

                foreach (json_decode($operators) as $operator) {
                    array_push(
                        $operators_collection,
                        new WorkflowTaskOperator([
                            'task_id' => $new_task->id,
                            'workflow_id' => $workflow->id,
                            'user_id' => $operator,
                            'user_type' => $user_type
                        ])
                    );
                }

                $new_task->actors()->saveMany($operators_collection);
            }

            if ($placeName == 'TAX_RETURN_VERIFICATION') {
                if (key($places) == 'completed') {
                    $assessmentExists = $subject->assessment()->exists();
                    if ($assessmentExists) {
                        $subject->taxReturn->application_status = ReturnApplicationStatus::ADJUSTED;
                    } else {
                        $subject->taxReturn->application_status = ReturnApplicationStatus::SELF_ASSESSMENT;
                    }
                    $subject->status = TaxVerificationStatus::APPROVED;
                    $subject->approved_on = Carbon::now()->toDateTimeString();

                    $subject->taxReturn->save();
                }
            } elseif ($placeName == 'TAX_AUDIT') {
                if (key($places) == 'completed') {
                    $subject->status = TaxAuditStatus::APPROVED;
                    $subject->approved_on = Carbon::now()->toDateTimeString();
                }
            } elseif ($placeName == 'TAX_INVESTIGATION') {
                if (key($places) == 'completed') {
                    $subject->status = TaxInvestigationStatus::APPROVED;
                    $subject->approved_on = Carbon::now()->toDateTimeString();
                }
                if (key($places) == 'legal') {
                    $subject->status = TaxInvestigationStatus::LEGAL;
                    $subject->approved_on = Carbon::now()->toDateTimeString();
                }
            } elseif ($placeName == 'TAX_CLEARENCE') {
                if (key($places) == 'completed') {
                    $subject->status = TaxClearanceStatus::APPROVED;
                    $subject->approved_on = Carbon::now()->toDateTimeString();
                    $subject->expire_on = Carbon::now()->endOfYear()->toDateTimeString();
                }
                if (key($places) == 'rejected') {
                    $subject->status = TaxClearanceStatus::REJECTED;
                    $subject->approved_on = Carbon::now()->toDateTimeString();
                }
            } elseif ($placeName == 'DISPUTE') {
                if (key($places) == 'completed') {
                    $subject->app_status = DisputeStatus::APPROVED;
                    $subject->approved_on = Carbon::now()->toDateTimeString();
                }
                if (key($places) == 'rejected') {
                    $subject->app_status = DisputeStatus::REJECTED;
                    $subject->approved_on = Carbon::now()->toDateTimeString();
                }
            } elseif ($placeName == 'TAX_CONSULTANT_VERIFICATION') {
                if (key($places) == 'completed') {
                    $subject->status = TaxAgentStatus::APPROVED;
                    $subject->approved_at = Carbon::now()->toDateTimeString();
                }
                if (key($places) == 'rejected') {
                    $subject->status = TaxAgentStatus::CORRECTION;
                    $subject->approved_at = Carbon::now()->toDateTimeString();
                }
            } elseif ($placeName == 'RENEW_TAX_CONSULTANT_VERIFICATION') {
                if (key($places) == 'completed') {
                    $subject->status = TaxAgentStatus::APPROVED;
                    $subject->approved_at = Carbon::now()->toDateTimeString();
                }
                if (key($places) == 'rejected') {
                    $subject->status = TaxAgentStatus::CORRECTION;
                    $subject->approved_at = Carbon::now()->toDateTimeString();
                }
            } elseif ($placeName == 'BUSINESS_REGISTRATION') {
                if (key($places) == 'correct_application') {
                    event(new SendSms('business-registration-correction', $subject->id, ['message' => $context['comment']]));
                    event(new SendMail('business-registration-correction', $subject->id, ['message' => $context['comment']]));
                }
                if (key($places) == 'completed') {
                    event(new SendSms('business-registration-approved', $subject->id));
                    event(new SendMail('business-registration-approved', $subject->id));
                }
            } else {
                if (key($place) == 'completed') {
                    $subject->status = TaxAuditStatus::APPROVED;
                    $subject->approved_on = Carbon::now()->toDateTimeString();
                } elseif (key($place) == 'rejected') {
                    $subject->status = TaxAuditStatus::REJECTED;
                    $subject->approved_on = Carbon::now()->toDateTimeString();
                }
            }
            $subject->marking = json_encode($subject->marking);
            $subject->save();
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new Exception($e);
        }

        DB::commit();
    }

    public function announceEvent(Event $event)
    {
        try {
            $subject = $event->getSubject();
            $marking = $event->getMarking();
            $placesCurrent = $marking->getPlaces();

            $places = $placesCurrent[key($placesCurrent)];

            $notificationName = strtoupper(str_replace('_', ' ', $event->getWorkflowName())) . ' APPROVAL';

            $placeName = $event->getWorkflowName();

            $urls = $this->getApplicationUrls($placeName, $subject);

            $hrefClient = $urls['client'];
            $hrefAdmin = $urls['admin'];

            $this->notifyActors($placeName, $placesCurrent, $event, $notificationName, $places, $subject, $hrefClient, $hrefAdmin);

        } catch (Exception $e) {
            report($e);
            throw new Exception($e);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.guard' => ['guardEvent'],
            'workflow.leave' => ['leaveEvent'],
            'workflow.transition' => ['transitionEvent'],
            'workflow.enter' => ['enterEvent'],
            'workflow.entered' => ['enteredEvent'],
            'workflow.completed' => ['completedEvent'],
            'workflow.announce' => ['announceEvent'],
        ];
    }

    private function getApplicationUrls($placeName, $subject)
    {
        try {
            if ($placeName == 'BUSINESS_UPDATE') {
                $hrefClient = 'business.index';
                $hrefAdmin = 'business.updatesRequests';
            } elseif ($placeName == 'BUSSINESS_REGISTRATION') {
                $hrefClient = 'business.index';
                $hrefAdmin = 'business.registrations.approval';
            } elseif ($placeName == 'BUSINESS_TAX_TYPE_CHANGE') {
                $hrefClient = 'business.taxTypes';
                $hrefAdmin = 'business.registrations.approval';
            } elseif ($placeName == 'BUSSINESS_DEREGISTRATION') {
                $hrefClient = 'business.deregistrations';
                $hrefAdmin = 'business.deregistrations';
            } elseif ($placeName == 'BUSSINESS_CLOSURE') {
                $hrefClient = 'business.closures';
                $hrefAdmin = 'business.closure';
            } elseif ($placeName == 'BUSSINESS_BRANCH_REGISTRATION') {
                $hrefClient = 'business.branches.index';
                $hrefAdmin = 'business.branches.index';
            } elseif ($placeName == 'DEBT_WAIVER') {
                $hrefClient = 'debts.waiver.index';
                $hrefAdmin = 'debts.waivers.index';
            } elseif ($placeName == 'TAXPAYER_DETAILS_AMENDMENT_VERIFICATION') {
                $hrefClient = 'taxpayers-amendment.index';
                $hrefAdmin = 'taxpayers-amendment.index';
            } elseif ($placeName == 'kyc_details_amendment_verification') {
                $hrefClient = 'kycs-amendment.index';
                $hrefAdmin = 'kycs-amendment.index';
            } elseif ($placeName == 'TAX_RETURN_VETTING') {
                $return = $subject->return;
                if ($return instanceof PetroleumReturn) {
                    $hrefClient = 'returns.petroleum.index';
                    $hrefAdmin = 'returns.petroleum.index';
                } elseif ($return instanceof StampDutyReturn) {
                    $hrefClient = 'returns.stamp-duty.index';
                    $hrefAdmin = 'returns.stamp-duty.index';
                } elseif ($return instanceof VatReturn) {
                    $hrefClient = 'returns.vat-return.index';
                    $hrefAdmin = 'returns.vat-return.index';
                } elseif ($return instanceof MmTransferReturn) {
                    $hrefClient = 'returns.mm-transfer.show-returns';
                    $hrefAdmin = 'returns.mobile-money-transfer.index ';
                } elseif ($return instanceof MnoReturn) {
                    $hrefClient = 'returns.excise-duty.mno';
                    $hrefAdmin = 'returns.excise-duty.mno';
                } elseif ($return instanceof BfoReturn) {
                    $hrefClient = 'returns.excise-duty.show-returns';
                    $hrefAdmin = 'returns.bfo-excise-duty.index';
                } elseif ($return instanceof EmTransactionReturn) {
                    $hrefClient = 'returns.em-transaction.show-returns';
                    $hrefAdmin = 'returns.em-transaction.index';
                } else {
                    $hrefClient = null;
                    $hrefAdmin = null;
                }
            } else {
                $hrefClient = null;
                $hrefAdmin = null;
            }
            return ['client' => $hrefClient, 'admin' => $hrefAdmin];
        } catch (Exception $exception) {
            Log::error('WORKFLOW-SUBSCRIBER-WS', [$exception]);
            throw $exception;
        }

    }

    private function notifyActors($placeName, $placesCurrent, $event, $notificationName, $places, $subject, $hrefClient, $hrefAdmin) {
        try {
            if ($placeName) {
                if (key($placesCurrent) == 'completed') {
                    if ($event->getSubject()->taxpayer || $placeName == 'TAXPAYER_DETAILS_AMENDMENT_VERIFICATION') {
                        $event->getSubject()->taxpayer->notify(new DatabaseNotification(
                            $notificationName,
                            'Your request has been approved successfully.',
                            $hrefClient ?? null,
                            'View',
                            null,
                            'taxpayer'
                        ));
                    }
                } elseif (key($placesCurrent) == 'rejected') {
                    if ($event->getSubject()->taxpayer || $placeName == 'TAXPAYER_DETAILS_AMENDMENT_VERIFICATION') {
                        $event->getSubject()->taxpayer->notify(new DatabaseNotification(
                            $notificationName,
                            'Your request has been rejected .',
                            $hrefClient ?? null,
                            'View',
                            null,
                            'taxpayer',
                        ));
                    }
                } elseif (key($placesCurrent) == 'correct_application') {
                    if ($event->getSubject()->taxpayer || $placeName == 'TAX_RETURN_VETTING') {
                        $event->getSubject()->taxpayer->notify(new DatabaseNotification(
                            $notificationName,
                            'Your return required correction .',
                            $hrefClient ?? null,
                            'View',
                            null,
                            'taxpayer',
                        ));
                    }
                }

                if ($places['owner'] == 'staff') {
                    $task = $subject->pinstance;
                    $actors = json_decode($task->operators);
                    if (gettype($actors) != "array") {
                        $actors = [];
                    }
                    if ($places['operator_type'] == 'role') {
                        $users = User::whereIn('role_id', $actors)->get();
                        foreach ($users as $u) {
                            $u->notify(new DatabaseNotification(
                                $notificationName,
                                'You have a request to review',
                                $hrefAdmin ?? null,
                                'view'
                            ));
                        }
                    }
                }
            }
        } catch (Exception $exception) {
            Log::error('WORKFLOW-SUBSCRIBER-NOTIFY-ACTORS', [$exception]);
            throw $exception;
        }
    }
}
