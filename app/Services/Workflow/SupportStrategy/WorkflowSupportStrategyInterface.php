<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Services\Workflow\SupportStrategy;

use App\Services\Workflow\WorkflowInterface;

/**
 * @author Amrouche Hamza <hamza.simperfit@gmail.com>
 */
interface WorkflowSupportStrategyInterface
{
    public function supports(WorkflowInterface $workflow, object $subject): bool;
}
