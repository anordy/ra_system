<?php

namespace App\Traits;

use App\Models\WorkflowTask;

trait WorkflowTrait
{

    public function pinstances()
    {
        return $this->morphMany(WorkflowTask::class, 'pinstance');
    }

    public function getMarking()
    {
        return $this->marking;
    }

    public function setMarking($marking, $context = [])
    {
        $this->marking = $marking;
    }


   
}
