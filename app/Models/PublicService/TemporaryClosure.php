<?php

namespace App\Models\PublicService;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryClosure extends Model
{
    use HasFactory;

    protected $table = 'public_service_temporary_closures';

    protected $guarded = [];
}
