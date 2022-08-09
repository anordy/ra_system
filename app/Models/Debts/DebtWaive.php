<?php

namespace App\Models\Debts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DebtWaive extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded =[];
}
