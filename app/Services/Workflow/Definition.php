<?php

namespace App\Services\Workflow;

use App\Services\Workflow\Exception\LogicException;


final class Definition
{
    private $places = [];
    private $transitions = [];
    private $initialPlaces = [];

    /**
     * @param string[]             $places
     * @param Transition[]         $transitions
     * @param string|string[]|null $initialPlaces
     */
    public function __construct(array $places, array $transitions, $initialPlaces = null)
    {
        foreach ($places as $key => $place) {
            $this->addPlace($key, $place);
        }

        foreach ($transitions as $transition) {
            $this->addTransition($transition);
        }

        $this->setInitialPlaces($initialPlaces);

    }

    /**
     * @return string[]
     */
    public function getInitialPlaces(): array
    {
        return $this->initialPlaces;
    }

    /**
     * @return string[]
     */
    public function getPlaces(): array
    {
        return $this->places;
    }

    /**
     * @return Transition[]
     */
    public function getTransitions(): array
    {
        return $this->transitions;
    }



    private function setInitialPlaces($places = null)
    {
        if (!$places) {
            return;
        }


        $places = (array) $places;

        foreach ($places as $key=> $place) {
            if (!isset($this->places[$key])) {
                throw new LogicException(sprintf('Place "%s" cannot be the initial place as it does not exist.', $place));
            }
        }

        $this->initialPlaces = $places;
    }

    private function addPlace(string $place, $data)
    {
        if (!\count($this->places)) {
            $this->initialPlaces[$place] = $data;
        }

        $this->places[$place] = $data;
    }

    private function addTransition(Transition $transition)
    {
        $name = $transition->getName();

        foreach ($transition->getFroms() as $from) {
            if (!isset($this->places[$from])) {
                throw new LogicException(sprintf('Place "%s" referenced in transition "%s" does not exist.', $from, $name));
            }
        }

        foreach ($transition->getTos() as $to) {
            if (!isset($this->places[$to])) {
                throw new LogicException(sprintf('Place "%s" referenced in transition "%s" does not exist.', $to, $name));
            }
        }

        $this->transitions[] = $transition;
    }
}
