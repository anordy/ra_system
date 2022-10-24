<?php

namespace App\Models\Debts;

use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Debts\DebtRecoveryMeasure;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;

class RecoveryMeasure extends Model implements Auditable
{
    use HasFactory, WorkflowTrait, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function debt()
    {
        return $this->morphTo();
    }

    public function measures() {
        return $this->hasMany(DebtRecoveryMeasure::class, 'recovery_measure_id');
    }

}
