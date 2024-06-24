<?php

namespace App\Services\Workflow;

use App\Enum\GeneralConstant;
use App\Services\Workflow\MarkingStore\MarkingStoreInterface;
use App\Services\Workflow\MarkingStore\MethodMarkingStore;
use App\Services\Workflow\SupportStrategy\InstanceOfSupportStrategy;
use Illuminate\Support\Facades\Log;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class WorkflowRegistry
{

    protected $registry;
    protected $config;
    protected $dispatcher;


    public function __construct(array $config, $subscriber)
    {
        $this->registry = new Registry();
        $this->config = $config;
        $this->dispatcher = new EventDispatcher();

        if ($subscriber instanceof EventSubscriberInterface) {
            $this->dispatcher->addSubscriber($subscriber);

        }
        foreach ($this->config as $name => $workflowData) {
            $this->addFromArray($name, $workflowData);
        }
    }

    /**
     * Return the $subject workflow
     *
     * @param object $subject
     * @param string $workflowName
     * @return Workflow
     */
    public function get($subject, $workflowName = null)
    {
        return $this->registry->get($subject, $workflowName);
    }

    /**
     * Add a workflow to the subject
     *
     * @param Workflow $workflow
     * @param string $supportStrategy
     */
    public function add(Workflow $workflow, $supportStrategy)
    {
        $this->registry->addWorkflow($workflow, new InstanceOfSupportStrategy($supportStrategy));
    }

    /**
     * Add a workflow to the registry from array
     *
     * @param string $name
     * @param array $workflowData
     * @throws \ReflectionException
     */
    public function addFromArray($name, array $workflowData)
    {

        try {
            $builder = new DefinitionBuilder($workflowData['places']);

            foreach ($workflowData['transitions'] as $transitionName => $transition) {
                if (!is_string($transitionName)) {
                    $transitionName = $transition['name'];
                }

                foreach ((array)$transition['from'] as $form) {
                    $builder->addTransition(new Transition($transitionName, $form, $transition['to'], $transition['condition']));
                }

            }

            $definition = $builder->build();

            $markingStore = $this->getMarkingStoreInstance($workflowData);
            $workflow = $this->getWorkflowInstance($name, $workflowData, $definition, $markingStore);

            foreach ($workflowData['supports'] as $supportedClass) {
                $this->add($workflow, $supportedClass);
            }
        } catch (\Exception $exception) {
            Log::error('WORKFLOW-WORKFLOW-REGISTRY-ADD-FROM-ARRAY');
            throw $exception;
        }

    }

    /**
     * Return the workflow instance
     *
     * @param String $name
     * @param array $workflowData
     * @param Definition $definition
     * @param MarkingStoreInterface $markingStore
     * @return Workflow
     */
    protected function getWorkflowInstance(
        $name,
        array $workflowData,
        Definition $definition,
        MarkingStoreInterface $markingStore
    )
    {
        try {
            if (isset($workflowData['class'])) {
                $className = $workflowData['class'];
            } elseif (isset($workflowData['type']) && $workflowData['type'] === GeneralConstant::STATE_MACHINE) {
                $className = StateMachine::class;
            } else {
                $className = Workflow::class;
            }

            return new $className($definition, $markingStore, $this->dispatcher, $name);

        } catch (\Exception $exception) {
            Log::error('WORKFLOW-WORKFLOW-REGISTRY-GET-WORKFLOW-INSTANCE');
            throw $exception;
        }
    }

    /**
     * Return the making store instance
     *
     * @param array $workflowData
     * @return MarkingStoreInterface
     * @throws \ReflectionException
     */
    protected function getMarkingStoreInstance(array $workflowData)
    {
        try {
            $markingStoreData = $workflowData['marking_store'] ?? [];
            $arguments = [];

            if (isset($markingStoreData['class'])) {
                $className = $markingStoreData['class'];
            } elseif (isset($markingStoreData['type']) && $markingStoreData['type'] === GeneralConstant::SINGLE_STATE) {
                $className = MethodMarkingStore::class;
                $arguments = [true];
            } else {
                $className = MethodMarkingStore::class;
            }

            $class = new \ReflectionClass($className);

            return $class->newInstanceArgs($arguments);

        } catch (\Exception $exception) {
            Log::error('WORKFLOW-WORKFLOW-REGISTRY-GET-MARKING-STORE');
            throw $exception;
        }
    }


}
