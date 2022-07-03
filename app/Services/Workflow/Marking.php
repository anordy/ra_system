<?php

namespace App\Services\Workflow;

class Marking
{
    private $places = [];
    private $context = null;

    /**
     * @param int[] $representation Keys are the place name and values should be 1
     */
    public function __construct(array $representation = [])
    {
        foreach ($representation as $place => $nbToken) {
            $this->mark($place, $nbToken);
        }
    }

    public function mark(string $place, array $data)
    {
        $data['status'] = 1;
        $this->places[$place] = $data;
    }

    public function unmark(string $place)
    {
        unset($this->places[$place]);
    }

    public function has(string $place)
    {
        return isset($this->places[$place]);
    }

    public function getPlaces()
    {
        return $this->places;
    }

    /**
     * @internal
     */
    public function setContext(array $context): void
    {
        $this->context = $context;
    }

    /**
     * Returns the context after the subject has transitioned.
     */
    public function getContext(): ?array
    {
        return $this->context;
    }
}
