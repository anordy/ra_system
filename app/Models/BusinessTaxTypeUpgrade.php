<?php

namespace App\Models;

use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessTaxTypeUpgrade extends Model
{
    use HasFactory, WorkflowTrait;

    protected $guarded = [];
}
