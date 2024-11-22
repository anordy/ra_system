<?php

namespace App\Models\ReportRegister;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'sh_departments';

    protected $guarded = [];
}
