<?php

namespace App\Models\Tra;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TraVehicleTransmissionType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tra_vehicle_trans_types';

    protected $guarded = [];
}
