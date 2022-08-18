<?php

namespace App\Models\Installment;

use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstallmentRequest extends Model
{
    use HasFactory, SoftDeletes, WorkflowTrait;

    protected $guarded = [];
}
