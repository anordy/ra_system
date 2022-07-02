<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Services\Workflow;

use App\Services\Workflow\Event\AnnounceEvent;
use App\Services\Workflow\Event\CompletedEvent;
use App\Services\Workflow\Event\EnteredEvent;
use App\Services\Workflow\Event\EnterEvent;
use App\Services\Workflow\Event\GuardEvent;
use App\Services\Workflow\Event\LeaveEvent;
use App\Services\Workflow\Event\TransitionEvent;

/**
 * To learn more about how workflow events work, check the documentation
 * entry at {@link https://symfony.com/doc/current/workflow/usage.html#using-events}.
 */
final class WorkflowEvents
{
    /**
     * @Event("App\Services\Workflow\Event\GuardEvent")
     */
    public const GUARD = 'workflow.guard';

    /**
     * @Event("App\Services\Workflow\Event\LeaveEvent")
     */
    public const LEAVE = 'workflow.leave';

    /**
     * @Event("App\Services\Workflow\Event\TransitionEvent")
     */
    public const TRANSITION = 'workflow.transition';

    /**
     * @Event("App\Services\Workflow\Event\EnterEvent")
     */
    public const ENTER = 'workflow.enter';

    /**
     * @Event("App\Services\Workflow\Event\EnteredEvent")
     */
    public const ENTERED = 'workflow.entered';

    /**
     * @Event("App\Services\Workflow\Event\CompletedEvent")
     */
    public const COMPLETED = 'workflow.completed';

    /**
     * @Event("App\Services\Workflow\Event\AnnounceEvent")
     */
    public const ANNOUNCE = 'workflow.announce';

    /**
     * Event aliases.
     *
     * These aliases can be consumed by RegisterListenersPass.
     */
    public const ALIASES = [
        GuardEvent::class => self::GUARD,
        LeaveEvent::class => self::LEAVE,
        TransitionEvent::class => self::TRANSITION,
        EnterEvent::class => self::ENTER,
        EnteredEvent::class => self::ENTERED,
        CompletedEvent::class => self::COMPLETED,
        AnnounceEvent::class => self::ANNOUNCE,
    ];

    private function __construct()
    {
    }
}
