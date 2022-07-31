<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortConfig extends Model
{
   use HasFactory, \OwenIt\Auditing\Auditable;

    public $guarded = [];
}
