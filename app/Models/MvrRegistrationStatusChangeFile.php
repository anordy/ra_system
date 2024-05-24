<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MvrRegistrationStatusChangeFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mvr_registrations_status_change_files';


    protected $guarded = [];
}
