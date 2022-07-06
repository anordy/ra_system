<?php

namespace App\Services\Workflow;

use App\Services\Workflow\Exception\InvalidArgumentException;
use App\Services\Workflow\SupportStrategy\WorkflowSupportStrategyInterface;

class Registry
{
    private $workflows = [];

    public function addWorkflow(WorkflowInterface $workflow, WorkflowSupportStrategyInterface $supportStrategy)
    {
        $this->workflows[] = [$workflow, $supportStrategy];
    }

    public function has(object $subject, string $workflowName = null): bool
    {
        foreach ($this->workflows as [$workflow, $supportStrategy]) {
            if ($this->supports($workflow, $supportStrategy, $subject, $workflowName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Workflow
     */
    public function get(object $subject, string $workflowName = null)
    {
        $matched = [];

        foreach ($this->workflows as [$workflow, $supportStrategy]) {
            if ($this->supports($workflow, $supportStrategy, $subject, $workflowName)) {
                $matched[] = $workflow;
            }
        }

        if (!$matched) {
            throw new InvalidArgumentException(sprintf('Unable to find a workflow for class "%s".', get_debug_type($subject)));
        }

        if (2 <= \count($matched)) {
            $names = array_map(static function (WorkflowInterface $workflow): string {
                return $workflow->getName();
            }, $matched);

            throw new InvalidArgumentException(sprintf('Too many workflows (%s) match this subject (%s); set a different name on each and use the second (name) argument of this method.', implode(', ', $names), get_debug_type($subject)));
        }

        return $matched[0];
    }

    /**
     * @return Workflow[]
     */
    public function all(object $subject): array
    {
        $matched = [];
        foreach ($this->workflows as [$workflow, $supportStrategy]) {
            if ($supportStrategy->supports($workflow, $subject)) {
                $matched[] = $workflow;
            }
        }

        return $matched;
    }

    private function supports(WorkflowInterface $workflow, WorkflowSupportStrategyInterface $supportStrategy, object $subject, ?string $workflowName): bool
    {
        if (null !== $workflowName && $workflowName !== $workflow->getName()) {
            return false;
        }

        return $supportStrategy->supports($workflow, $subject);
    }
}
