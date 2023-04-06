<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkflowTaskOperator extends Model
{
    use HasFactory, SoftDeletes;

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

    public function scopeCanApprove($query)
    {
        if ($this->operator_type == 'staff') {
            if (in_array(auth()->id(), $this->operators)) {
                return true;
            }
        } elseif ($this->operator_type == 'role') {
            if (in_array(auth()->user()->role->id, $this->operators)) {
                return true;
            }
        }
    }

    public function getOperators()
    {
        if ($this->operator_type == 'staff') {
        } elseif ($this->operator_type == 'role') {
        }
    }
}
