<?php

namespace App\Models\ReportRegister;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RgSettings extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rg_settings';

    protected $guarded = [];

    public const DAYS_TO_BREACH = 'days-to-breach';

}
