<?php

namespace App\Models\Debts;

use App\Models\Taxpayer;
use App\Models\Returns\TaxReturn;
use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DebtWaiver extends Model
{
    use HasFactory, WorkflowTrait;

    protected $guarded = [];

    public function debt()
    {
        return $this->morphTo();
    }

    public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class, 'filed_by_id');
    }

    
}
