<?php

namespace App\Services\Workflow;


class Transition
{
    private $name;
    private $froms;
    private $tos;
    private $condition;

    /**
     * @param string $name
     * @param string|string[] $froms
     * @param string|string[] $tos
     * @param string $guard
     * @param string $condition
     */
    public function __construct(string $name, $froms, $tos, $condition = null)
    {
        $this->name = $name;
        $this->froms = (array) $froms;
        $this->tos = (array) $tos;
        $this->condition = $condition;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getFroms()
    {
        return $this->froms;
    }

    /**
     * @return string[]
     */
    public function getTos()
    {
        return $this->tos;
    }


    /**
     * @return string
     */
    public function getCondition()
    {
        return $this->condition;
    }
}
