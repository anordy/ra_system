<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowTask extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function pinstance()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->morphTo();
    }

    public function business()
    {
        return $this->morphTo();
    }

    public function actors()
    {
        return $this->hasMany(WorkflowTaskOperator::class, 'task_id');
    }

    public function scopeCanApprove($query)
    {
        if ($this->operator_type == 'staff') {
            if (in_array(auth()->id, $this->operators)) {
                return true;
            }
        } elseif ($this->operator_type == 'role') {
            if (in_array(auth()->user()->role->id, $this->operators)) {
                return true;
            }
        }
    }
}
