<?php

namespace App\Models\Returns\StampDuty;

use App\Models\ZmBill;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StampDutyReturn extends Model
{
    use HasFactory, SoftDeletes;

    public const SUBMITTED = 'submitted';
    public const COMPLETE = 'complete';

    protected $guarded = [];

    public function bill(){
        return $this->morphOne(ZmBill::class, 'billable');
    }
}
