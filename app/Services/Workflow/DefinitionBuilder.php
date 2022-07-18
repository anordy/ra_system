<?php

namespace App\Services\Workflow;


class DefinitionBuilder
{
    private $places = [];
    private $transitions = [];
    private $initialPlaces;

    /**
     * @param string[]     $places
     * @param Transition[] $transitions
     */
    public function __construct(array $places = [], array $transitions = [])
    {
        $this->addPlaces($places);
        $this->addTransitions($transitions);
    }

    /**
     * @return Definition
     */
    public function build()
    {
        return new Definition($this->places, $this->transitions, $this->initialPlaces);
    }

    /**
     * Clear all data in the builder.
     *
     * @return $this
     */
    public function clear()
    {
        $this->places = [];
        $this->transitions = [];
        $this->initialPlaces = null;

        return $this;
    }

    /**
     * @param string|string[]|null $initialPlaces
     *
     * @return $this
     */
    public function setInitialPlaces($initialPlaces)
    {
        $this->initialPlaces = $initialPlaces;

        return $this;
    }

    /**
     * @return $this
     */
    public function addPlace(string $place, $data)
    {
        if (!$this->places) {
            $this->initialPlaces[$place] = $data;
        }

        $this->places[$place] = $data;

        return $this;
    }

    /**
     * @param string[] $places
     *
     * @return $this
     */
    public function addPlaces(array $places)
    {
        foreach ($places as $key => $place) {
            $this->addPlace($key, $place);
        }

        return $this;
    }

    /**
     * @param Transition[] $transitions
     *
     * @return $this
     */
    public function addTransitions(array $transitions)
    {
        foreach ($transitions as $transition) {
            $this->addTransition($transition);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function addTransition(Transition $transition)
    {
        $this->transitions[] = $transition;

        return $this;
    }

 
}
