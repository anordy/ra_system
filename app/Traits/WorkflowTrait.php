<?php

namespace App\Traits;

use App\Models\WorkflowTask;

trait WorkflowTrait
{

    public function pinstances()
    {
        return $this->morphMany(WorkflowTask::class, 'pinstance');
    }    
    
    public function pinstance()
    {
        return $this->morphOne(WorkflowTask::class, 'pinstance')->latest();
    }

    public function pinstancesActive()
    {
        return $this->morphOne(WorkflowTask::class, 'pinstance')->latestOfMany();
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
