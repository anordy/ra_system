<?php

namespace App\Models\Debts;

use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Debts\DebtRecoveryMeasure;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecoveryMeasure extends Model
{
    use HasFactory, WorkflowTrait;

    protected $guarded = [];

    public function debt()
    {
        return $this->morphTo();
    }

    public function measures() {
        return $this->hasMany(DebtRecoveryMeasure::class, 'recovery_measure_id');
    }

}
